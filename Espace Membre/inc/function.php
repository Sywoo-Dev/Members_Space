<?php 

function iflog(){

	if(!isset($_SESSION['auth'])){
		return false;
	}else{
		return true;
	}

}

function degage($to){

	header("Location: " . $to);
	exit();

}

function displayMessage(){
	//you can display message you want with method you want
	unset($_SESSION['flash']);
}




?>