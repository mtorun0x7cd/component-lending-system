<?PHP
	session_start();

	require_once "config.php";
	
	/* Configure Session Arrays */
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
	{
		if (!isset($_SESSION["selected_teilenummer"]))
		{
			$_SESSION["selected_teilenummer"] = array(); // Contains teilenummer of element
		}
		if (!isset($_SESSION["selected_count"]))
		{
			$_SESSION["selected_count"] = array(); // Contains count of element
		}
	}
	/* /Configure Session Arrays */
	
	/* Variablen */
	$teilenummer 		= array();
	$name 				= array();
	$link 				= array();
	$beschreibung 		= array();
	$stueckzahl 		= array();
	$stueckzahl_v 		= array();
	$foto 				= array();
	$eaglebib 			= array();
	$lager_raum 		= array();
	$lager_schrank 		= array();
	$meldebestand 		= array();
	$rueckgabe_noetig 	= array();
	$i 					= 0;
	
	$err 				= "";
	$wasRun 			= false;
	
	$teilenummerA 		= "";
	$nameA 				= "";
	$j 					= 0;
	/* /Variablen */
		
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{		
		if (!empty($_POST["bauteil"]) && !empty($_POST["stueckzahlA"]))
		{
			$teilenummerSel = trim($_POST["bauteil"]);
			$stueckzahl_ausgewaehlt = trim($_POST["stueckzahlA"]);
			
			if (!empty($teilenummerSel) && !empty($stueckzahl_ausgewaehlt))
			{
				$query = "SELECT stueckzahl_verfuegbar FROM artikel WHERE teilenummer = '$teilenummerSel'";
				
				if ($result = mysqli_query($connection, $query))
				{
					while ($row = mysqli_fetch_row($result))
					{
						$stueckzahl_verfuegbar = $row[0];
						break;
					}
					
					if ($stueckzahl_ausgewaehlt > $stueckzahl_verfuegbar)
					{
						$err = "ERROR: Die Stückzahl darf nicht größer der vorhanden Menge sein!";
					}
					else 
					{
						array_push($_SESSION["selected_teilenummer"], $teilenummerSel);
						array_push($_SESSION["selected_count"], $stueckzahl_ausgewaehlt);
						$err = "Erfolg! Das Bauteil wurde dem Warenkorb hinzugefügt!";
					}
				}
				else 
				{
					$err = "ERROR: Konnte die SQL-Abfrage nicht ausführen!";
				}
			}
			else 
			{
				$err = "ERROR: Die Stueckzahl bzw. die Teilenummer dürfen nicht leer sein!";
			}
		}
		
		$wasRun = true;
	}
		
	$query = "SELECT * from artikel";
		
	if ($result = mysqli_query($connection, $query))
	{
		while ($row = mysqli_fetch_row($result))
		{
			$teilenummer[$i] 		= $row[0];
			$name[$i] 				= $row[1];
			$link[$i] 				= $row[2];
			$beschreibung[$i] 		= $row[3];
			$stueckzahl[$i] 		= $row[4];
			$stueckzahl_v[$i] 		= $row[5];
			$foto[$i] 				= $row[6];
			$eaglebib[$i] 			= $row[7];
			$lager_raum[$i] 		= $row[8];
			$lager_schrank[$i] 		= $row[9];
			$meldebestand[$i] 		= $row[10];
			$rueckgabe_noetig[$i] 	= $row[11];
			$i++;
		}
	}
	else 
	{
		echo "ERROR: Konnte die SQL-Abfrage nicht ausfuehren!";
	}
	
	$query = "SELECT teilenummer, name FROM artikel";
	
	if ($result = mysqli_query($connection, $query))
	{
		while ($row = mysqli_fetch_row($result))
		{
			$teilenummerA[$j] 	= $row[0];
			$nameA[$j] 			= $row[1];
			$j++;
		}
	}
?>

<!DOCTYPE HTML>
<HTML lang="DE">
	<HEAD>
		<!-- Meta Tags -->
		<META charset="utf-8">
		<META name="viewport" content="width = device-width, initial-scale = 1, shrink-to-fit = no">

		<!-- Bootstrap CSS -->
		<LINK rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<TITLE>
			Bauteilverwaltung
		</TITLE>
	</HEAD>
	<BODY>
		<!-- Navigationsleiste -->
		<NAV class="navbar navbar-expand-lg navbar-light bg-light">
			<A class="navbar-brand" href="home.php">
				<IMG src="bilder/logo.svg" width="100" height="100" title="Home">
			</A>

			<BUTTON class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<SPAN class="navbar-toggler-icon"></SPAN>
			</BUTTON>

			<DIV class="collapse navbar-collapse" id="navbarSupportedContent">
				<UL class="navbar-nav mr-auto">
					<LI class="nav-item">
						<A class="nav-link" href="home.php">
							Home
						</A>
					</LI>
					<LI class="nav-item">
						<A class="nav-link" href="labor.php">
							Labor
						</A>
					</LI>
					<LI class="nav-item active">
						<A class="nav-link" href="inventar.php">
							Inventar
							<SPAN class='sr-only'>(current)</SPAN>
						</A>
					</LI>
					<LI class="nav-item">
						<A class="nav-link" href="kontakt.php">
							Kontakt
						</A>
					</LI>
					<?PHP
						if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
						{
							if ($_SESSION["id"] > 2)
							{
								echo 
								"
								<LI class='nav-item dropdown'>
									<A class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
										Adminpanel
									</A>
									<DIV class='dropdown-menu' aria-labelledby='navbarDropdown'>
										<A class='dropdown-item' href='admin_bau_hinzu.php'>
											Bauteil hinzufügen
										</A>
										<A class='dropdown-item' href='admin_bau_bearbeiten.php'>
											Bauteil bearbeiten
										</A>
										<A class='dropdown-item' href='admin_bau_loeschen.php'>
											Bauteil/e löschen
										</A>
										<A class='dropdown-item' href='admin_meldebestand.php'>
											Meldebestand
										</A>
										<DIV class='dropdown-divider'></DIV>
										<A class='dropdown-item' href='admin_ueberpruefen.php'>
											Studenten akzeptieren
										</A>
										<A class='dropdown-item' href='admin_moderator_hinzufuegen.php'>
											Moderator hinzufuegen
										</A>
										<A class='dropdown-item' href='admin_loeschen.php'>
											Account löschen
										</A>
									</DIV>
								</LI>
								";
							}
							
							echo 
							"
							<LI class='nav-item'>
								<A class='nav-link'>
									Eingeloggt als: <FONT color='blue'>" . $_SESSION["username"] . "</FONT>
								</A>
							</LI>
							";
						}
					?>
				</UL>				

				<UL class="nav navbar-nav navbar-right">
					<LI>
						<!-- Volltextsuchfeld -->
						<FORM class="form-inline my-2 my-lg-0">
							<INPUT class="form-control mr-sm-2" type="search" placeholder="Suche..." aria-label="Search">
							<BUTTON class="btn btn-outline-success my-2 my-sm-0" type="submit">
								Suchen
							</BUTTON>
						</FORM>
					</LI>
					<LI>
						<A class="button" href="warenkorb.php">
							<IMG src="bilder/Warenkorb.png" width="32" height="32" title="Warenkorb">
						</A>
					</LI>
					<?PHP
						if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
						{
							echo 
							"
							<LI>
								<A class='button' href='ausloggen.php'>
									<IMG src='bilder/logout.png' width='32' height='32' title='Abmelden'>
								</A>
							</LI>
							";
						}
						else 
						{
							echo 
							"
							<LI>
								<A class='button' data-toggle='modal' data-target='#anmeldung'>
									<IMG src='bilder/login.png' width='32' height='32' title='Anmelden'>
								</A>
							</LI>
							";
						}
					?>
				</UL>
			</DIV>
		</NAV>
		<!-- /Navigationsleiste -->
		
		<!-- Inventar -->
		<DIV class="container">
			<DIV class="center">
				<TABLE border="1px solid black">
				<?PHP
						echo 
						"
						<TR>
							<TH>
								Teilenummer
							</TH>
							<TH>
								Name
							</TH>
							<TH>
								Link
							</TH>
							<TH>
								Beschreibung
							</TH>
							<TH>
								Stueckzahl / Stueckzahl Verfuegbar
							</TH>
							<TH>
								Foto
							</TH>
							<TH>
								Eaglebib
							</TH>
							<TH>
								Lager Raum
							</TH>
							<TH>
								Lager Schrank
							</TH>
						</TR>
						";
						
						while (--$i >= 0)
						{
						echo
						"
						<TR>
							<TD>
								$teilenummer[$i]
							</TD>
							<TD>
								$name[$i]
							</TD>
							<TD>
								$link[$i]
							</TD>
							<TD>
								$beschreibung[$i]
							</TD>
							<TD>
								$stueckzahl[$i] / $stueckzahl_v[$i]
							</TD>
							<TD>
								$foto[$i]
							</TD>
							<TD>
								$eaglebib[$i]
							</TD>
							<TD>
								$lager_raum[$i]
							</TD>
							<TD>
								$lager_schrank[$i]
							</TD>
						</TR>
						";
						}
				?>
				</TABLE>
				
				<FORM action="<?PHP echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<SELECT class="form-control" name="bauteil">
						<?PHP
						if ($j == 0)
						{
							echo
							"
							<OPTION value=''>Keine Artikel in der Datenbank</OPTION>
							";
						}
						else 
						{
							while (--$j >= 0)
							{
								echo 
								"
								<OPTION value=$teilenummer[$j]>$teilenummer[$j] / $name[$j]</OPTION>
								";
							}
						}
						?>
					</SELECT>
					<INPUT type="text" class="form-control" name="stueckzahlA"></INPUT>
					
					<BUTTON class="btn btn-outline-success my-2 my-sm-0" type="submit">
						Zum Warenkorb hinzufügen
					</BUTTON>
				</FORM>
				<?PHP 
				if ($wasRun == true)
				{
					echo "<B>" . $err . "</B>";
				}
				?>
			</DIV>
		</DIV>
		<!-- /Inventar -->

		<!-- Anmelde Dialog -->
		<DIV class="modal fade" id="anmeldung" role="dialog">
			<DIV class="modal-dialog modal-sm">
				<DIV class="modal-content">
					<DIV class="modal-header">
						<P>
							Anmelden
						</P>
						<BUTTON type="button" class="close" data-dismiss="modal">
							&times;
						</BUTTON>
					</DIV>

					<DIV class="modal-body">
						<FORM action="einloggen.php" method="post">
							<DIV class="form-group">
								<LABEL>
									Campus ID:
								</LABEL>
								<INPUT type="text" name="campusid" class="form-control" id="campus_id" placeholder="campusid">
							</DIV>
							<DIV class="form-group">
								<LABEL>
									Passwort:
								</LABEL>
								<INPUT type="password" name="passwort" class="form-control" id="passwd" placeholder="****">
							</DIV>
							<BUTTON type="submit" class="btn btn-default">
								Anmelden
							</BUTTON>
							<BUTTON type="submit" class="btn btn-default" data-dismiss="modal">
								Abbrechen
							</BUTTON>
						</FORM>
					</DIV>

					<DIV class="modal-footer">
						<DIV class="form-group">
							<LABEL>
								<A class="form-control" href="neu_hier.php">
									Registrieren
								</A>
							</LABEL>
							<LABEL>
								<A class="form-control" href="passwort_vergessen.php">
									Passwort vergessen
								</A>
							</LABEL>
						</DIV>
					</DIV>
				</DIV>
			</DIV>
		</DIV>
		<!-- Anmelde Dialog -->
		
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</BODY>
</HTML>