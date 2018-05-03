<?php 

// Register Wurrd classes
$lhcmExtRoot = dirname(dirname(__FILE__));

$loader = require_once($lhcmExtRoot . '/vendor/autoload.php');
$loader->addPsr4('LHCMessenger\\Classes\\', $lhcmExtRoot . '/modules/lhgcm/Classes/', true);

use LHCMessenger\Classes\Config;

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

	}


	
  public function sendPushNotificationToGCM($title,$chat,$chat_type,$msg) {
       
        $config =  Config::getInstance();
	      
	//	$settings =include 'extension/gcm/settings/settings.ini.php';	
		
      	//Google cloud messaging GCM-API url
        $url = 'https://fcm.googleapis.com/fcm/send';
	
        $registered_ids = $config->getSetting("fcm","fcm_reg_ids");
        
        $allowed_ids=array();
          foreach ($registered_ids as $key => $value) {
              //getuser
              $user = erLhcoreClassModelUser::fetch($key); 
              	$currentUser = erLhcoreClassUser::instance();
              	$currentUser->setLoggedUser($user->id);
            //  $chatInstance = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', $chat->id);
              if ( erLhcoreClassChat::hasAccessToRead($chat) )
            {
             $allowed_ids= array_merge($allowed_ids,$value); 
             }
             
              $currentUser->logout();
              
        }
        
        $installation_id=$config->getSetting("installation","installationid");
        
        $google_api = $config->getSetting('fcm','google_api_key');

		define("GOOGLE_API_KEY",$google_api);	
	

		
	$fields = array(
            'registration_ids' => $allowed_ids,
            'notification'=>array("title"=>$title,"sound"=>"default","body"=>$chat->nick.': '.$msg,"priority"=>"high") 
        );
        
		 /* 'data' => array("server_id"=>$installation_id,"m" => $title,"chat"=> json_encode($chat),"chat_type"=>$chat_type,"msg"=>$msg),	 ,
           'notification'=>array("title"=>$title,"sound"=>"default","body"=>$chat->nick.': '.$msg,"priority"=>"high")  */

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
    $this->sendPushNotificationToGCM("New Message",$chat,"new_msg",$msg->msg);
    }

    public function newChat($params)
    {
    $chat = $params['chat'];
    $msg = $params['msg'];
    $this->sendPushNotificationToGCM("NEW CHAT",$chat,"pending",$msg->msg);
    }

    public function unreadMessage($params)
    {
    $chat = $params['chat'];
    $this->sendPushNotificationToGCM("Unread Message",$chat,"unread","");
    }

}
