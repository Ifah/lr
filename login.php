<?php
include 'core/init.php';
logged_in_redirect();
if(empty($_POST) === FALSE){
	$username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) === TRUE || empty($password) === TRUE){
    	$errors[] = 'You need to enter a username and password';
    }else if(strlen($password) > 32){
    	$errors[] = "Password too long";
    }else if(strlen($username) > 32){
    	$errors[] = "Username too long";
    }else if(user_exists($username) === FALSE){
    	$errors[] = "We can't find that username. Have you registered?";
    }else if(user_active($username) === FALSE){
    	$errors[] = "You haven't activated your account!";
    }else{
    	$login = login($username, $password);
    	if($login === FALSE){
    		$errors[] = "Username or password is incorrect";
    	}else{
    		$_SESSION['user_id'] = $login;
    		header('Location: index.php');
    		exit();
    	}  
    }
}else{
	header('Location: index.php');
}
include 'includes/overall/header.php';
if(empty($errors) === FALSE){
?>
	<h2> We tried to log you in, but...</h2>
<?php
echo output_errors($errors);
}
include 'includes/overall/footer.php';
?>