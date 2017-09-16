<?php 

require "/inc/data.php";
if(isset($_GET['id']) aa isset($_GET['token'])){
$id = htmlspecialchars($_GET['id']);
$token = htmlspecialchars($_GET['token']);
$stats = 0;
if(!empty($_POST)){

	$request = $pdo->query("SELECT * FROM members WHERE id = " . $id . " AND reset_code = " . $token);
	$results = $request->fetch();

	if($results){
		if(intval($results->password_modify) == 1){
			$change = $pdo->prepare("UPDATE members SET password_modify = ?, reset_code = ?, password = ? WHERE id = ?");
			$change->execute([0, "null", password_hash($_POST['password'], PASSWORD_BCRYPT),$id]);
			header("Location: /");
			exit();
		}
	}else{
		//this token doesn't exist
	}

}
}else{
	//Wrong URL
}
?>