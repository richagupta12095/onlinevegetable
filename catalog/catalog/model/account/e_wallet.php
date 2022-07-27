<?php
class ModelAccountEwallet extends Model{
	public $per = DB_PREFIX;
	public function gettransaction($data = array()){
		if (isset($data['customer_id'])) {
			$str = "SELECT * FROM `{$this->per}e_wallet_transaction`
			WHERE customer_id = " . (int) $data['customer_id'];
		} else {
			$str = "SELECT * FROM `{$this->per}e_wallet_transaction`
			WHERE customer_id = " . (int) $this->customer->getId();
		}
		if(isset($data['datefrom'])) $str .= " AND date_added >= '".date('Y-m-d',strtotime($data['datefrom']))."'";
		if(isset($data['dateto'])) $str .= " AND date_added <= '".date('Y-m-d',strtotime($data['dateto'].' +1 days'))."'";
		$str .= " ORDER BY date_added DESC ";
		$start = 0; $limit = 20;
		if(isset($data['start'])) $start = $data['start'];
		if(isset($data['limit'])) $limit = $data['limit'];
		$str .= " LIMIT ".$start." , ".$limit;
		$data = $this->db->query($str);
		return $data->rows;
	}
	public function gettransactiontotal($data = array()){
		$str = "SELECT COUNT(*) AS total FROM `{$this->per}e_wallet_transaction` 
			WHERE customer_id = ".(int)$this->customer->getId();
		if(isset($data['datefrom'])) $str .= " AND date_added >= '".date('Y-m-d',strtotime($data['datefrom']))."'";
		if(isset($data['dateto'])) $str .= " AND date_added <= '".date('Y-m-d',strtotime($data['dateto'].' +1 days'))."'";
		$data = $this->db->query($str);
		return $data->row['total'];
	}
	public function getopenningbalance($data = array()){
		$time = strtotime($data['datefrom'].' -1 days');
		$str = "SELECT balance FROM `{$this->per}e_wallet_transaction` 
			WHERE customer_id = ".(int)$this->customer->getId();
		if(isset($data['datefrom'])) $str .= " AND DATE(date_added) <= '".date('Y-m-d',$time)."'";
		$str .= " ORDER BY date_added DESC ";
		$data = $this->db->query($str);
		 
		if($data->num_rows)
			return $data->row['balance'];
		return 0;
	}
	public function getbank($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$str = "SELECT * FROM `{$this->per}e_wallet_bank` 
			WHERE customer_id = ".(int)$customer_id;
		$data = $this->db->query($str);
		if($data->num_rows)
			return $data->row;
		return array();
	}
	public function setbank($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$bank = $this->getbank($data);
		if(!$bank) $str = "INSERT INTO ";
		else $str = "UPDATE ";
		$str .= "`{$this->per}e_wallet_bank` SET
			`bank_name` = '{$data['bank_name']}',
			`branch_code` = '{$data['branch_code']}',
			`swift` = '{$data['swift']}',
			`ifsc` = '{$data['ifsc']}',
			`ac_name` = '{$data['ac_name']}',
			`ac_no` = '{$data['ac_no']}'
		";
		if($bank) $str .= " WHERE customer_id = ".(int)$customer_id;
		else $str .= " ,`customer_id` = '{$customer_id}' ";
		$this->db->query($str);
	}
	public function setbalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$data['customer_id'] = $customer_id;
		$balance = (float)$this->getBalance($data);
		$str = "UPDATE `{$this->per}e_wallet_transaction` SET
			balance = {$balance}
			WHERE customer_id = ".$customer_id." AND transaction_id = ".(int)$data['transaction_id'];
		$this->db->query($str);
	}
	public function getBalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$str = "SELECT SUM(price) as total FROM `{$this->per}e_wallet_transaction` WHERE customer_id = ".$customer_id;
		$data = $this->db->query($str);
		return $data->row['total'];
	}
	public function addcod_request($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$sql = "INSERT INTO `".DB_PREFIX."cod_request` SET 
			customer_id = ".$customer_id." ,
			amount = ".(float)$data['amount']." ,
			description = '".$this->db->escape($data['desc'])."',
			date_added = NOW()";
		$this->db->query($sql);
		$cod_request_id = $this->db->getLastId();
		return $cod_request_id;
	}
	public function addtransaction($data = array()){
		if(isset($data['customer_id']) && (int)$data['customer_id']){
			$customer_id = (int)$data['customer_id'];
		}else if((int)$this->customer->getId()){
			$customer_id = (int)$this->customer->getId();
		}  
		$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
			`customer_id` = '".(int)$customer_id."',
			`price` = '".(float)$data['amount']."',
			`description` = '".$this->db->escape($data['desc'])."',
			`date_added` = NOW()";
		$this->db->query($str);
		$transaction_id = $this->db->getLastId();
		$this->setbalance(array('customer_id' => $customer_id,'transaction_id' => $transaction_id));
		if($this->customer->getId()){
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$subject = $this->config->get('e_wallet_title')." Money Transaction.";
			$message = $data['desc']." \n Amount : ".$data['amount']." \n Date : ".date('d-m-Y h:i:s A');
			$mail->setTo($this->customer->getEmail());
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();
		}
		return $transaction_id;
	}
	public function sendmoney($data = array()){
		if(isset($data['customer_id']) && (int)$data['customer_id'] != 0){
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$data['customer_id']."',
				`price` = '".(float)$data['amount']."',
				`description` = '".$this->db->escape('You Receive Money From '.$this->customer->getFirstName())."',
				`date_added` = NOW()";
			$this->db->query($str);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $data['customer_id'],'transaction_id' => $transaction_id));
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$this->customer->getId()."',
				`price` = '".(float)-$data['amount']."',
				`description` = '".$this->db->escape('You Send Money to '.$data['name'])."',
				`date_added` = NOW()";
			$this->db->query($str);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $this->customer->getId(),'transaction_id' => $transaction_id));
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$subject = $this->config->get('e_wallet_title')." Money Transaction.";
			$message = 'You Receive Money From '.$this->customer->getFirstName().
				" \n Amount : ".$data['amount'].
				" \n Email : ".$this->customer->getEmail().
				" \n Date : ".date('d-m-Y h:i:s A');
			$mail->setTo($data['email']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();
			$subject = $this->config->get('e_wallet_title')." Money Transaction.";
			$message = 'You Send Money to '.$data['name'].
				" \n Amount : ".$data['amount'].
				" \n Email : ".$data['email'].
				" \n Date : ".date('d-m-Y h:i:s A');
			$mail->setTo($this->customer->getEmail());
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();
		}
	}
	public function withdrawmoney($data = array()){
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$this->customer->getId()."',
				`price` = '".(float)-$data['amount']."',
				`description` = '".$this->db->escape('You withdraw Amount :'.$data['amount'])."',
				`date_added` = NOW()";
			$this->db->query($str);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $this->customer->getId(),'transaction_id' => $transaction_id));
			$customer_id = (int)$this->customer->getId();
			$sql = "INSERT INTO `".DB_PREFIX."withdraw_request` SET 
				customer_id = ".$customer_id." ,
				amount = ".(float)$data['amount']." ,
				description = 'You withdraw Amount :".$data['amount']."',
				date_added = NOW()";
			$this->db->query($sql);
	}
}