<?php
class ControllerApiFcm extends Controller {
	


	public function addfcm() {
		 $json = array();
		 $this->load->model('api/fcm');
	

        if(!empty($this->request->post['userId']) && !empty($this->request->post['fcm_key']) ){
        	$userId=$this->request->post['userId'];
        	$fcm_key=$this->request->post['fcm_key'];
        	$count=$this->model_api_fcm->getKeycount($userId);
        	if($count==1){
        		$res=$this->model_api_fcm->updateKey($userId,$fcm_key);
        	}else{


        		$res=$this->model_api_fcm->addKey($userId,$fcm_key);
        	}
       
        }



		$json['status'] = 'success';
		$json['userId'] = $userId;
		$json['fcm_key'] = $fcm_key;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function sendmsgtoall(){
		
	    $this->load->model('api/fcm');
		$res=array();
		$res=$this->model_api_fcm->sendmsg();
	   if(!empty($res)){
	   	 foreach ($res as $key => $value) {
	  	 	# code...
	  	 	$msg="Welcome to https://iavocado.in/";
	  	 	$result=$this->sendGCM($msg,$value['fcm_key']);
	  	 	
	   	 }
   		 $json['status'] = 'success';
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	   }
	}


	public function sendGCM($message, $deviceToken) {
		$key="AAAAv364Pdg:APA91bHbv_z_tCmlXHOpy1k4TpCLuIaU8EJkONbDpJR7BOWH6bkrCaPNqwCIxk5r5ep6u1LbBOpO4RmXYyrWAuJATWgFonwQuJNhuZMjJTlb3h6R4zJy6hN4jjioOA_HtIl4sqxCTqvK";
 		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

       $notification = [
            'title' =>'Welcome iavocado',
            'body' => 'Well Done,Signin up with iavocado and develop by Roshan Singh',
            'icon' =>'https://iavocado.in/image/catalog/iAVOCADO_Logo.png', 
            'sound' => 'mySound'
        ];
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $deviceToken, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        echo $result;

	    
	}

}