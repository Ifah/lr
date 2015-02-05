<?php 
include 'core/init.php'; 
protect_page();
admin_protect();
include 'includes/overall/header.php';
?>
<h1>Email all users</h1>
<?php
if(isset($_GET['success']) === TRUE && empty($_GET['success']) === TRUE){
?>
<p>Email has been sent</p>
<?php    
}else{
	if(empty($_POST) === FALSE){
        if(empty($_POST['subject']) === TRUE){
            $errors[] = "Subject is required";        
        }
        if(empty($_POST['body']) === TRUE){
            $errors[] = "Body is required";   
        }
        if(empty($errors) === FALSE){
            echo output_errors($errors);        
        }else{
        	mail_users($_POST['subject'], $_POST['body']);
        	// header('Location: mail.php?success');
        	// exit();
        }    
    }
    
    ?>

    <form action="" method="post">
    	<ul>
            <li>
                Subject*:<br>
                <input type="text" name="subject" value="<?php if(isset($_POST['subject'])) echo $_POST['subject']; ?>">
            </li>
            <li>
                Body*:<br>
                <textarea name="body"><?php if(isset($_POST['body'])) echo $_POST['body']; ?></textarea>
            </li>
            <li>
                <input type="submit" value="Send">
            </li>
        </ul>
    </form>
<?php 
}
include 'includes/overall/footer.php';
?>