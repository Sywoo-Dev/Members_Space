<?php 

require "/inc/data.php";
require "/inc/function.php";

if(iflog()){
	degage("/");
}

if(!empty($_POST)){

	$email = htmlspecialchars($_POST['email']);
	$forgot = create_forgot_code();
	$request = $pdo->query("SELECT * FROM members WHERE mail = " . $mail);
	$result = $request->fetch();

	if(!empty($result)){
		//Show Error Account with this email doesn't exist
	}else{
		sendMail(htmlspecialchars($mail), $forgot);
		//in mail send URL with ID and Token
		$set_stats = $pdo->query("UPDATE members SET password_modify = 1, reset_code = " . $code);
		$_SESSION['flash']->message = 'Un mail vous a été envoyé';
		header("Location: /");
		exit();
	}

}

function create_forgot_code(){
$code = "";
$i = 0;
while ($i < 30) {
	$code += rand(0, 9);
	$i++;
}
return $code;
}

function sendMail($to, $code){
//send mail with code to reset password
}

?>