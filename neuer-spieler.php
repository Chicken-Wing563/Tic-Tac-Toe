
<!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <title>Neuer Spieler</title>
    <link rel="stylesheet" href="stylesheets/style.css">
</head>

<body>

<div class="zentral container_highscore">

    <h1>Neuen Spieler adden</h1>

    <?php
	
		$mysqli = new mysqli("localhost", "root", "", "tictactoe");
		if (!empty($_POST['name'])) {

			$check = $mysqli->prepare(
				"SELECT 1 FROM spieler WHERE Name = ?"
			);
			
			$check->bind_param("s", $_POST['name']);
			$check->execute();
			$check->store_result();

			if ($check->num_rows > 0) {
				echo "<p style='color:red'>❌ Spieler existiert bereits.</p>";
			} 
			
			else {
				$stmt = $mysqli->prepare(
					"INSERT INTO spieler (Name, Score, Created, Updated)
					VALUES (?, 0, NOW(), NOW())"
				);
				$stmt->bind_param("s", $_POST['name']);
				$stmt->execute();
				echo "<p style='color:green'>✅ Spieler hinzugefügt!</p>";
				$stmt->close();
			}	

			$check->close();
		
		}

    ?>

    <form method="post">

        <input type="text"
               name="name"
               placeholder="Spielername"
               required>

        <br><br>

        <button class="zentral button_fame">
            Zur Highscore-Liste hinzufügen
        </button>

    </form>

    <br>

    <a href="/tictactoe/highscore.php"
       class="zentral button">
        Zurück zur Highscore
    </a>

</div>

</body>
</html>
