<?php
	require 'config.php';
					session_start();
					use PHPmailer\PHPMailer\PHPMailer;
	
						// ********** inlog + register ********* \\
								// ***** inlog *****\\
								if(isset($_POST["inlog"]))
								{
									if(isset($_POST["UsernameLogin"]) && isset($_POST["passwordLogin"]) && strlen($_POST["UsernameLogin"]) > 0 && strlen($_POST["passwordLogin"]) > 0)
									{
										// pak de inlog variable \\
										$Username = htmlentities($_POST["UsernameLogin"]);
										$Username = str_replace(".", "_", $Username);
										
										$Wachtwoord = htmlentities($_POST["passwordLogin"]);
										$opdracht_inloggen = mysqli_query($mysqli, "SELECT * FROM encircle_GB WHERE email LIKE '" . $Username . "' AND wachtwoord = '". sha1($Wachtwoord) ."'");
									while ($row = mysqli_fetch_array($opdracht_inloggen))
									{
										// zet als er al resultaaten zijn in een sessie \\
										$_SESSION["gb_id"] = $row['GB_ID'];
										$_SESSION["email"] = $row['email'];
										$_SESSION["GB_leeftijd"] = $row['leeftijd'];
										$_SESSION["GB_geslacht"] = $row['geslacht'];
										$_SESSION["GB_naam"] = $row['naam'];
										//echo "uw id =" .$_SESSION["gb_id"];
									}
										$resultaat_inloggen = mysqli_num_rows($opdracht_inloggen);
										// ** if naam exists ** \\
										if(!$resultaat_inloggen)
										{
											// * query * \\
											$opdracht_naam = mysqli_query($mysqli, "SELECT * FROM encircle_GB WHERE email LIKE '". $Username ."'" );
											$resultaat_naam = mysqli_num_rows($opdracht_naam);
											
											if(!$resultaat_naam)
											{
												echo("<div class='col-sm'><div class='alert alertloc alert-danger' role='alert'>Gebruikersnaam bestaat niet!</div></div>");
											}
											
											else
											{
												echo("<div class='col-sm'><div class='alert alert-danger' role='alert'>U heeft een verkeerd wachtwoord ingevoerd!</div></div>");
											}
										}
										
										else
										{
											// *** zet email plus wachtwoord in een sessie *** \\
											echo("<div class='col-sm'><div class='alert alertloc  alert-success' role='alert'>U bent nu ingelogd.</div></div>");
											$Username = str_replace("_", ".", $Username);
												$_SESSION["email"] = $Username;
												$_SESSION["password"] = $Wachtwoord;
												$_SESSION['signed_in'] = true;
												header('refresh:3 url=index.php');
										}
									}

									else
									{
										echo("<div class='col-sm'><div class='alert alertloc alert-danger' role='alert'>De gebruikersnaam of het wachtwoord is niet ingevuld!</div></div>");
									}
								}
								// *** register *** \\
								if(isset($_POST["register"]))
							{
								// alles is ingevuld
								if(isset($_POST["naam"]) && isset($_POST["password"]) && isset($_POST["Email"]) && isset($_POST["leeftijd"]) && isset($_POST["geslacht"]))	
								{
									// alles heeft waarde
									if(strlen($_POST["naam"]) > 0 && strlen($_POST["password"]) > 0 && strlen($_POST["Email"]) > 0 && strlen($_POST["leeftijd"]) > 0 && strlen($_POST["geslacht"]) > 0)
									{
										//zet alle velden in een variable
										$naam = htmlentities($_POST["naam"]);
										$email = htmlentities($_POST["Email"]);
										
										$leeftijd = htmlentities($_POST["leeftijd"]);
										$geslacht = htmlentities($_POST["geslacht"]);
										$wachtwoord = sha1($_POST["password"]);
										// als je email al gebruikt word
										$opdracht_emailcheck = mysqli_query($mysqli, "SELECT * FROM encircle_GB WHERE email LIKE '" . $email ."'");
										// check email
										if (mysqli_num_rows($opdracht_emailcheck) == 0)
										{
											$emailtoken = 'qwerzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM0123456789qwerzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM0123456789qwerzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM0123456789';
											$emailtoken = str_shuffle($emailtoken);
											$emailtoken = substr($emailtoken, 0, 55);
											$opdracht_register = mysqli_query($mysqli, "INSERT INTO encircle_GB VALUES (NULL, '" . $naam . "', '" . $email . "' , '" . $leeftijd ."', '". $geslacht . "', '" . $wachtwoord . "', 0, '". $emailtoken ."')");
											$email = str_replace(".", "_", $email);
											
											$getid = mysqli_query($mysqli, "SELECT * FROM encircle_GB WHERE email LIKE '". $email ."'");
											$idres = mysqli_fetch_array($getid);
											$gb_idpf = $idres["GB_ID"];
											$email = str_replace("_", ".", $email);
											include_once "PHPmailer/PHPMailer.php";

											$mail = new PHPMailer();

											$mail->setFrom('encircle@info.com');
											$mail->addAddress($email, $name);
											$mail->Subject = "Encircle verify email.";
											$mail->isHTML(true);
											$mail->Body = "
											Please confirm your accaunt with the link below <br><br>
											<a href='80103.ict-lab.nl/encircle/confirmEmail.php?email=". $email ."&token=". $emailtoken. "'>click here </a><br>
											If you do not know why this is for please ignore the email and the link.";
											if ($mail->send())
											{
												if ($opdracht_register)
												{	
													echo("<div class='col-sm'><div class='alert alertloc alert-success' role='alert'>U bent geregristreert. Activeer uw email</div></div>");
													echo $email;
													//header('refresh:5 url=inlog.php');
												}
												else{
													echo "fout";
												}
											}
											else
											{
												echo("<div class='col-sm'><div class='alert alertloc alert-danger' role='alert'>Mail kon niet versturen.</div></div>");
											}
										} 
										else
										{
											echo("<div class='col-sm'><div class='alert alertloc  alert-danger' role='alert'>U heeft al een acc op dit email.</div></div>");

										}
									}
									else
									{
										echo("<div class='col-sm'><div class='alert alertloc  alert-danger' role='alert'>Er is iets niet goed ingevuld.</div></div>");
									}
								}
								else
								{
									echo("<div class='col-sm'><div class='alert alertloc alert-danger' role='alert'>Niet alles is ingevuld.</div></div>");
								}
							}
							else
							{
							}
								
						
	// Lees het config bestand

	// Begin de session
	//require_once 'session.php';
	
?>

<html>
	<!-- Begin header -->
	<?php require 'header.php'; ?>
	<!-- Eind header -->

	<div>
		<!-- Begin body -->
		<div class="body">
			<?php
				
				include ("config.php");

				if(isset($_POST["signed_in"]) && $_POST["signed_in"] == true)
				{
					header("index.php");
				}
				if (!isset($_POST['token'])){
					$token = bin2hex(openssl_random_pseudo_bytes (32));
					$_SESSION["token"] = $token;
				}
			?>

			<meta charset="UTF-8">
			<title>Inlog</title>
			
			<!-- jquery-->
			<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
			<!-- popper.js -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<!-- bootstrap.js -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



			<!-- first row start -->
			<div class="row">
				<div class="col-4">
					<!--
					<div class="kaart">
						<div class="card mt-5 hoverable">
					
						</div>
					</div>
					-->
				</div>

				<div class="col-4">
					<h1 id="title">Login to Encircle</h1>
					<!--
					<div class="kaart">
						<div class="card mt-5 hoverable title-A">
							<div class="">
								
							</div>
						</div>
					</div>
					-->
				</div>	

				<div class="col-4">
					<!--
					<div class="kaart">
						<div class="card mt-5 hoverable">
					
						</div>
					</div>
					-->
				</div>
			</div>
			<!-- first row end -->



			<!-- second row start -->
			<div class="row">
				<div class="col-4">
					<!--
					<div class="kaart">
						<div class="card mt-5 hoverable">
					
						</div>
					</div>
					-->
				</div>
				
				<div class="col-4">
					<div class="kaart">
						<div class="card mt-5 hoverable login-A">
							<!-- Inlog container -->
							<div class="container">
								<div class="row">
									<div class="">
										&nbsp;
									</div>
										<form class="left-side" method="post" action="?inlog=1">
											<div class="form-row">
												<div class="form-group">
													<label class="text-AA" for="UsernameLogin">Email</label>
													<input type="UsernameLogin" class="form-control" name="UsernameLogin" id="UsernameLogin" placeholder="email adress">
												</div>

												<div class="form-group">
													<label class="text-AB" for="passwordLogin">Wachtwoord</label>
													<input type="password" class="form-control" name="passwordLogin" id="passwordLogin" placeholder="Password">
												</div>

												<div class="form-group">
													<label for="token"></label>
												</div>
											</div>

											<div class="form-group">
												<div class="button-AA">
													<button type="submit" class="btn btn-primary" name="inlog" id="inlog">Sign in</button>
												</div>

												<div class="button-AB">
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inlogModal">Register</button>
												</div>	
											</div>

											<div class="form-group">

											</div>																							
										</form>
									
									<div class="col-sm">
										&nbsp;
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-4">
					<!--
					<div class="kaart">
						<div class="card mt-5 hoverable">
					
						</div>
					</div>
					-->
				</div>
			</div>
			<!-- second row end -->
		</div>

		<!-- Modal -->
		<!-- *********** register forum *********** -->
		<div class="modal fade" id="inlogModal" tabindex="-1" role="dialog" aria-labelledby="inlogModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="inlogModalLabel">Maak hier je account.</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<div class="modal-body">
						<form method="post" action="?register=1">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="naam">Naam</label>
									<input type="name" class="form-control" name="naam"  id="name" placeholder="Gebruikers Naam">
								</div>

								<div class="form-group col-md-6">
									<label for="Email">Email</label>
									<input type="Email" class="form-control" name="Email"  id="sirname" placeholder="Email">
								</div>
							</div>
							
							<div class="form-row"> 
								<div class="form-group col-md-6">
									<label for="Leeftijd">leeftijd</label>
									<input type="number" class="form-control" name="leeftijd"  id="username" placeholder="leeftijd">
								</div>
							</div>
							
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="Geslacht">Geslacht</label>
									<input type="text" class="form-control" name="geslacht"  id="geslacht" placeholder="Geslacht">
								</div>

								<div class="form-group col-md-6">
									<label for="password">Wachtwoord</label>
									<input type="password" class="form-control" name="password"  id="password" placeholder="Wachtwoord">
								</div>
							</div>
					<div class="modal-footer">
					<!-- register knop -->
						<button type="submit" class="btn btn-primary" name="register">Register</button>
						
						<!-- close en discard-->
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>	
						</form>		
					</div>


				</div>
			</div>
		</div>
		
		<!-- Eind body -->

		<!-- Begin footer -->
		<?php require 'footer.php'; ?>
		<!-- Eind footer -->
	</div>
</body>
</html>