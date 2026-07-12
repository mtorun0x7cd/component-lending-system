<?PHP
	require_once "config.php";
	
	session_start();
	
	$teilenummer		= array();
	$name 				= array();
	$i 					= 0;
	$bauteilSelected 	= false;
	$wasRun 			= false;
	$err 				= "";
	
	$teilenummerSel 	= "";
	$artikelName 		= "";
	$link 				= "";
	$beschreibung 		= "";
	$stueck 			= "";
	$foto 				= "";
	$eaglebib  			= "";
	$lagerraum 			= "";
	$lagerschrank 		= "";
	$rueckgabe_noetig 	= "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{		
		$submitbtn = trim($_POST["submitbtn"]);
		
		if ($submitbtn == 0)
		{
			if (!empty($_POST["bauteil"]))
			{	
				$teilenummerSel = trim($_POST["bauteil"]);
				$query = "SELECT name, link, beschreibung, stueckzahl, stueckzahl_verfuegbar, foto, eaglebib, lager_raum, lager_schrank, meldebestand, rueckgabe_noetig FROM artikel WHERE teilenummer = '$teilenummerSel'";
			
				if ($result = mysqli_query($connection, $query))
				{
					while ($row = mysqli_fetch_row($result))
					{
						$artikelName = $row[0];
						$link = $row[1];
						$beschreibung = $row[2];
						$stueckzahl = $row[3];
						$stueckzahl_verfuegbar = $row[4];
						$foto = $row[5];
						$eaglebib = $row[6];
						$lagerraum = $row[7];
						$lagerschrank = $row[8];
						$meldebestand = $row[9];
						$rueckgabe_noetig = $row[10];
					}
					
					$err = "Erfolg! Das Bauteil wurde abgerufen!";
				}
				else 
				{
					$err = "ERROR: Konnte die SQL-Abfrage nicht ausführen!";
				}
				
				$bauteilSelected = true;
			}
		}
		else 
		{
			$teilenummerSel 		= trim($_POST["teilenummer"]);
			$nameS 					= trim($_POST["artikelname"]);
			$linkS 					= trim($_POST["link"]);
			$beschreibungS 			= trim($_POST["beschreibung"]);
			$stueckzahlS 			= trim($_POST["stueck"]);
			$stueckzahl_verfuegbarS = trim($_POST["stueckV"]);
			$fotoS 					= trim($_POST["foto"]);
			$eaglebibS 				= trim($_POST["eaglebib"]);
			$lager_raumS 			= trim($_POST["lagerraum"]);
			$lager_schrankS 		= trim($_POST["lagerschrank"]);
			$meldebestandS 			= trim($_POST["meldebestand"]);
			$rueckgabe_noetigS		= trim($_POST["rueckgabe_noetig"]);

			$query = "UPDATE artikel SET name = '$nameS', link = '$linkS', beschreibung = '$beschreibungS', stueckzahl = '$stueckzahlS', stueckzahl_verfuegbar = '$stueckzahl_verfuegbarS', foto = '$fotoS', eaglebib = '$eaglebibS', lager_raum = '$lager_raumS', lager_schrank = '$lager_schrankS', meldebestand = '$meldebestandS', rueckgabe_noetig = '$rueckgabe_noetigS' WHERE teilenummer = '$teilenummerSel'";
		
			if ($result = mysqli_query($connection, $query))
			{
				$err = "Erfolg! Das Bauteil wurde aktualisiert!";
			}
			else 
			{
				$err = "ERROR: Die SQL-Abfrage konnte nicht ausgeführt werden!";
			}
		}
		
		$wasRun = true;
	}
	
	$query = "SELECT teilenummer, name FROM artikel";
	
	if ($result = mysqli_query($connection, $query))
	{
		while ($row = mysqli_fetch_row($result))
		{
			$teilenummer[$i] 	= $row[0];
			$name[$i] 			= $row[1];
			$i++;
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
										<A class='dropdown-item active' href='admin_bau_bearbeiten.php'>
											Bauteil bearbeiten
											<SPAN class='sr-only'>(current)</SPAN>
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
		
		<!-- Admin Bau Bearbeiten -->
		<!-- Admin Überprüfung -->
		<?PHP
			if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
			{
				if ($_SESSION["id"] < 3)
				{
					header("location: home.php");
					exit;
				}
			}
		?>
		<!-- /Admin Überprüfung -->
		<DIV class="container">
			<DIV class="center">
				<H1>
					Bauteile Bearbeiten:
				</H1>

				<FORM action="<?PHP echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<SELECT class="form-control" name="bauteil">
						<?PHP
						if ($i == 0)
						{
							echo
							"
							<OPTION value=''>Keine Artikel in der Datenbank</OPTION>
							";
						}
						else 
						{
							while (--$i >= 0)
							{
								echo 
								"
								<OPTION value=$teilenummer[$i]>$teilenummer[$i] / $name[$i]</OPTION>
								";
							}
						}
						?>
					</SELECT>
					<INPUT type="hidden" name="submitbtn" value="0">
					<BUTTON class="btn btn-outline-success my-2 my-sm-0" type="submit">
						Bearbeiten
					</BUTTON>
				</FORM>
				<FORM action="<?PHP echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<?PHP
					if ($bauteilSelected == true)
					{
						echo 
						"
							<DIV class='form-group'>
								<LABEL>
									Artikelname:
								</LABEL>
								<INPUT type='text' class='form-control' name='artikelname' value='$artikelName'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Teilenummer:
								</LABEL>
								<INPUT type='text' class='form-control' name='teilenummer' value='$teilenummerSel' readonly>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Link:
								</LABEL>
								<INPUT type='text' class='form-control' name='link' value='$link'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Beschreibung:
								</LABEL>
								<TEXTAREA type='text' class='form-control' name='beschreibung' cols=30 rows=5 value='$beschreibung'></TEXTAREA>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Stueckzahl:
								</LABEL>
								<INPUT type='text' class='form-control' name='stueck' value='$stueckzahl'>
							</DIV>
														<DIV class='form-group'>
								<LABEL>
									Stueckzahl Verfügbar:
								</LABEL>
								<INPUT type='text' class='form-control' name='stueckV' value='$stueckzahl_verfuegbar'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Foto:
								</LABEL>
								<INPUT type='text' class='form-control' name='foto' value='$foto'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Eaglebib:
								</LABEL>
								<INPUT type='text' class='form-control' name='eaglebib' value='$eaglebib'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Lagerraum:
								</LABEL>
								<INPUT type='text' class='form-control' name='lagerraum' value='$lagerraum'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Lagerschrank:
								</LABEL>
								<INPUT type='text' class='form-control' name='lagerschrank' value='$lagerschrank'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Meldebestand (ab wann Sie benachrichtigt werden wollen):
								</LABEL>
								<INPUT type='text' class='form-control' name='meldebestand' value='$meldebestand'>
							</DIV>
							<DIV class='form-group'>
								<LABEL>
									Rückgabe nötig:
								</LABEL>
								<INPUT type='text' class='form-control' name='rueckgabe_noetig' value='$rueckgabe_noetig'>
							</DIV>
							<INPUT type = 'hidden' name='submitbtn' value='1'>
					<BUTTON class='btn btn-outline-success my-2 my-sm-0' type='submit'>
						Aktualisieren
					</BUTTON>
						";
					}
					?>
				</FORM>
				<?PHP 
					if ($wasRun == true)
					{
						echo $err;
					}
				?>
			</DIV>
		</DIV>
		<!-- /Admin Bau Bearbeiten -->
		
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