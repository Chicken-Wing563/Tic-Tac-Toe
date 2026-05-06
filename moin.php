<!doctype html>
<html> 

<head> 
  
    <title>Tic Tac Toe Highscore Liste</title>
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >
	
</head>
  
<body>

	<div class="zentral container_highscore_neu">
		
		<h1>&#10024; Hall of Fame &#10024;</h1>
		
		<?php
		
			$mysqli = new mysqli("localhost", "root", "", "tictactoe");
				
			if (!empty($_POST['new_player_name'])) {
				$stmt = $mysqli->prepare(
					"INSERT INTO spieler (Name, Score, Created, Updated)
					VALUES (?, 0, NOW(), NOW())"
			);
				$stmt->bind_param("s", $_POST['new_player_name']);
				$stmt->execute();
				$stmt->close();
}

			if (!empty($_POST['score_action']) && !empty($_POST['score_name'])) {

				if ($_POST['score_action'] === 'plus') {
					$sql = "
						UPDATE spieler
						SET Score = Score + 1,
							Updated = NOW()
						WHERE Name = ?
					";
				}

				if ($_POST['score_action'] === 'minus') {
					$sql = "
						UPDATE spieler
						SET Score = GREATEST(Score - 1, 0),
							Updated = NOW()
						WHERE Name = ?
					";
				}

				$stmt = $mysqli->prepare($sql);
				$stmt->bind_param("s", $_POST['score_name']);
				$stmt->execute();
				$stmt->close();
			}

			if (!empty($_POST['delete_Name'])) {
				$stmt = $mysqli->prepare("DELETE FROM spieler WHERE Name = ?");
				$stmt->bind_param("s", $_POST['delete_Name']);
				$stmt->execute();
				$stmt->close();
			}	


		$result = $mysqli->query("SELECT * FROM spieler ORDER BY Score DESC");
		
		?>

		<table>
  
			<thead>
			<tr>
				<th>Platz</th>
				<th>Name</th>
				<th>Highscore</th>
				<th>Erstellt am</th>
				<th>Zuletzt Aktualisiert</th>
				<th>Aktion</th>
			</tr>
		
			</thead>
    
			<tbody>
				
				<?php
					foreach ($result as $key => $row) {

						echo "<tr>";
						
						echo "<td>" . (++$key) . "</td>";
						echo "<td>" . $row['Name'] . "</td>";
						echo "<td>" . $row['Score'] . "</td>";
						echo "<td>" . date("d.m.Y", strtotime($row['Created'])) . "</td>";
						echo "<td>" . date("d.m.Y", strtotime($row['Updated'])) . "</td>";
	
						echo "<td>
						<div class='action-buttons'>
						
							<form method='post' style='display:inline'>
								<input type='hidden' name='score_name' value='{$row['Name']}'>
								<input type='hidden' name='score_action' value='plus'>
								<button class='button_fame'>+1</button>
							</form>
							
							<form method='post' style='display:inline'>
								<input type='hidden' name='score_name' value='{$row['Name']}'>
								<input type='hidden' name='score_action' value='minus'>
								<button class='button_fame'>-1</button>
							</form>
							
							<br>
							
							<form method='post' style='display:inline'>
								<input type='hidden' name='delete_Name' value='{$row['Name']}'>
								<button type='submit' class='zentral button_fame' 
										onclick=\"return confirm('Spieler wirklich löschen?')\">
									Löschen
								</button>
							</form>
							
						</div>	
						</td>";

						echo "</tr>";
					}
				?>

				<br>
			
			</tbody>
		
		</table>
		
		<br>
	
		<div class="button_fame_unten">

			<a href="/tictactoe/spiel-tictactoe.php" class="zentral button">
				Weiter Spielen
			</a>

			<a href="/tictactoe/neuer-spieler.php"
				class="zentral button">
				Neuer Spieler
			</a>

		</div>

	</div>
	
</body>

</html>