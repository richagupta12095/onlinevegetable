<?php
class ModelAccountMembership extends Model {
	
	public function getMemebership($userId){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mpplan_customer WHERE customer_id = '" . (int)$userId . "'");
		return $query->row;
	}

  public function getVegOrderDetails($id) {
  
            $sql = "SELECT vol.*,c.firstname,mc.plan_name,p.model,ds.name,wv.name as weight_value,wv.value as wgt,ds.name as dname,sum(wv.value) as total_weight,tslot.name as slotname
                    FROM `oc_customer_weekly_veg_orders` cwo
                    LEFT JOIN `oc_mpplan_customer` AS mc ON cwo.customer_id = mc.customer_id
                    LEFT JOIN `oc_customer` AS c ON c.customer_id = mc.customer_id
                    LEFT JOIN `oc_veg_order_line_items` AS vol ON vol.veg_order_id = cwo.id
                    LEFT JOIN `oc_product` p ON vol.product_id = p.product_id
                    LEFT JOIN `oc_delivery_settings` ds ON ds.id =cwo.delivery_id
                    LEFT JOIN `oc_weight_values` wv ON wv.id = vol.weight_id
                    LEFT JOIN `oc_timeslots_settings` tslot ON tslot.id = cwo.slot_id
                    WHERE cwo.customer_id ='".$id."' and DATE(cwo.created_at)=CURDATE()  GROUP by cwo.id";

                    $query = $this->db->query($sql);

        return $query->rows;
    }


  public function getcurrentVegOrderDetails($id) {
        $sql = "SELECT vol.*,c.firstname,mc.plan_name,p.model,ds.name,wv.name as weight_value,wv.value as wgt,ds.name as dname,sum(wv.value) as total_weight,tslot.name as slotname,ds.id as delivery_id
                    FROM `oc_customer_weekly_veg_orders` cwo
                    LEFT JOIN `oc_mpplan_customer` AS mc ON cwo.customer_id = mc.customer_id
                    LEFT JOIN `oc_customer` AS c ON c.customer_id = mc.customer_id
                    LEFT JOIN `oc_veg_order_line_items` AS vol ON cwo.id= vol.veg_order_id 
                    LEFT JOIN `oc_product` p ON vol.product_id = p.product_id
                    LEFT JOIN `oc_delivery_settings` ds ON ds.id =cwo.delivery_id
                    LEFT JOIN `oc_weight_values` wv ON wv.id = vol.weight_id
                    LEFT JOIN `oc_timeslots_settings` tslot ON tslot.id = cwo.slot_id
                    WHERE cwo.customer_id ='".$id."' and YEARWEEK(cwo.created_at)=YEARWEEK(NOW()) GROUP by cwo.id";
                    $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getcurrentVegOrderForCategoryAccess($id) {
        $monday = date('Y-m-d', strtotime( 'monday this week' ) );
        $daystartsfrom = date('N');
        $sql = "SELECT cwo.*,cwo.id as veg_order_id,ds.name,ds.name as dname,tslot.name as slotname,ds.id as delivery_id
                FROM `oc_customer_weekly_veg_orders` cwo           
                LEFT JOIN `oc_delivery_settings` ds ON ds.id =cwo.delivery_id
                LEFT JOIN `oc_timeslots_settings` tslot ON tslot.id = cwo.slot_id
                WHERE cwo.customer_id ='".$id."' and cwo.week_date='". $monday."' AND cwo.delivery_id >".$daystartsfrom; 
                $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getEditButtonEnabledDetails($id) {
        $monday = date('Y-m-d', strtotime( 'monday this week' ) );
        $daystartsfrom = date('N') +2;
        $sql = "SELECT cwo.*,cwo.id as veg_order_id,ds.name,ds.name as dname,tslot.name as slotname,ds.id as delivery_id
                    FROM `oc_customer_weekly_veg_orders` cwo           
                    LEFT JOIN `oc_delivery_settings` ds ON ds.id =cwo.delivery_id
                    LEFT JOIN `oc_timeslots_settings` tslot ON tslot.id = cwo.slot_id
                    WHERE cwo.customer_id ='".$id."' AND cwo.delivery_id >= ".$daystartsfrom." AND  cwo.week_date='". $monday."' AND order_status_id=2"; 
                    /*WHERE cwo.customer_id ='".$id."' and YEARWEEK(cwo.created_at)=YEARWEEK(NOW()) GROUP by cwo.id"; */
                    
                    $query = $this->db->query($sql);

        return $query->rows;
    }


     public function getcurrentVegOrderitem($orderId) {
        $sql = "SELECT  vitem.*,p.model,wv.name as weight_value,wv.value as wgt from  oc_veg_order_line_items as vitem 
        LEFT JOIN `oc_customer_weekly_veg_orders` cwo on cwo.id=vitem.veg_order_id
        LEFT JOIN `oc_product` p ON p.product_id = vitem.product_id
        lEFT JOIN `oc_delivery_settings` ds ON ds.id =cwo.delivery_id
        LEFT JOIN `oc_weight_values` wv ON wv.id = vitem.weight_id
        WHERE vitem.veg_order_id ='".$orderId."'";
        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getdeliverysetting(){
        $daystartsfrom = date('N') +2;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_settings where status=1 AND id >= ".$daystartsfrom);
        return $query->rows;
    }

    public function getTimeslot(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslots_settings where status=1");
        return $query->rows;
    }


	public function getMemebershipprdcat($mebsipId){
		$query = $this->db->query("SELECT  category_id FROM " . DB_PREFIX . "mpplan_product_category WHERE mpplan_id = '" . (int)$mebsipId . "'");
		return $query->rows;

	}
    public function saveliverysetting($userId,$dval){
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_delivery_settings SET customer_id = '" . (int)$userId . "', delivery_settings_id = '" . (int)$dval . "', created_at = NOW()");
    }
    public function deldsettings($id){

        $this->db->query("delete from   " . DB_PREFIX . "customer_delivery_settings  WHERE customer_id = '" . (int)$id . "'");

    }

    public function deleteOrderLineItems($data){
          $monday = date('Y-m-d', strtotime( 'monday this week' ) );
        
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_weekly_veg_orders WHERE customer_id = '" . (int)$data['customer_id'] . "' AND week_date='".$monday."' AND status=1");
          $resdata=$query->rows;

          foreach ($resdata as $key => $value) {
           $this->db->query("Delete from   " . DB_PREFIX . "veg_order_line_items  WHERE veg_order_id = '" . (int)$value['id'] . "'");
          }

        $this->db->query("Delete from   " . DB_PREFIX . "customer_weekly_veg_orders  WHERE customer_id = '" . (int)$data['customer_id'] . "' AND week_date='".$monday."' AND status=1");
   
    }

  public function getdsetting($id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_delivery_settings WHERE customer_id = '" . (int)$id . "'");
        return $query->rows;
    }

     public function getselectedday($id){

        $query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."customer_delivery_settings WHERE customer_id = '" . (int)$id . "'");

        return $query->row['total'];
    }
	public function getProductweight($pid){

		$query = $this->db->query("SELECT  *  FROM " . DB_PREFIX . "product_to_weight WHERE product_id = '" . (int)$pid . "'");
		$data =$query->rows;
        if(!empty($data)){
            $arr=array();
            foreach ($data as $key => $value) {
                $ar[]=$value['weight_id'];
            }
        }
        if($data){
            $exp=implode(',',$ar);
            $query=$this->db->query("SELECT  *  FROM " . DB_PREFIX . "weight_values WHERE id  IN (" . implode(',', $ar) . ") ");
        
    	   return $query->rows;
		}
	}

    
     public function getlistdelivery($uid) {
            $query = $this->db->query($query = "SELECT * FROM `" . DB_PREFIX . "customer_delivery_settings` ds LEFT JOIN `" . DB_PREFIX . "delivery_settings` pd ON pd.id = ds.delivery_settings_id WHERE ds.customer_id = " .$uid. " and ds.status=1 LIMIT 20");
            return $query->rows;

    }
  public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
		return $query->rows;

	}
	public function getProducts($data = array()) {
    $sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,pd.name,pd.description,p.quantity";

    if (!empty($data['filter_category_id'])) {
        if (!empty($data['filter_sub_category'])) {
            $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
        }

        if (!empty($data['filter_filter'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
        } else {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
        }
    } else {
        $sql .= " FROM " . DB_PREFIX . "product p";
    }

    $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

    if (!empty($data['filter_category_id'])) {
        if (!empty($data['filter_sub_category'])) {
            $sql .= " AND cp.path_id IN (" . implode(',', $data['filter_category_id']) . ")";
        } else {
            $sql .= " AND p2c.category_id IN (" . implode(',', $data['filter_category_id']) . ")";
        }

        if (!empty($data['filter_filter'])) {
            $implode = array();

            $filters = explode(',', $data['filter_filter']);

            foreach ($filters as $filter_id) {
                $implode[] = (int)$filter_id;
            }

            $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
        }
    }

    if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
        $sql .= " AND (";

        if (!empty($data['filter_name'])) {
            $implode = array();

            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

            foreach ($words as $word) {
                $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
            }

            if ($implode) {
                $sql .= " " . implode(" AND ", $implode) . "";
            }

            if (!empty($data['filter_description'])) {
                $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
            $sql .= " OR ";
        }

        if (!empty($data['filter_tag'])) {
            $sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
        }

        $sql .= ")";
    }

    if (!empty($data['filter_manufacturer_id'])) {
        $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
    }

    $sql .= " GROUP BY p.product_id";

    $sort_data = array(
        'pd.name',
        'p.model',
        'p.quantity',
        'p.price',
        'rating',
        'p.sort_order',
        'p.date_added'
    );

    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
        if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
            $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
        } elseif ($data['sort'] == 'p.price') {
            $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
        } else {
            $sql .= " ORDER BY " . $data['sort'];
        }
    } else {
        $sql .= " ORDER BY p.sort_order";
    }

    if (isset($data['order']) && ($data['order'] == 'DESC')) {
        $sql .= " DESC, LCASE(pd.name) DESC";
    } else {
        $sql .= " ASC, LCASE(pd.name) ASC";
    }

    if (isset($data['start']) || isset($data['limit'])) {
        if ($data['start'] < 0) {
            $data['start'] = 0;
        }

        if ($data['limit'] < 1) {
            $data['limit'] = 20;
        }

        $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
    }

    $product_data = array();
    $query = $this->db->query($sql);
    return $query->rows;
    }
    
    public function addVegOrders($data,$lineItems){
        
        $sql = "INSERT INTO " . DB_PREFIX . "customer_weekly_veg_orders SET customer_id = '" . (int)
        $data['customer_id'] . "', week_id = '1', week_date = '".$data['week_date']."', type = '1', delivery_id = '" . $this->db->escape($data['delivery_id']) . "', slot_id= '".$this->db->escape($data['slot_id'])."', status = 1, updated_at = NOW(),created_at = NOW()";
        $query = $this->db->query($sql);
        $veg_order_id = $this->db->getLastId();
        if($query){
            $prodsql = '';
            foreach($lineItems as $key=>$prod){
                $prodsql = "INSERT INTO " . DB_PREFIX . "veg_order_line_items SET veg_order_id = '".$veg_order_id."', product_id = '".$prod['product_id']."', weight_id = '".$prod['weight']."', created_at = NOW()";
                $this->db->query($prodsql);
            }           
        }
    }

    public function getVegOrderWeight($data,$cust){
        
        $sql = "SELECT id,value as total_weight FROM " . DB_PREFIX . "weight_values WHERE id IN (".implode(",",$data).")";
        $array_count = array_count_values($data);
        $query = $this->db->query($sql);
        $total_weight = 0;
        foreach($query->rows as $key=>$singlerow){
            $total_weight = $total_weight + $array_count[$singlerow['id']] * $singlerow['total_weight'];            
        }
        
        $wh_sql = "SELECT weight_allowed FROM " . DB_PREFIX . "mpplan_customer WHERE customer_id='".$cust."' AND active=1 LIMIT 0,1";        
        $weight_sql = $this->db->query($wh_sql);
        if($query->num_rows > 0){
            if( $weight_sql->row['weight_allowed'] >= $total_weight){
                return true;
            }
        }
    }

    
    public function getCustomerWeightAllowded($cust){
        
        $wh_sql = "SELECT weight_allowed FROM " . DB_PREFIX . "mpplan_customer WHERE customer_id='".$cust."' AND active=1 LIMIT 0,1";        
        $weight_sql = $this->db->query($wh_sql);
        return $weight_sql->row['weight_allowed'];
    }

    public function checkCustomerFilledVegOrder($data){
        
        $sql ="SELECT * FROM " . DB_PREFIX . "customer_weekly_veg_orders WHERE customer_id = '" .$data['customer_id']."' AND week_date='".$data['week_date']."'";
        $query = $this->db->query($sql);
        if($query->num_rows > 0){
            return false;    
        }       
        return true; 
    }

    public function checkweekorderexsit($data){
        
        $sql ="SELECT * FROM " . DB_PREFIX . "customer_weekly_veg_orders WHERE customer_id = '" .$data['customer_id']."' AND week_date='".$data['week_date']."'";
        $query = $this->db->query($sql);
        if($query->num_rows > 0){
            return true;    
        }else{
            return false;
        }       
    }


    public function getVegOrderLineItems($data) {
        $monday = date('Y-m-d', strtotime( 'monday this week' ) );
        $sql = "SELECT vo.id,vo.delivery_id,vo.slot_id,vl.product_id,vl.weight_id FROM `oc_customer_weekly_veg_orders` as vo left JOIN `oc_veg_order_line_items` vl ON vo.id=vl.veg_order_id WHERE customer_id='".$data['customer_id']."' AND week_date='". $monday."' AND status=1";
        $query = $this->db->query($sql);
        if($query->num_rows > 0){
            return $query->rows;
        }else{
            return array();
        }
    }
}