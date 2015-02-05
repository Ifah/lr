<?php
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';

if(isset($_GET['success']) === TRUE && empty($_GET['success']) === TRUE){
?>
<h2>Thanks, we've activated your account...</h2>
<p>You're free to log in </p>

<?php
}else if(isset($_GET['email'], $_GET['email_code']) === TRUE){
	$email = trim($_GET['email']);
	$email_code = trim($_GET['email_code']);

	if (email_exists($email) === FALSE){
		$errors[] = "Oops, something went wrong, and we couldn't find that email adress!";
	}else if(activate($email, $email_code) === FALSE){
		$errors[] = "We had problems activating your account";
	}

	if(empty($errors) === FALSE){
    ?>
    <h2>Oops...</h2>       
   <?php
   	echo output_errors($errors); 
    }else{
    	header('location: activate.php?success');
    	exit();
    } 
}else{
	header('Location: index.php');
}


include 'includes/overall/footer.php';
?>