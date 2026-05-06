<?php

session_start();
if (isset($_SESSION['1Spieler'])) {
    $_GET['1Spieler'] = $_SESSION['1Spieler'];
}
if (isset($_SESSION['2Spieler'])) {
    $_GET['2Spieler'] = $_SESSION['2Spieler'];
}
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Startseite Tic Tac Toe </title>
<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >

</head>

<body>

<div class="zentral" >
	<h1>
		Willkommen zu<br> 
		Tic Tac Toe 
		<p class="by"> by Amelie </p>
	</h1>
</div>

<br><br>

<div>
	<h4 class="zentral">
		Bitte gebt eure Namen in die Feldern ein!
	</h4>
</div>

<br><br>

<div>

	<form action="spiel-tictactoe.php" method="get">
	<div class="form-row">
		<div class="form-group" >
			<label for="1Spieler" >1 Spieler:</label><br>
			<input type="text" name="1Spieler" value="<?= htmlspecialchars($_SESSION['1Spieler'] ?? '') ?>" required ><br>
		</div>
		<br>
		
		<div class="form-group" >
			<label for="2Spieler" >2 Spieler:</label><br>
			<input type="text" name="2Spieler" value="<?= htmlspecialchars($_SESSION ['2Spieler'] ?? '') ?>" required >
		</div>
		
		<br>
	</div>
	<br>
	
	<div class="submit-row">
		<input type="submit" class="zentral button" value="Spielen">
	</div>
	
	</form>
	
</div>

</body>

</html>