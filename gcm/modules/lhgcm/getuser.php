<?php 

    $currentUser = erLhcoreClassUser::instance();
    if (!$currentUser->isLogged() && !$currentUser->authenticate($_POST['username'], $_POST['password'])) {
        echo json_encode(array("error" => "true"));
        exit();
    }
    
  //  $userdata=array();
    $userdata = $currentUser->getUserData(true);
    unset($userdata->password);
    echo json_encode(array('error' => false, 'result' =>$userdata) );
    exit;
   ?>