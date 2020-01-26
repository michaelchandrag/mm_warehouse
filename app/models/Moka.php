<?php
namespace Models;

class Moka {
	public $client_id;
	public $client_secret;
	protected $access_token;
	protected $business_id;

	public function __construct() {
		$this->url = "https://api.mokapos.com/v1/";
		$this->client_id = getenv('MOKA_CLIENT_ID');
		$this->client_secret = getenv('MOKA_CLIENT_SECRET');
		$this->access_token = getenv('MOKA_ACCESS_TOKEN');
		$this->business_id = getenv('MOKA_BUSINESS_ID');
	}

	public function GetBusinessProfile() {
		$url = "https://api.mokapos.com/v1/businesses";
		$headers = [
		    'Authorization' => 'Bearer ' . $this->access_token,        
		    'Accept'        => 'application/json',
		];
		return guzzleGET($url, $headers);
	}

	public function GetListOutlets() {
		$url = "https://api.mokapos.com/v1/businesses/".$this->business_id."/outlets";
		$headers = array(
			"Authorization:Bearer ".$this->access_token
		);
		$moka_response = curlGET($url, $headers);
		$result = array();
		if (isset($moka_response['data']['outlets'])) {
			$result = $moka_response['data']['outlets'];
		}
		return $result;
	}

	public function GetCategoriesByOutletID($outletID) {
		$url = "https://api.mokapos.com/v1/outlets/".$outletID."/categories";
		$headers = array(
			"Authorization:Bearer ".$this->access_token
		);
		$moka_response = curlGET($url, $headers);
		$result = array();
		if (isset($moka_response['data']['category'])) {
			$result = $moka_response['data']['category'];
		}
		return $result;
	}

	public function GetSalesTypeByOutletID($outletID) {
		$url = "https://api.mokapos.com/v1/outlets/".$outletID."/sales_type";
		$headers = [
			'Authorization' => 'Bearer '.$this->access_token,
			'Accept' 		=> 'application/json'
		];
		$moka_response = guzzleGET($url, $headers);
		if (isset($moka_response['data']['results'])) {
			$result = $moka_response['data']['results'];
		}
		return $result;
	}

	public function GetItemsByOutletID($outletID) {
		$url = "https://api.mokapos.com/v1/outlets/".$outletID."/items";
		$headers = [
		    'Authorization' => 'Bearer ' . $this->access_token,        
		    'Accept'        => 'application/json',
		];
		$moka_response = guzzleGET($url, $headers);
		$result = array();
		if (isset($moka_response['data']['items'])) {
			$result = $moka_response['data']['items'];
		}
		return $result;
	}

	public function GetLatestTransactionsByOutletID($outletID, $param=array()) {
		$today_date = date('Y-m-d');
		$since = strtotime($today_date);
		$until = strtotime(addDays($today_date, 1));
		
		foreach ($param as $key => $value) {
			$param[$key] = strtotime($value);
		}


		$queryParameter = http_build_query($param);

		$url = "https://api.mokapos.com/v2/outlets/".$outletID."/reports/get_latest_transactions?".$queryParameter."&reorder_type=descending";
		$headers = [
			'Authorization' => 'Bearer '. $this->access_token,
			'Accept' 		=> 'application/json'
		];
		$moka_response = guzzleGET($url, $headers);
		$result = array();
		if (isset($moka_response['data']['payments'])) {
			$result = $moka_response['data']['payments'];
			$completed = $moka_response['data']['completed'];
			
			while (!$completed) {
				$moka_response = guzzleGET($moka_response['data']['next_url'], $headers);
				if (isset($moka_response['data']['payments'])) {
					array_push($result, $moka_response['data']['payments']);
					$completed = $moka_response['data']['completed'];
				}
			}

		}
		return $result;
	}
}