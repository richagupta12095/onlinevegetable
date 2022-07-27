<?php

class ModelLocalisationZone extends Model {

	public function getZone($zone_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");



		return $query->row;

	}



	public function getZonesByCountryId($country_id) {

		$zone_data = $this->cache->get('zone.' . (int)$country_id);



		if (!$zone_data) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");



			$zone_data = $query->rows;



			$this->cache->set('zone.' . (int)$country_id, $zone_data);

		}



		return $zone_data;

	}

	public function getCitiesByZoneId($zone_id) {
		$city_data = $this->cache->get('city.' . (int)$zone_id);

		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE zone_id = '" . (int)$zone_id . "' AND status = '1' ORDER BY name");
			
			
			$city_data = $query->rows;

			$this->cache->set('city.' . (int)$zone_id, $city_data);
		}		
		return $city_data;
	}

}