<div class="widget">
    <h2>Hello, <?php echo $user_data['first_name']; ?>!</h2>
    <div class="inner">
        <div class="profile">
            <?php
            if(isset($_FILES['profile']) === TRUE){
                if(empty($_FILES['profile']['name']) === TRUE){
                    echo 'Please choose a file';
                }else{
                    $allowed = array('jpg', 'jpeg', 'gif', 'png');
                    
                    $file_name = $_FILES['profile']['name'];
                    $file_extn = strtolower(end(explode('.', $file_name)));
                    $file_temp = $_FILES['profile']['tmp_name'];

                    $size = $_FILES['profile']['size'];
                    $max_size = 2097152;
                    // $max_size = 10000;


                    if(in_array($file_extn, $allowed) === TRUE){
                        if($size>=$max_size){
                            echo 'File must be 2MB or less.';
                        }else{
                            //uploading the file 
                            if(change_profile_image($session_user_id, $file_temp, $file_extn) == TRUE){
                                header('Location: ' . $current_file);
                                exit();
                            }else{
                                echo 'We could not upload your profile picture. Please try again later or contact Administrator'; 
                            }
                        }
                    }else{
                        echo 'Incorrect file type. Allowed types are: '; 
                        echo implode(', ', $allowed);
                    }
                }
            }
            if(empty($user_data['profile']) === FALSE){
                echo '<img src="', $user_data['profile'], '" alt="', $user_data['first_name'] , '\'s profile image">';
            }           
            ?>
            <form action="" method="post" enctype="multipart/form-data"> 
                <input type="file" name="profile">
                <input type="submit">
            </form>            
        </div>
        <ul>
            <li>
                <a href="logout.php">Log out</a>
            </li>
            <li>
                <a href="<?php echo($user_data['username']); ?>">Profile</a>
            </li>
            <li>
                <a href="changepassword.php">Change password</a>
            </li>
            <li>
                <a href="settings.php">Settings</a>
            </li>
        </ul>
    </div>
</div>