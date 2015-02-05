<?php
function email($to, $subject, $body){
	// mail($to, $subject, $body, 'from: root@localhost.com');
	echo $to, ' ', $subject, ' ', $body;
}
function logged_in_redirect(){
	if(logged_in() === TRUE){
		header('Location: index.php');
		exit();
	}
}
function protect_page(){
	if(logged_in() === FALSE){
		header('Location: protected.php');
		exit();
	}
}
function admin_protect(){
	global $user_data;
	if(has_access($user_data['user_id'], 1) === FALSE){
		header('Location: index.php');
		exit();
	}
}
function array_sanitize(&$item){
	global $mysql_connect;
	$item = htmlentities(strip_tags(mysqli_real_escape_string($mysql_connect, $item)));
}
function sanitize($data){
	global $mysql_connect;
	return htmlentities(strip_tags(mysqli_real_escape_string($mysql_connect, $data)));
}
function output_errors($errors){
	return '<ul><li>' . implode('</li><li>', $errors) .  '</li></ul>';
}
?>