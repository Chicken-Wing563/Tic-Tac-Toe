
<?php

session_start();

if (!isset($_SESSION['scoreX'])) $_SESSION['scoreX'] = 0;
if (!isset($_SESSION['scoreO'])) $_SESSION['scoreO'] = 0;

$winCombos = [
    [1,2,3], [4,5,6], [7,8,9],
    [1,4,7], [2,5,8], [3,6,9],
    [1,5,9], [7,5,3]
];

function checkWinner($board, $winCombos) {
	foreach($winCombos as $combo) {
		[$a, $b, $c] = $combo;

if (
$board[$a] !== '' &&
$board[$a] === $board[$b] &&
$board[$a] === $board[$c]
	) {
	return $board[$a];}
	}
	return null;
}

if (isset($_POST['reset'])) {

	$board = array_fill(1, 9, '');

    $currentPlayer = 'X';

    $_SESSION['scoreX'] = 0;
    $_SESSION['scoreO'] = 0;
}


else {
	
	$board = $_POST['board'] ?? array_fill(1, 9, '');
	$currentPlayer = $_POST['player'] ?? 'X';
	
	if (isset($_POST['cell'])) {
		$cell = (int)$_POST['cell'];
		
		if ($board[$cell] === '') {
			$board[$cell] = $currentPlayer;
			
			$winner = checkWinner($board, $winCombos);
			
			if ($winner === 'X') {
				$_SESSION['scoreX']++;
				$board = array_fill(1, 9, '');
				$currentPlayer = 'X';
				
				
				} elseif ($winner === 'O') {
				$_SESSION['scoreO']++;
				$board = array_fill(1, 9, '');
				$currentPlayer = 'X';
				
				} elseif (!in_array('', $board, true)) {
					$board = array_fill(1, 9, '');
					$currentPlayer = 'X';
					
					} else {
						$currentPlayer = ($currentPlayer === 'X') ? 'O' : 'X';
			}
		}
	}
}


?>

<!DOCTYPE html>
<html>

<head> 

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spiel Tic Tac Toe</title>
<link rel="stylesheet" type="text/css" href="stylesheets/style.css" >

</head>

<body>
<form method="post">

<div class="container" >

	<div class="oben">

		<div class="player">
			<div class="symbol X">X</div>
			<div class="player-name">
			<?= htmlspecialchars($_GET['1Spieler'] ?? '') ?>
			</div>
		</div>	
	
		<div class="points">
			<div class="scores">
				<div id="p1" class="score"><?= $_SESSION['scoreX'] ?></div>
				<div id="p2" class="score"><?= $_SESSION['scoreO'] ?></div>
			</div>
			
			<button class="button" type="submit" name="reset" value="1">
				Reset
			</button>

		</div>

		<div class="player">
			<div class="symbol O">O</div>
			<div class="player-name">
				<?= htmlspecialchars($_GET['2Spieler'] ?? '') ?>
			</div>
		</div>
	</div>

	<div class="board_container">

		<div class="board">

			<?php for ($i = 1; $i <= 9; $i++): ?>

				<button
			
					class="cell"
					type="submit"
					name="cell"
					value="<?= $i ?>"
				
					<?= $board[$i] !== '' ? 'disabled' : '' ?>>
					<?= $board[$i] ?>
				
				</button>

				<input type="hidden" name="board[<?= $i ?>]" value="<?= $board[$i] ?>">

			<?php endfor; ?>

		</div>
	</div>
	
</div>
	<input type="hidden" name="player" value="<?= $currentPlayer ?>">


<div class="submit-row">
    <a href="index.html" class="zentral button">
        Namen ändern
    </a>
</div>




</form>
</body>

</html> 