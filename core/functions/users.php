<?php
function change_profile_image($user_id, $file_temp, $file_extn){
	global $mysql_connect;
	$file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
	if(move_uploaded_file($file_temp, $file_path) == true){
		if(mysqli_query($mysql_connect, "UPDATE `users` SET `profile` = '" . mysqli_real_escape_string($mysql_connect, $file_path) . "' WHERE `user_id` = " . (int)$user_id)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
function mail_users($subject, $body){
	global $mysql_connect;
	$query = mysqli_query($mysql_connect, "SELECT `email`, `first_name` FROM `users` WHERE `allow_email` = 1");
    while($row = mysqli_fetch_array($query)){
    	email($row['email'], $subject, "Hello " . $row['first_name'] . ",\n\n" . $body);
    }
}
function has_access($user_id, $type){
	global $mysql_connect;
	$user_id = (int)$user_id;
    $type = (int)$type;

    $result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `user_id` = '$user_id' AND `type` = $type");

    if(mysqli_num_rows($result)==0){
		return false;
	}else if(mysqli_num_rows($result)==1){
		return true;
	}
}
function recover($mode, $email){
	$mode = sanitize($mode);
	$email = sanitize($email);

	$user_data = user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');
    
    if($mode == 'username'){
    	email($email, 'Your username', "Hello " . $user_data['first_name'] . ",\n\nYour username is: " . $user_data['username'] . "\n\n-register and login system-");
    	return true;
    }else if($mode == 'password'){
    	$generated_password = substr(md5(rand(999, 999999)), 0, 8);
    	change_password($user_data['user_id'], $generated_password);

    	if(update_user($user_data['user_id'], array('password_recover' => '1')) == TRUE){
    		email($email, 'Your password recovery', "Hello " . $user_data['first_name'] . ",\n\nYour new password is: " . $generated_password . "\n\n-register and login system-");
    		return true;
    	}else{
    		return false;
    	}
    }   
}
function update_user($user_id, $update_data){
	global $mysql_connect;
	$update = array();
    array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	
	if(mysqli_query($mysql_connect, "UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = $user_id")){
		return true;
	}else{
		return false;
	}
}
function activate($email, $email_code){
	global $mysql_connect;
	$email = mysqli_real_escape_string($mysql_connect, $email);
	$email_code = mysqli_real_escape_string($mysql_connect, $email_code);

	$result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0");

	if(mysqli_num_rows($result)==1){
		mysqli_query($mysql_connect, "UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
		return true;
	}else{
		return false;
	}
}
function change_password($user_id, $password){
	global $mysql_connect;
	$user_id = (int)$user_id;
    $password = md5($password);

    mysqli_query($mysql_connect, "UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
}
function register_user($register_data){
	global $mysql_connect;
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']);

	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
    $data  =  '\'' . implode('\', \'', $register_data) . '\'';
    
    mysqli_query($mysql_connect, "INSERT INTO `users` ($fields) VALUES ($data)");
    email($register_data['email'], 'Activate your account', "Hello " . $register_data['first_name'] . ",\n\nYou need to activate your account, so use the link below:\n\nhttp://localhost:8080/lr/activate.php?email=" . $register_data['email'] . "&email_code=" . $register_data['email_code'] ."\n\n\n\n\n-register and login system-");
}
function user_count(){
	global $mysql_connect;
	$result = mysqli_query($mysql_connect, "SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1");
	$count_array = mysqli_fetch_array($result);
	return $count_array[COUNT(`user_id`)];
    
}
function user_data($user_id){
	global $mysql_connect;
	$data =  array();
	$user_id = (int)$user_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_num_args > 1){
		unset($func_get_args[0]);

		$fields = '`' . implode('`, `', $func_get_args) . '`';
		$result = mysqli_query($mysql_connect, "SELECT $fields FROM `users` WHERE `user_id` = $user_id");
		$data = mysqli_fetch_assoc($result);
		return $data;
	}
}
function logged_in(){
	return (isset($_SESSION['user_id'])) ?  TRUE : FALSE;
}
function user_exists($username){
	global $mysql_connect;
    $username = sanitize($username);

    $result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `username` = '$username'");

    if(mysqli_num_rows($result)==0){
		return false;
	}else if(mysqli_num_rows($result)>=1){
		return true;
	}
}
function email_exists($email){
	global $mysql_connect;
    $email = sanitize($email);

    $result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `email` = '$email'");

    if(mysqli_num_rows($result)==0){
		return false;
	}else if(mysqli_num_rows($result)>=1){
		return true;
	}
}
function user_active($username){
	global $mysql_connect;
    $username = sanitize($username);

    $result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `username` = '$username' AND `active` = 1");

    if(mysqli_num_rows($result)==0){
		return false;
	}else if(mysqli_num_rows($result)==1){
		return true;
	}
}
function user_id_from_username($username){
	global $mysql_connect;
	$username = sanitize($username);

	$result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `username` = '$username'");
	$row = mysqli_fetch_array($result);
	$user_id = $row['user_id'];
    return $user_id;
}
function user_id_from_email($email){
	global $mysql_connect;
	$email = sanitize($email);

	$result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `email` = '$email'");
	$row = mysqli_fetch_array($result);
	$user_id = $row['user_id'];
    return $user_id;
}
function login($username, $password){
	global $mysql_connect;
	$user_id = user_id_from_username($username);

	$username = sanitize($username);
	$password = md5($password);

	$result = mysqli_query($mysql_connect, "SELECT `user_id` FROM `users` WHERE `username` = '$username' AND `password` = '$password'");

    if(mysqli_num_rows($result)==0){
		return false;
	}else if(mysqli_num_rows($result)==1){
		return $user_id;
	}
}
?>