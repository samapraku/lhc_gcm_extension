<?php 

	$currentUser = erLhcoreClassUser::instance();
   
    if (!$currentUser->isLogged() && !$currentUser->authenticate($_POST['username'],$_POST['password']))
    {
        echo json_encode(array("error"=>"true"));
        exit();
    }

use LHCMessenger\Classes\Config;


//Generate installation id
function generateInstallID(){
    return  md5(time() . ":" . mt_rand());  
}



if(isset($_POST["regId"]) )
{
  $config =  Config::getInstance();
  $gcmRegID = $_POST["regId"];
  $fcm_section = 'fcm';
  $fcm_ids = 'fcm_reg_ids';
    $output_result="";

 // check if unique id already exists
  $uniq_id = $config->getSetting("installation","installationid");
  
  if(isset($uniq_id) && $uniq_id !=='')
  {
    $output_result=$uniq_id; 
  }
  else {
        // Generate id
        $srv_id = generateInstallID(); 
        $config->setSetting("installation","installationid",$srv_id);
        $config->save();   
         $output_result = $srv_id;
 }

  $saved_regIDs = array();
  $saved_regIDs = $config->getSetting($fcm_section,$fcm_ids);
  
  //getUserData
  $user_data = $currentUser->getUserData(false);
  //echo $user_data->id;
  if(isset($saved_regIDs["$user_data->id"])){
      $arr_ids=$saved_regIDs["$user_data->id"];
      
     if(isset($_POST["action"]) && $_POST["action"] =="add")
    {  
      // if action is new 
    if(!in_array($gcmRegID, $arr_ids) )
    {
      $newArr = array_merge($arr_ids,array($gcmRegID));
       $saved_regIDs["$user_data->id"]=$newArr;
         $config->setSetting($fcm_section, $fcm_ids,$saved_regIDs);
      $config->save();
     
    }
    }
    
    // if action is logout
        // if action is logout
    if(isset($_POST["action"]) && $_POST["action"] =="logout")
    {
  $key = array_search($gcmRegID,  $arr_ids);
   unset($arr_ids[$key]);
     
       $saved_regIDs["$user_data->id"]=$arr_ids;
         $config->setSetting($fcm_section, $fcm_ids,$saved_regIDs);
      $config->save();
      
    echo json_encode(array("error"=>"false"));
    exit;
    }
    
  }
  else {
      $saved_regIDs["$user_data->id"]=array($gcmRegID);
       $config->setSetting($fcm_section, $fcm_ids,$saved_regIDs);
      $config->save();
  }
  
  
$gc_key = $config->getSetting( $fcm_section,"google_api_key");
    $gcmRegIds = array($gcmRegID);

 if(isset($gcmRegID)) 
 //sendPushNotificationToGCM($gc_key,$gcmRegIds);
	
echo json_encode(array("error"=>"false","results"=>$output_result));
exit;




} 
else {
    echo json_encode(array("error"=>"true"));
    exit;
}






	function sendPushNotificationToGCM($fcm_API,$gcm_registered_device) {
	

        $url = 'https://fcm.googleapis.com/fcm/send';

            //'to'=> $gcm_registered_device,
        $fields = array(
            'registration_ids' => $gcm_registered_device,
            'notification'=>array("title"=>"Livehelp Messenger","body"=>"Thank you for using!","priority"=>"high"),
            'data' => array("m" => "Device Registered",)
        );

        $headers = array(
            'Authorization: key=' . $fcm_API,
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



