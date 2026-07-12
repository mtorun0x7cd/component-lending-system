<?PHP
	session_start();
	
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
	{
		header("location: home.php");
		exit;
	}
	
	require_once "config.php";
	
	/* Variables */
	$campusid 		= trim($_POST["campusid"]);
	$password 		= trim($_POST["passwort"]);
	$err 			= "";
	/* /Variables */
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		/* einloggen.php */
		if (empty($campusid) || empty($password))
		{
			$err = "ERROR: Campus ID oder Passwort ist leer!";
		}
		else 
		{
			$query = "SELECT id, passwort FROM benutzer WHERE campusid = '$campusid'";
			
			if ($result = mysqli_query($connection, $query))
			{
				$isFound = false;
				while ($row = mysqli_fetch_row($result))
				{
					$id = $row[0];
					$storedPassword = $row[1];
					$isFound = true;
					break;
				}
				
				if ($isFound)
				{
					if (password_verify($password, $storedPassword))
					{
						session_start();
						
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["username"] = $campusid;
						
						header("location: home.php");
						exit;
					}
					else 
					{
						echo "ERROR: Konnte Passwoerter nicht verifizieren!";
					}
				}
				else 
				{
					$err = "ERROR: Konnte die SQL-Abfrage nicht ausfuehren!";
				}
			}
			else 
			{
				$err = "ERROR: Konnte die SQL-Abfrage nicht ausfuehren!";
			}
		}
		/* /einloggen.php */
	}
?>