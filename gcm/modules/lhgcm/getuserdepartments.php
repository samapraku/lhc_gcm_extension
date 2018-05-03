<?php

    $currentUser = erLhcoreClassUser::instance();
    if (!$currentUser->isLogged() && !$currentUser->authenticate($_POST['username'], $_POST['password'])) {
        echo json_encode(array("error" => "true"));
        exit();
    }


    $user_depids =array();

    $user_dep_params = json_decode($_POST['user_depids'],true);

    if(isset($user_dep_params) && (int)$user_dep_params['all_departments'] == 1)
{
 echo  json_encode(array("error"=>"false","departments"=>erLhcoreClassDepartament::getDepartaments()));
    exit;
    
}
else
{
    $user_dep_ids = array();
   $user_dep_ids = $user_dep_params['dep_ids']; 
   
   $dep_results = getUserDepartaments($user_dep_ids);

   echo json_encode(array("error"=>"false","departments"=>$dep_results));
}


  function getUserDepartaments($user_dept_ids)
   {
         $db = ezcDbInstance::get();

         $stmt = $db->prepare('SELECT * FROM lh_departament where lh_departament.id IN (:dep_ids)  ORDER BY id ASC');
         $stmt->bindValue(':dep_ids', implode(',', $user_dept_ids));
         $stmt->execute();
         $rows = $stmt->fetchAll();

         return $rows;
   }

exit;

?>