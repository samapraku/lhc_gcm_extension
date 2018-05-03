<?php 

    $currentUser = erLhcoreClassUser::instance();
    if (!$currentUser->isLogged() && !$currentUser->authenticate($_POST['username'], $_POST['password'])) {
        echo json_encode(array("error" => "true"));
        exit();
    }
    
// borrowed from lhchat/operatortyping.php
if (is_numeric((int)$_POST['chat_id']))
{
    $chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', (int)$_POST['chat_id']);

    if ( erLhcoreClassChat::hasAccessToRead($chat) )
    {    	
    	// Rewritten in a more efficient way
    	$db = ezcDbInstance::get();
    	$stmt = $db->prepare('UPDATE lh_chat SET operator_typing = :operator_typing, operator_typing_id = :operator_typing_id WHERE id = :id');
    	$stmt->bindValue(':id',$chat->id,PDO::PARAM_INT);
    			
        if ( $_POST['status'] == 'true' ) {
        	$stmt->bindValue(':operator_typing',time(),PDO::PARAM_INT);
        	$stmt->bindValue(':operator_typing_id',$currentUser->getUserID(),PDO::PARAM_INT); 
        } else {
        	$stmt->bindValue(':operator_typing',0,PDO::PARAM_INT);
        	$stmt->bindValue(':operator_typing_id',0,PDO::PARAM_INT);  
        }
        
        $stmt->execute();             
    }
}

exit;

?>