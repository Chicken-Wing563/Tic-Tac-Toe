<!doctype html>
<html> 

<head> 
  
    <meta charset="utf-8">
    <title>Tic Tac Toe Highscore Liste</title>
	<meta charset="UTF-8"> <!-- Zeichencodierung wegen umlauten etc.-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >
	
	
</head>
  
<body>

	<div class="zentral container_highscore">
		
			<h1>&#10024; Hall of Fame &#10024;</h1>
			
		<table>
  
			<thead>
			<tr>
				<th>Platz</th>
				<th>Name</th>
				<th>Highscore</th>
				<th>Erstellt am</th>
				<th>Zuletzt Aktualisiert</th>
			</tr>
		
			</thead>
    
			<tbody>
				<?php


					$mysqli = new mysqli("localhost", "root", "", "tictactoe");
	
					$result = $mysqli->query("SELECT * FROM `spieler` ORDER BY Score DESC");

					foreach ($result as $key=>$row)  {
   
						echo "<tr>";
						echo "<td>" . ++$key . "</td>";
						echo "<td>" . $row['Name'] . "</td>";
						echo "<td>" . $row['Score'] . "</td>";
						echo "<td>" . date_format (date_create($row['Created']), "d.m.Y") . "</td>";
						echo "<td>" . date_format (date_create($row['Updated']), "d.m.Y") . "</td>";
						echo "</tr>";
					}

				?>
				<br>
			
			</tbody>
		
		</table>
		
		<br>
		<a href="/tictactoe/spiel-tictactoe.php" class="zentral button" >Weiter Spielen</a>
	
	</div>
	

</body>

</html>