
<?php

session_start(); /*starten*/


if (!isset($_SESSION['roundFinished'])) {
    $_SESSION['roundFinished'] = false;
}


$mysqli = new mysqli("localhost", "root", "", "tictactoe");

function createPlayerIfNotExists($mysqli, $name) {
    if ($name === '') return;

    $stmt = $mysqli->prepare("SELECT 1 FROM spieler WHERE Name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $insert = $mysqli->prepare(
            "INSERT INTO spieler (Name, Score, Created, Updated)
             VALUES (?, 0, NOW(), NOW())"
        );
        $insert->bind_param("s", $name);
        $insert->execute();
        $insert->close();
    }

    $stmt->close();
}

if (isset($_SESSION['1Spieler'])) {
    createPlayerIfNotExists($mysqli, $_SESSION['1Spieler']);
}
if (isset($_SESSION['2Spieler'])) {
    createPlayerIfNotExists($mysqli, $_SESSION['2Spieler']);
}


/*namen speichern*/
if (isset($_GET['1Spieler'])) {
    $_SESSION['1Spieler'] = $_GET['1Spieler'];
}
if (isset($_GET['2Spieler'])) {
    $_SESSION['2Spieler'] = $_GET['2Spieler'];
}


/*score auf 0*/
if (!isset($_SESSION['scoreX'])) {
    $_SESSION['scoreX'] = 0;
}
if (!isset($_SESSION['scoreO'])) {
    $_SESSION['scoreO'] = 0;
}

/*gewinn kombiss*/
$winCombos = [
    [1,2,3], [4,5,6], [7,8,9],
    [1,4,7], [2,5,8], [3,6,9],
    [1,5,9], [7,5,3]
];

/*gewinn kombis werden durchgegangen*/
function checkWinner($board, $winCombos) {
	foreach($winCombos as $combo) {
		[$a, $b, $c] = $combo;

/*prüft ob alle drei felder x oder o haben*/
if (
$board[$a] !== '' &&
$board[$a] === $board[$b] &&
$board[$a] === $board[$c]
	) {
	return $board[$a];} /*gibt gewinner*/
	}
	return null; /*kein gewinner :I*/
}

/*wenn reset gedrückt wurden dann spielfeld und punkte restet*/
if (isset($_GET['reset'])) {

	$board = array_fill(1, 9, '');

    $currentPlayer = 'X'; /* x beginnt*/

    $_SESSION['scoreX'] = 0;
    $_SESSION['scoreO'] = 0;
	$_SESSION['roundFinished'] = false;
}

else {
	/*aktuellen stand/spieler übernehmen*/
	$board = $_GET['board'] ?? array_fill(1, 9, '');
	$currentPlayer = $_GET['player'] ?? 'X';
	
	if (isset($_GET['cell'])) {
		
		$_SESSION['roundFinished'] = false;   
		
		$cell = (int)$_GET['cell']; /*spielfeld angeklickt*/ 
		
		if ($board[$cell] === '') {
			$board[$cell] = $currentPlayer; /*nur wenn feld leer ist darg geseezt werden*/
			
			$winner = checkWinner($board, $winCombos); /*prüft ob gewonnen*/
			
			
		/*wenn gewinnt score geht hoch und andere fängt an*/
		
			if ($winner === 'X') {
				if (!$_SESSION['roundFinished']) {
					
					$_SESSION['scoreX']++;
			
					$stmt = $mysqli->prepare("
						UPDATE spieler
						SET Score = Score + 1, Updated = NOW()
						WHERE Name = ?
					");

					$stmt->bind_param("s", $_SESSION['1Spieler']);
					$stmt->execute();
					$stmt->close();
			
					$board = array_fill(1, 9, '');
					$currentPlayer = 'O';
					/*wenn gewinnt score geht hoch und anderer fängt an*/
					
					$_SESSION['roundFinished'] = true;
				}
				
				header("Location: spiel-tictactoe.php");
				exit;
			}
		
			elseif ($winner === 'O') {
				if (!$_SESSION['roundFinished']) {
					
					$_SESSION['scoreO']++;
					$stmt = $mysqli->prepare("
						UPDATE spieler
						SET Score = Score + 1, Updated = NOW()
						WHERE Name = ?
						");
					$stmt->bind_param("s", $_SESSION['2Spieler']);
					$stmt->execute();
					$stmt->close();
			
					$board = array_fill(1, 9, '');
					$currentPlayer = 'X';
			
					$_SESSION['roundFinished'] = true;
				}
				
				header("Location: spiel-tictactoe.php");
				exit;
			}
		
			elseif (!in_array('', $board, true)) {
				$board = array_fill(1, 9, '');
				$currentPlayer = 'X';
			
				/*noch nicht gewonnen spieler wechsel*/
			}
		
			else {
				$currentPlayer = ($currentPlayer === 'X') ? 'O' : 'X';
			}
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head> 

<meta charset="UTF-8"> <!-- Zeichencodierung wegen umlauten etc.-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spiel Tic Tac Toe</title>
<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >

</head>

<body>

<form method="get">

<div class="container" >

	<div class="oben">

		<div class="player">
			<div class="symbol X">X</div>
			<div class="player-name">
			<?= htmlspecialchars($_SESSION['1Spieler'] ?? '') ?>  <!-- htmlspecialchars schützt vor HTML/Java -->
			</div>
		</div>	
	
		<div class="points">
			<div class="scores">
				<div id="p1" class="score"><?= $_SESSION['scoreX'] ?></div>
				<div id="p2" class="score"><?= $_SESSION['scoreO'] ?></div>
			</div>
			
			<button class="button" type="submit" name="reset" value="1">
				Reset <!-- Reset-Button: löst $_POST['reset'] aus -->
			</button>

		</div>

		<div class="player">
			<div class="symbol O">O</div>
			<div class="player-name">
				<?= htmlspecialchars($_SESSION['2Spieler'] ?? '') ?> <!-- htmlspecialchars schützt vor HTML/Java -->
			</div>
		</div>
	</div>

	<div class="board_container">

		<div class="board">

			<?php for ($i = 1; $i <= 9; $i++): ?>  <!-- Schleife erzeugt die 9 Spielfelde -->

				<button
			
					class="cell"
					type="submit"
					name="cell"
					value="<?= $i ?>"
				
					<?= $board[$i] !== '' ? 'disabled' : '' ?>> <!-- disabled = deaktiviert-->
					<?= $board[$i] ?>
				
				</button>
				
					<!-- speichert den Zustand der felder -->
				<input type="hidden" name="board[<?= $i ?>]" value="<?= $board[$i] ?>">

			<?php endfor; ?>

		</div>
	</div>
	
	<div>	
	
		Dran ist grade: 
		<input class="disabled_button" name="player" value="<?= $currentPlayer ?>"> 
		<!-- speichert den Zustand der spieler und zeigt gleichzeiig wer dran ist  -->
		
	</div>

</div  >
</form>

<!--name ändern auf der startseite --> 
<div class="submit-row"> 

    <form action="/tictactoe/index.php"  method="get">
	  
	<input type="hidden" name="1Spieler" value="<?= htmlspecialchars($_SESSION['1Spieler'] ?? '') ?>">
	<input type="hidden" name="2Spieler" value="<?= htmlspecialchars($_SESSION['2Spieler'] ?? '') ?>">

	   <button type="submit" class="zentral button">Name ändern</button>
    </form>
	<br>
	<a href="/tictactoe/highscore.php" class="zentral button" >Highscore</a>
</div>



</body>

</html> 