<?php
$DisplayName = $_REQUEST["DisplayName"];
$Email = $_REQUEST["Email"];
$Password = $_REQUEST["Password"];

//This is not the actual database access information. Publishing that on GitHub wouldn't be a good idea.
$Hostname = "localhost";
$DBName = "databaseName";
$User = "databaseNameUserName";
$PasswordP = "databaseNameUserNamePassword";
$SecretColumn = "secretColumn";

mysql_connect($Hostname, $User, $PasswordP) or die ("Can't connect to DB");
mysql_select_db($DBName) or die("Can't Connect to DB");

if(!$Email || !$Password){
	echo "Empty";
}else{
	$SQL = "SELECT * FROM `engine4_users` WHERE email = '".$Email."'";
	$Result = @mysql_query($SQL) or die ("DB Error");
	$Total = mysql_num_rows($Result);
	if($Total == 0){
		$SQL2 = "SELECT value FROM `engine4_core_settings` WHERE name = '".$SecretColumn."' LIMIT 1";
		$Secret = mysql_result(mysql_query($SQL2),0) or die ("Database Error!");
		$Salt = (string) rand(1000000, 9999999);
		$insert = "INSERT INTO `engine4_users` (`displayname`, `email`, `password`, `salt`) VALUES ('".$DisplayName."', '".$Email."', MD5('".$Secret.$Password.$Salt."'), '".$Salt."')";
		$SQL1 = mysql_query($insert);
		echo "Success";
	}else{
		echo "AlreadyUsed";
	}
}

mysql_close();
?>







