<?PHP
	// Author: Mert Torun (mtorun0x7cd)
	define('DB_SERVER', '<db-host>');
	define('DB_USERNAME', '<db-user>');
	define('DB_PASSWORD', '<db-password>');
	define('DB_NAME', 'bauteilverwaltung');
	
	$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	
	if ($connection == false)
	{
		die("ERROR: Could not connect to local sql database. " . mysqli_connect_error());
	}
?>