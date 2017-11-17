<?php 
	
if(!empty($_GET["shareRegId"])) {

	$gcmRegID  = $_POST["regId"]; 

$settings = include "settings.php";

	if(isset($gcmRegID))
{
        $gcm_section = $settings['gcm_reg_id'];

      
	array_push($gcm_section,$gcmRegID);

	$settings['gcm_reg_id']=$gcm_section;

	file_put_contents("settings.php","<?php \n return ".var_export($settings,TRUE)." \n ?>");

	//echo $gcm_section[0];

	//echo $gcm_section[1];

	//echo $settings['gcm_reg_id'][1];

	$gcmRegIds = array($gcmRegID);

	sendPushNotificationToGCM($settings,"registered",$gcmRegIds);


}

}


	function sendPushNotificationToGCM($settings,$message,$gcm_registered_devices) {
	


//Google cloud messaging GCM-API url

        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(

            'registration_ids' => $gcm_registered_devices,

            'data' => array("m" => $message,)

        );

		// Google Cloud Messaging GCM API Key
$google_api =$settings['google_api_key'];

		define("GOOGLE_API_KEY", $google_api); 		

        $headers = array(

            'Authorization: key=' . GOOGLE_API_KEY,

            'Content-Type: application/json'

        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);				

        if ($result === FALSE) {

            die('Curl failed: ' . curl_error($ch));

        }

        curl_close($ch);

        return $result;

    }





?>



