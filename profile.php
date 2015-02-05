<?php 
include 'core/init.php'; 
include 'includes/overall/header.php';

if(isset($_GET['username']) === TRUE && empty($_GET['username']) === FALSE){
    $username = $_GET['username'];

    if(user_exists($username) === TRUE){
        $user_id = user_id_from_username($username);
        $profile_data = user_data($user_id, 'first_name', 'last_name', "email");
        
        ?>
        
        <h1><?php echo $profile_data['first_name']; ?>'s profile</h1>
        <p>First name: <?php echo $profile_data['first_name']; ?></p>
        <p>Last name: <?php echo $profile_data['last_name']; ?></p>
        <p>Email: <?php echo $profile_data['email']; ?></p>
        
        <?php    
    }else{
        echo 'Sorry, that user doesn\'t exist!';
    }
}else{
    header('Location: index.php');
    exit();
}

include 'includes/overall/footer.php'; 
?>