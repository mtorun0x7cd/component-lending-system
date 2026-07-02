<?PHP
	session_start();
	
	require_once "config.php";
	
	$selected_teilenummer 	= array();
	$selected_count			= array();
	$wasLoad 				= false;
	$err 					= "";
	$wasRun 				= false;
	
	/* Load Session Arrays, if possible */
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
	{
		if (isset($_SESSION["selected_teilenummer"]) && isset($_SESSION["selected_count"]))
		{
			$selected_teilenummer = $_SESSION["selected_teilenummer"];
			$selected_count = $_SESSION["selected_count"];
			$wasLoad = true;
		}
	}
	/* /Load Session Arrays, if possible */
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$campusid = $_SESSION["username"];
		$ausgegeben_am = date('Y-m-d');
		$zurueckzugeben_bis = date('Y-m-d', strtotime("$ausgegeben_am +180 days"));
		$status = "Offen";
		
		$query = "INSERT INTO ausleihe (campusid, ausgegeben_am, zurueckzugeben_bis, status) VALUES ('$campusid', '$ausgegeben_am', '$zurueckzugeben_bis', '$status')";
		
		if ($result = mysqli_query($connection, $query))
		{
			$ausleihid = (int) mysqli_insert_id($connection);
			
			$teilenummerASel = 0;
			$stueckzahlASel = 0;
			
			$idx = 0;
			foreach ($selected_teilenummer as $teilenummerSel)
			{
				$stueckzahlSel = $selected_count[$idx++];
					
				$teilenummerASel = (int) $teilenummerSel;
				$stueckzahlASel = (int) $stueckzahlSel;
				$query = "INSERT INTO ausleihposition (ausleihid, teilenummer, stueckzahl) VALUES ($ausleihid, $teilenummerASel, $stueckzahlASel)";
				if ($result = mysqli_query($connection, $query))
				{
					
				}
				else 
				{
					$err = "ERROR: Konnte SQL-Abfrage nicht ausführen!";
					break;
				}
			}
			
			$err = "Erfolg! Ihre Ausleihe wurde erfolgreich getätigt! Drucken Sie diese Seite und gehen Sie damit zu Ihrem Betreuer um die Bauteile abzuholen!";
		}
		else 
		{
			$err = "ERROR: Konnte SQL-Abfrage nicht ausführen!";
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
		
		<!-- Warenkorb -->
		<DIV class="container">
			<DIV class="center">
				<H1>
					Warenkorb
				</H1>
				<!-- Warenkorb Überprüfung -->
				<?PHP
					if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
					{
						if ($_SESSION["id"] == 1)
						{ 
							echo "<B>Der Warenkorb ist nur für Überprüfte Mitglieder verfügbar!</B>";
							exit;
						}
					}
					else 
					{
						echo "<B>Bitte zuerst einloggen!</B>";
						exit;
					}
				?>
				<!-- /Warenkorb Überprüfung -->
				<H3>
					Hier erhalten Sie eine Übersicht über Ihre zur Ausleihe ausgewählten Bauteile:
				</H3>
				
				<FORM action="<?PHP echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<TABLE border="1px solid black">
						<TR>
							<TH>
								Teilenummer
							</TH>
							<TH>
								Stueckzahl
							</TH>
						</TR>
						
						<?PHP
						$idx = 0;
						foreach ($selected_teilenummer as $teilenummerSel)
						{
							$stueckzahlSel = $selected_count[$idx++];
							
							echo 
							"
							<TR>
								<TD>
									$teilenummerSel 
								</TD>
								<TD>
									$stueckzahlSel
								</TD>
							</TR>
							";
						}
						?>
					</TABLE>
			
					<BUTTON class="btn btn-outline-success my-2 my-sm-0" type="submit">
						Ausleihen
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
		<!-- /Warenkorb -->
		
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