<?php 

if(isset($_SESSION['auth'])){
    header('Location: account.php');
    exit();
}

if(!empty($_POST)){

	$req = $pdo->prepare('SELECT * FROM members WHERE username = ? OR mail = ?');
    $req->execute([htmlspecialchars($_POST['user'])]);
    $user = $req->fetch();
    if(password_verify($_POST['password'], $user->password)){
    	$_SESSION['auth'] = $user;
    	header("Location: /");
    	exit();
    }else{
    	//Wrong creditentials
    }


}

?>