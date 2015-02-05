<?php 
include 'core/init.php'; 
logged_in_redirect();
include 'includes/overall/header.php';
?>
<h1>Recover</h1>
<?php
if(isset($_GET['success']) === TRUE && empty($_GET['success']) === TRUE){
?>
    <p>Thanks, w've sent the username to your email address!</p>
    
<?php
}else{
    $mode_allowed = array('username', 'password');
    if(isset($_GET['mode']) === TRUE && in_array($_GET['mode'], $mode_allowed) === TRUE){
        if(isset($_POST['email']) === TRUE && empty($_POST['email']) === FALSE){
            if(email_exists($_POST['email']) === TRUE){
                if(recover($_GET['mode'], $_POST['email']) == TRUE){
                // header('location: recover.php?success');
                // exit();
                }else{
                    echo '<p>We could not process your request. Please try again later or contact Administrator</p>';
                }
            }else{
                echo '<p>Oops, we couldn\'t find that email adress</p>';
            }
        }
    ?>
    <form action="" method="post">
        <ul>
            <li>
                Please enter your email adress:<br>
                <input type="text" name="email">
            </li>
            <li>
                <input type="submit" value="Recover">
            </li>
        </ul>
    </form>
    <?php   
    }else{
        header('Location: index.php');
        exit();
    }
}
?>
<?php include 'includes/overall/footer.php'; ?>