<?php 

$token = htmlspecialchars($_GET['token']);

require "/inc/data.php";
require "/inc/funtion.php";
session_start();
if(!iflog()){
	degage("/");
	exit();
}

$request = $pdo->query("SELECT * FROM members WHERE confirmation_code = " . $token);
$result = $request->fetch();

if($result){
	if(intval($result->is_validate) == 1){
		//Show Error or redirect to other page
	}else{
		$valid = $pdo->prepare("UPDATE members SET is_validate = ? WHERE confirmation_code = ?");
		$valid->execute([1, $token]);
		//Show succes message or redirect
		$_SESSION['auth']->is_validate = 1;
	}
	}
}

?>