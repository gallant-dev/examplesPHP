<?php
$Email = $_REQUEST["Email"];
$Password = $_REQUEST["Password"];

//Not actual database information. Publishing that on GitHub would be a bad idea.
$Hostname = "localhost";
$DBName = "dataBaseName";
$User = "dataBaseUserName";
$PasswordP = "dataBaseUserPass";
$SecretColumn = "secretColumn";

mysql_connect($Hostname, $User, $PasswordP) or die ("Can't connect to DB");
mysql_select_db($DBName) or die("Can't Connect to DB");

if(!$Email || !$Password){
	echo "Email and password cannot be empty!";
}else{
	$SQL = "SELECT * FROM `engine4_users` WHERE email = '".$Email."'";
	$Result = @mysql_query($SQL) or die ("DB Error");
	$Total = mysql_num_rows($Result);
	$UserSalt = mysql_result(mysql_query("SELECT salt FROM `engine4_users` WHERE email = '".$Email."' LIMIT 1"),0) or die ("Database Error!");
	$Secret = mysql_result(mysql_query("SELECT value FROM `engine4_core_settings` WHERE name = '".$SecretColumn."' LIMIT 1"),0) or die ("Database Error!");
	
	$Password2 = MD5($Secret.$Password.$UserSalt);
	if($Total >0){
		$Password_Enc = mysql_result(mysql_query("SELECT password FROM `engine4_users` WHERE  email = '".$Email."' LIMIT 1"),0) or die ("Database Error!");
		if($Password2 == $Password_Enc){
			$DisplayName = mysql_result(mysql_query("SELECT displayname FROM `engine4_users` WHERE email = '".$Email."' LIMIT 1"),0) or die ("Database Error!");
			//Currently it is manditory for an account to have a photo assigned.
			$PlayerPhotoID = mysql_result(mysql_query("SELECT `photo_id` FROM `engine4_users` WHERE email = '".$Email."' LIMIT 1"),0) or die ("Database Error!"); 
			// When other games are made will need to determine which game is accessing login.
			$PlayerSkill = mysql_result(mysql_query("SELECT `etherlands_skill` FROM `engine4_users` WHERE email = '".$Email."' LIMIT 1"),0) or die ("Database Error!");
			$PhotoPath = mysql_result(mysql_query("SELECT `storage_path` FROM `engine4_storage_files` WHERE `file_id` = '".$PlayerPhotoID."' LIMIT 1"),0) or die ("Database Error!");
			echo $DisplayName;
			echo ":";
			echo $PhotoPath;
			echo ":";
			echo $PlayerSkill;
			echo "Success";
		}else{
			echo "WrongPassword";	
		}

		
	}
}

mysql_close();
?>







