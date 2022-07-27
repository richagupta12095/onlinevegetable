<?php
namespace smshare_core;
class smshare_core_dao extends smshare_core_000 {
    /**
     *
     */
    public function get_order_status_name($language_id, $order_status_id){

        if(! isset($order_status_id)){
            if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] No order status id, no status name. Returning empty!");
            return "";
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$language_id . "'";
        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] SQL to get order status name: " . $sql);
        $order_status_query = $this->db->query($sql);

        $order_status = '';
        if ($order_status_query->num_rows) {
            $order_status = $order_status_query->row['name'];
        }

        return $order_status;
    }



    /**
     * not used
     */
    public function get_country_iso_code_2($address_id){

        if ($this->config->get('smshare_core_cfg_log')) $this->log->write("[pws] Going to get country code for address_id: $address_id");

        /*
         *
         */
        $address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

        if ($address_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

            if ($country_query->num_rows) {
                $iso_code_2 = $country_query->row['iso_code_2'];
                return $iso_code_2;
            }
        }

        return "";
    }



}