<?php 

class erLhcoreClassExtensionGcm {

	public function __construct() {
	}

	public function run(){		

	//	 $this->registerAutoload();

		$dispatcher = erLhcoreClassChatEventDispatcher::getInstance();

		

		// Attatch event listeners

		$dispatcher->listen('chat.close',array($this,'chatClosed'));
		$dispatcher->listen('chat.unread_chat',array($this,'unreadMessage'));	
		$dispatcher->listen('chat.addmsguser', array($this,'newMessage'));		
		$dispatcher->listen('chat.chat_started', array($this,'newChat'));					
	}

	public function chatClosed($params) {

		// 

		// 'chat' => & $chat, 		// Chat which was closed

		// 'user_data' => $operator // Operator who has closed a chat

		// 

		// 

		//

	}


	
  public function sendPushNotificationToGCM($title,$chatid,$chat_type,$nick,$msg) {
		$settings =include 'extension/gcm/settings.php';

      	//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
	
	$registered_ids= $settings['gcm_reg_id'];
        
	$fields = array(
            'registration_ids' => $registered_ids,
            'data' => array("m" => $title,"chat_id"=> $chatid,"chat_type"=>$chat_type,"nick"=>$nick,"msg"=>$msg)
        );
		// Google Cloud Messaging GCM API Key
		//define("GOOGLE_API_KEY", "AIzaSyAeuzXn29YybxGtdSgUAhKl2w9a-HeeIco"); 	 
		$google_api = $settings['google_api_key'];

		define("GOOGLE_API_KEY",$google_api);	

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


   
    public function registerAutoload()
    {
        spl_autoload_register(array($this, 'autoload'), true, false);
    }
    
    
  // the lines below are used to debug
//  $filename = "extension/gcm/newchatparams.txt";
//    file_put_contents($filename,"New Message\n",FILE_APPEND );
//    file_put_contents($filename,json_encode($params),FILE_APPEND );
//    file_put_contents($filename,"\n\n",FILE_APPEND );  
    

    public function newMessage($params)
    {
    $chat = $params['chat'];
    $msg = $params['msg'];
    $this->sendPushNotificationToGCM("New Message",$chat->id,"new_msg",$chat->nick,$msg->msg);
    }

    public function newChat($params)
    {
    $chat = $params['chat'];
    $msg = $params['msg'];
    $this->sendPushNotificationToGCM("NEW CHAT",$chat->id,"pending",$chat->nick,$msg->msg);
    }

    public function unreadMessage($params)
    {
    $chat = $params['chat'];
    $this->sendPushNotificationToGCM("Unread Message",$chat->id,"unread",$chat->nick,"");
    }

}


// >