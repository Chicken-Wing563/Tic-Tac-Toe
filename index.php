<?php

session_start();
session_unset(); 
session_destroy();

session_start();
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
		<p class="by"> by Amelie &#9996;</p>
	</h1>
</div>


<div class="zentral">

	<h3>
		Starte ein neues Match oder trete eins bei
	</h3>
	<h4>
		Bitte gib dein Namen dafür ein!
	</h4>

</div>

<br><br>

<div>

	<form method="get">
	<div class="form-row zentral">

		<div class="form-group" >
			<label for="1Spieler Name" >Spieler Name:</label>
			<input type="text" name="1Spieler"  value="<?= htmlspecialchars( $_GET['1Spieler'] ) ?>" required >
		</div>

		<div class="submit-row">
			<input type="hidden" name="spiel_erstellt" value="1" >
			<input type="submit" class="zentral button" value="Neues Match Starten" formaction="/match.php">
			<input type="hidden" name='X' value='1'>
		</div>

	</div>
	</form>

<br><br>
<br>

	<form method="get">
	<div class="form-row zentral">

		<div class="form-group" >
                        <label for="2Spieler Name" >Spieler Name:</label>
                        <input type="text" name="2Spieler"  value="<?= htmlspecialchars( $_GET['2Spieler'] ) ?>" required >
		</div>

                <div class="submit-row">
			<input type="submit" class="zentral button" value="Match Beitreten" formaction="/match-code.php">
                	<input type="hidden" name='O' value='1'>
		</div>

	</div>
	</form>


</div>

</body>

</html>
