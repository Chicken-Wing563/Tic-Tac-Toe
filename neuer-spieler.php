<!doctype html>
<html>

<head>

	<meta charset="utf-8">
	<title>Neuer Spieler</title>
	<link rel="stylesheet" href="stylesheets/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>


<body>

<div class="zentral container_highscore_neu"> 
   
	<h1>Neuen Spieler adden</h1>

	<?php 
	
		$mysqli = new mysqli("localhost", "root", "", "tictactoe");
		if (!empty($_POST['name'])) {
			
			§check = $mysqli->prepare (
			);
			
			$check->bind_param("s", $_POST['name']);
     	$check->execute();
     	$check->store_result();
			
			
			
		}	
	
    ?>

	<form method="post">
		
		<input 
			type="text"
			name="name"
			placeholder="Spielername"
			required
		>
		
		<br><br>
		
		<button class="zentral button_fame">
		Zur Highscore Liste Hinzufügen
		</button>
		
	</form>
	
	<br>
	
	<a href="/tictactoe/highscore.php" class="zentral button">
	Zurück
	</a>
	
</div>

</body>
</html>