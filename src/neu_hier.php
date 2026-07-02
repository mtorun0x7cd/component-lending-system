<?PHP 
	session_start();

	require_once "config.php";
	
	$wasRun 	= false;
	$err 		= "";
		
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		/* Variablen */
		$email			= trim($_POST["email"]);
		$vorname		= trim($_POST["vorname"]);
		$nachname		= trim($_POST["nachname"]);
		$campusid		= trim($_POST["campusid"]);
		$password		= trim($_POST["password"]);
		/* /Variablen */
		
		if (empty($email) || empty($campusid) || empty($password))
		{
			$err = "ERROR: E-Mail, Campus ID oder Passwort ist leer!";
		}
		else 
		{
			// Check if E-Mail or Campus ID already exists
			$query = "SELECT * FROM benutzer WHERE email = '$email' OR campusid = '$campusid'";
			
			if ($result = mysqli_query($connection, $query))
			{
				$isFound = false;
				while ($row = mysqli_fetch_row($result))
				{
					$isFound = true;
					break;
				}
				
				if ($isFound)
				{
					$err = 
						"
						<P>
							Diese E-Mail Adresse oder Campus ID wird schon verwendet.
							Sollte dies ein Fehler sein, so wenden Sie sich bitte an einen Systemadministrator.
						</P>
						";
				}
				else 
				{
					$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
					$register = "INSERT INTO benutzer (campusid, id, passwort, vorname, nachname, email) VALUES ('$campusid', 1, '$hashedPassword', '$vorname', '$nachname', '$email');";
					if (mysqli_query($connection, $register))
					{
						$err = 
							"
							<P>
								Die Registrierung war erfolgreich.
							</P><BR>
							<P>
								Ihr Nutername lautet '$campusid' und ihr Passwort ist '$password'
							</P><BR>
							";
					}
					else 
					{
						$err = 
							"
							<P>
								Die Registrierung war nicht erfolgreich.
							</P>
							";
					}
				}
			}
			else 
			{
				$err = "ERROR: Konnte die SQL-Abfrage nicht ausfuehren!";
			}
		}
		
		$wasRun = true;
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
					<LI class="nav-item">
						<A class="nav-link" href="inventar.php">
							Inventar
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
		
		<!-- Neu Hier -->
		<DIV class="container">
			<DIV class="center">
				<H1>
					Neu Anmeldung
				</H1>
				<H3>
					Bitte geben Sie ihre Informationen in das Formular ein 
					und bestätigen diese durch das Abschicken des Formulars. 
					Anschließend erhalten Sie eine Bestätigungsmail. 
					Nach der manuellen Überprüfung, wird Ihr Konto frei geschaltet.
				</H3>
				
				<FORM action="neu_hier.php" method="post">
					<DIV class="form-group">
						<LABEL>
							E-Mail (Hier bitte Ihre SMail Adresse der TH Köln eintragen!):
						</LABEL>
						<INPUT type="email" class="form-control" name="email" placeholder="max.mustermann@smail.th-koeln.de">
					</DIV>
					<DIV class="form-group">
						<LABEL>
							Vorname:
						</LABEL>
						<INPUT type="text" class="form-control" name="vorname" placeholder="Max">
					</DIV>
					<DIV class="form-group">
						<LABEL>
							Nachname:
						</LABEL>
						<INPUT type="text" class="form-control" name="nachname" placeholder="Mustermann">
					</DIV>
					<DIV class="form-group">
						<LABEL>
							Campus ID:
						</LABEL>
						<INPUT type="text" name="campusid" class="form-control" placeholder="mmustermann">
					</DIV>
					<DIV class="form-group">
						<LABEL>
							Passwort:
						</LABEL>
						<INPUT type="password" name="password" class="form-control" placeholder="****">
					</DIV>
					<INPUT class="btn btn-outline-success my-2 my-sm-0" type="reset" value="Zurücksetzen">
					<INPUT class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Registrieren">
				</FORM>
				<?PHP
				if ($wasRun == true)
				{
					echo "<B>" . $err . "</B>";
				}
				?>
			</DIV>
		</DIV>
		<!-- /Neu Hier -->
		
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