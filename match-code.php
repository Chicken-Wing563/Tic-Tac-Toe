<?php

session_start();
if (isset($_GET['2Spieler'])) {
    $_SESSION['2Spieler'] = $_GET['2Spieler'];}

$mysqli = new mysqli("localhost", "amelie", "123chickenWings", "tictactoe");

if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Match Code Eingeben Tic Tac Toe </title>
<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >

</head>

<body>

<form method="get">

	<div class="zentral" >
		<h1>
			Tic Tac Toe
		</h1>
	</div>

	<div class="player zentral">
		<div class="player-name">
			<?= htmlspecialchars($_SESSION['2Spieler'] ?? '') ?> <!-- htmlspecialchars schützt vor HTML/Java -->
		</div>
	</div>



	<div class="zentral">
		<h4>
			Gib den Match Code ein um dem Match beizutreten
		</h4>
	</div>

<br><br>
	<div >
		<div class="form-group zentral" >
			<label for="matchcode" >Match Code:</label><br>
			<input type="text" name="matchcode" required >
		</div>

		<div class="submit-row">
			<input type="submit" class="zentral button" value="Match Jetzt Beitreten" formaction="/match.php">
			 <input type="hidden" name='O' value='1'>
		</div>
	</div>

</form>

</body>

</html>
