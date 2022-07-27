<?php
class ModelApiFcm extends Model {


 public function getKey(){


 }

 public function getKeycount($userId){
 	$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."fcm_key WHERE userId = '" . (int)$userId . "'");
 	return $query->row['total'];
 }

public function addKey($userId,$fcm_key){
		$sql = "INSERT INTO `".DB_PREFIX."fcm_key` SET 
		userId = ".$userId." ,
		fcm_key = '".$fcm_key."',
		created_at = NOW()";
		$this->db->query($sql);


 }

public function updateKey($userId,$fcm_key){
	$sql2 = "UPDATE " . DB_PREFIX . "fcm_key SET fcm_key = '" .$fcm_key. "' WHERE userId= '" .$userId . "'";
    $query2 = $this->db->query($sql2);

}

 public function sendmsg(){
 	$query = $this->db->query("SELECT * FROM ".DB_PREFIX."fcm_key");
 	return $query->rows;
 }


}