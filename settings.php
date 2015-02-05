<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';

if(empty($_POST) === FALSE){
    $required_fields =  array('first_name', 'email');
    foreach($_POST as $key=>$value){
        if(empty($value) && in_array($key, $required_fields) === TRUE){
            $errors[] = "Fields with * are required";
            break 1;
        }
    }

    if(strlen($_POST['first_name']) > 32){
        $errors[] = "First name cannot be greater than 32 characters";
    }
    if(strlen($_POST['last_name']) > 32){
        $errors[] = "Last name cannot be greater than 32 characters";
    }
    if(strlen($_POST['email']) > 1024){
        $errors[] = "Email cannot be greater than 1024 characters";
    }
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === FALSE){
        $errors[] = "A valid email adress is required";
    }else if(email_exists($_POST['email']) === TRUE && $user_data['email'] !== $_POST['email']){
            $errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use.';
    }
}

?>
<h1>Settings</h1>
<?php
if(isset($_GET['success']) === TRUE && empty($_GET['success']) === TRUE){
    echo 'Your details has been updated!';
}else{
    if(empty($_POST) === FALSE && empty($errors) === TRUE){
        $update_data =  array(
            'first_name'    => $_POST['first_name'],
            'last_name'     => $_POST['last_name'],
            'email'         => $_POST['email'],
            'allow_email'   => ($_POST['allow_email'] == 'on') ? 1 : 0
        );

        if(update_user($session_user_id, $update_data) === TRUE){
            header('Location: settings.php?success');
            exit();
        }else{
            $errors[] = "We could not update your information. Please try again later or contact Administrator";
        }

    }else if(empty($errors) === FALSE){
        echo output_errors($errors);
    }
    ?>
    <form action="" method="post">
        <ul>
            <li>
                First name*:<br>
                <input type="text" name="first_name" value="<?php echo($user_data['first_name']); ?>" maxlength="32">
            </li>
            <li>
                Last name:<br>
                <input type="text" name="last_name" value="<?php echo($user_data['last_name']); ?>" maxlength="32">
            </li>
            <li>
                Email*:<br>
                <input type="text" name="email" value="<?php echo($user_data['email']); ?>" maxlength="1024">
            </li>
            <li>
                <input type="checkbox" name="allow_email" <?php if ($user_data['allow_email'] == 1) {echo 'checked="checked"';} ?>>
                Would you like tp receive our newsletter?
            </li>
            <li>
                <input type="submit" value="Update">
            </li>
        </ul>
    </form>
<?php
}
include 'includes/overall/footer.php';
?>