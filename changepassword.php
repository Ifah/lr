<?php 
include 'core/init.php';
protect_page();
 
if(empty($_POST) === FALSE){
    $required_fields = array('current_password', 'password', 'password_again');
    foreach($_POST as $key=>$value){
        if(empty($value) && in_array($key, $required_fields) === TRUE){
            $errors[] = "Fields with * are required";
            break 1;
        }
    }
    if(md5($_POST['current_password']) === $user_data['password']){
        if(trim($_POST['password']) !== trim($_POST['password_again'])){
            $errors[] = "Your new passwords do not match";
        }else if(strlen($_POST['password']) < 6){
            $errors[] = "Your password must be at least 6 characters";
        }
    }else{
        $errors[] = "Your current password is incorrect";
    }    
}

include 'includes/overall/header.php';

?>

<h1>Change password</h1>

<?php
if(isset($_GET['success']) === TRUE && empty($_GET['success']) === TRUE){
    echo 'Your password has been changed!';   
}else{

    if(isset($_GET['force']) === TRUE && empty($_GET['force']) === TRUE){
    ?>
    <p>You must change your password now that you've requested a new password!</p>
    <?php     
    }

    if(empty($_POST) === FALSE && empty($errors) === TRUE){
        change_password($session_user_id, $_POST['password']);
        header('Location: changepassword.php?success');
    }else if(empty($errors) === FALSE){
        echo output_errors($errors);
    }
        
    ?>


    <form action="" method="post">
        <ul>
            <li>
                Current password*:<br>
                <input type="password" name="current_password">
            </li>
            <li>
                New password*:<br>
                <input type="password" name="password">
            </li>
            <li>
                New password again*:<br>
                <input type="password" name="password_again">
            </li>
            <li>
                <input type="submit" value="Change password">
            </li>
        </ul>
    </form>
 


<?php 
}

include 'includes/overall/footer.php'; ?>