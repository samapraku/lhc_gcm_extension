<?php 
$currentUser = erLhcoreClassUser::instance();

if (!$currentUser->isLogged() && !$currentUser->authenticate($_POST['username'], $_POST['password'])) {
    echo json_encode(array("error" => "true"));
    exit();
}


/**
 * RECEIVE DEPARTMENT WORKHOURS AS JSON
 * STORE IT IN 
 * ITERATE AND VALIDATE EACH ONE, validating will save it
 * 
 * json format 
 * 
 */

 
function getOnlineHours($dept)
{
    return array(
        'id' => $dept->id,
        'online_hours_active' => $dept->online_hours_active,
        'name' => $dept->name,
        'email' => $dept->email,
        'mod_start_hour' => $dept->mod_start_hour,
        'mod_end_hour' => $dept->mod_end_hour,
        'tud_start_hour' => $dept->tud_start_hour,
        'tud_end_hour' => $dept->tud_end_hour,
        'wed_start_hour' => $dept->wed_start_hour,
        'wed_end_hour' => $dept->wed_end_hour,
        'thd_start_hour' => $dept->thd_start_hour,
        'thd_end_hour' => $dept->thd_end_hour,
        'frd_start_hour' => $dept->frd_start_hour,
        'frd_end_hour' => $dept->frd_end_hour,
        'sad_start_hour' => $dept->sad_start_hour,
        'sad_end_hour' => $dept->sad_end_hour,
        'sud_start_hour' => $dept->sud_start_hour,
        'sud_end_hour' => $dept->sud_end_hour,
    );
}

//Get Department instance 

if (isset($_POST['work_hours'])) {
    $deptWorkHours = array();
    $deptWorkHours = json_decode($_POST['work_hours'], true);
    $deptid = (int)$deptWorkHours['id'];

    try {
        $Department = erLhcoreClassDepartament::getSession()->load('erLhcoreClassModelDepartament', $deptid );

        foreach ($deptWorkHours as $key => $value) {
			if(key !="id")
            $Department->$key = $value;
        }

        erLhcoreClassDepartament::getSession()->update($Department);

        $Department = erLhcoreClassDepartament::getSession()->load('erLhcoreClassModelDepartament', $deptid);
        echo json_encode(array("error" => "false", "onlinehours" => getOnlineHours($Department)));

    } catch (Exception $ex) {
        echo json_encode(array("error" => "true", "msg" => "Work hours could not be saved"));
        new Exception("Could not save the department data");
    }

}




exit;
?>