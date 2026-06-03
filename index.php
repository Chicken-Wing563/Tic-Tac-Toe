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

<br>

<div>
	<h4 class="zentral">
		Bitte gib dein Namen in das Feld ein!
	</h4>
</div>

<br><br>

<div>

	<form action="" method="get">
	<div class="form-row">
	
		<div class="form-group" >
			<label for="1Spieler" >1 Spieler:</label><br>
			<input type="text" name="1Spieler" value="<?= htmlspecialchars($_SESSION['1Spieler'] ?? '') ?>" required ><br>
		</div>
		<br>
		
		<div>
			<div class="form-group" >
				<label for="matchcode" >Match Code:</label><br>
				<input type="text" name="matchcode" required >
			</div>
		
			<div class="submit-row">
			<input type="submit" class="zentral button" value="Match Beitreten">
			</div>
		</div>	
	
	</div>
	</form>
	
	<br><br>
	
	<form action="" method="get">
	<div class="form-row">
	
		<div class="form-group" >
			<label for="1Spieler" >1 Spieler:</label><br>
			<input type="text" name="1Spieler" value="<?= htmlspecialchars($_SESSION['1Spieler'] ?? '') ?>" required ><br>
		</div>
		
		<br>
		
		<div class="submit-row">
			<input type="submit" class="zentral button" value="Neues Match Starten">
		</div>
	
	</div>
	</form>
	
</div>

</body>

</html>