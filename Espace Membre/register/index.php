<?php


require "/inc/funtcion.php";
session_start();
//if user is already connected he is redirected to main page
//You can modify this value ("/" to "/mypage" for exemple)
if(iflog()){
	degage("/");
}

require "/inc/data.php";

if(!empty($_POST)){

$errors = array();

//

if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	$errors['mail'] = "Mail invalide";
}else{
	$req = $pdo->prepare("SELECT id FROM members WHERE mail = ?");
	$req->execute([$_POST['email']]);
	$users = $req->fetch();
	if($users){
		$errors['already_mail'] = "Mail déjà utiliser";
	}
}
if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])){
	$errors['username'] = "Votre pseudo ne doit pas contenir de caractère spéciaux";
}else{
	$req = $pdo->prepare("SELECT id FROM members WHERE username = ?");
	$req->execute([$_POST['username']]);
	$users = $req->fetch();
	if($users){
		$errors['already_username'] = "Pseudo déjà utiliser";
	}
}
if(empty($_POST['password'])){
	$errors['pass_empty'] = "Veuillez entrer un mot de passe";
}
if(!empty($_POST['password']) && !empty($_POST['password_confirm'])){
	if($_POST['password'] != $_POST['password_confirm']){
		$errors['pass_corespond'] = "Les deux mots de passes ne correspondent pas.";
	}
}

//Insert in database

if(empty($errors)){

$mail = htmlspecialchars($_POST['email']);
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);
$confirmation_code = createConfirmation_code();

$password = password_hash($password, PASSWORD_BCRYPT);


$register = $pdo->prepare("INSERT INTO members SET mail = ?, username = ?, password = ?, confirmation_code = ?");
$register->execute([$mail, $username, $password, $confirmation_code]);

//You can use this code with all variables.

$account = array('mail' => $mail, 'username' => $username);
$_SESSION['auth'] = $account;

$_SESSION['flash']->message_register = "Bienvenue parmis nous";
//this message be use where you want with call of SESSION Variable
sendMail($mail, $confirmation_code);
header("Location: /account");
exit();
//redirection to /account but you can modify that with your own access
}

function sendMail($mail, $token){
//Create Mail header and content
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){

    $new_line = "\r\n";

}else{

    $new_line = "\n";

}

$message_txt = "Hello, Your confirmation token is";

$message_html = "<html><head></head><body><a href=\" https://www.mywebsite.com/confirm/index.php?token=\"" . $token . ">" . $token . "</a></body></html>";


$boundary = "-----=".md5(rand());


$sujet = "Your confirmation code to www.mywebsite.com";


$header = "From: \"WeaponsB\"<weaponsb@mail.fr>".$new_line;

$header.= "Reply-to: \"WeaponsB\" <weaponsb@mail.fr>".$new_line;

$header.= "MIME-Version: 1.0".$new_line;

$header.= "Content-Type: multipart/alternative;".$new_line." boundary=\"$boundary\"".$new_line;


$message = $new_line."--".$boundary.$new_line;



$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$new_line;

$message.= "Content-Transfer-Encoding: 8bit".$new_line;

$message.= $new_line.$message_txt.$new_line;


$message.= $new_line."--".$boundary.$new_line;


$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$new_line;

$message.= "Content-Transfer-Encoding: 8bit".$new_line;

$message.= $new_line.$message_html.$new_line;


$message.= $new_line."--".$boundary."--".$new_line;

$message.= $new_line."--".$boundary."--".$new_line;

//Send mail

mail($mail,$sujet,$message,$header);

}


function getErrors(){
	return $errors;
}


}
function createConfirmation_code(){
	return str_random(60);
}

?>