<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

try {
$mysqli = new mysqli("localhost", "amelie", "123chickenWings", "tictactoe");
}

catch (Exception $e){
print $e->getMessage();
}

if (isset($_GET['1Spieler'])) {
    $_SESSION['1Spieler'] = $_GET['1Spieler'];
}

if (isset($_GET['2Spieler'])) {
    $_SESSION['2Spieler'] = $_GET['2Spieler'];
}

if (!isset($_SESSION['roundFinished'])) {
    $_SESSION['roundFinished'] = false;
}

if (!isset($_SESSION['currentPlayer'])) {
    $_SESSION['currentPlayer'] = 'X';
}

if (!isset($_SESSION['scoreX'])) {
    $_SESSION['scoreX'] = 0;
}

if (!isset($_SESSION['scoreO'])) {
    $_SESSION['scoreO'] = 0;
}

if (!isset($_SESSION['Matchcode']) && !isset($_GET['matchcode'])) {

//	print_r('hi');

        $erlaubtezeichen = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $Matchcode = '';

        for($i = 1; $i <= 8; $i++){
                $position = rand(0, strlen($erlaubtezeichen) - 1);
                $Matchcode .= $erlaubtezeichen[$position];
        }


        $_SESSION['Matchcode'] = $Matchcode;

}

elseif(isset($_GET['matchcode'])) {
//print_r('moinnnnnnnnnnnnnnn');
	$stmt = $mysqli->prepare("

		SELECT
			spieler1, spieler2, matchcode,
                        board1, board2, board3, board4, board5,
                        board6, board7, board8, board9,
                        aktuelldran, score1, score2

		FROM spiel
		WHERE matchcode = ?

	");

	$stmt->bind_param (
	"s",

	$_GET['matchcode']
	);

	$stmt->execute();
	$match = $stmt->get_result()->fetch_assoc();
	$stmt->close();

//	print_r($match);


	if (!$match){

		header("Location: match-code.php");
                exit;

	}

	$match['spieler2'] = $_SESSION['2Spieler'];

	$stmt = $mysqli->prepare("

                UPDATE spiel
		SET  spieler2 = ?
		WHERE matchcode = ?
");

	$stmt->bind_param (
        "ss",
	$_SESSION['2Spieler'],
        $_SESSION['Matchcode']
	);

        $stmt->execute();
//        $match = $stmt->get_result()->fetch_assoc();
        $stmt->close();


//	print_r($_SESSION);

	$_SESSION['Matchcode'] = $match['matchcode'];

	$_SESSION['1Spieler'] = $match['spieler1'];

	$_SESSION['scoreX'] = $match['score1'];

        $_SESSION['scoreO'] = $match['score2'];

	$_SESSION['currentPlayer'] = $match['aktuelldran'];

//print_r('match1');
}



try {
	if (isset($_GET['X'])){
	$_SESSION['d'] = 'X';
	}
}

catch (Exception $e){
	print $e->getMessage();
	die ($e->getMessage());
}



try {
	if (isset($_GET['O'])){
	$_SESSION['d'] = 'O';


	$stmt = $mysqli->prepare("
	    UPDATE spiel
	    SET spieler2 = ?
	    WHERE matchcode = ?
	");

	$stmt->bind_param(
	    "ss",
	    $_SESSION['2Spieler'],
	    $_SESSION['Matchcode']
	);

	$stmt->execute();
	$stmt->close();


	}
}

catch (Exception $e){
	print $e->getMessage();
	die ($e->getMessage());
}


//print_r($_SESSION);
//print_r($_GET);
try {

//	print_r('halo');
	if (isset($_GET['spiel_erstellt'])){
//	print_r('prüfe ob');
       		$stmt = $mysqli->prepare("
                	INSERT INTO spiel (
				spieler1, spieler2, matchcode,
				board1, board2, board3, board4, board5,
				board6, board7, board8, board9,
				aktuelldran, score1, score2
        	)
        	VALUES (?, ?, ?, '', '', '', '', '', '', '', '', '', 'X', 0, 0)

		RETURNING
			spieler1, spieler2, matchcode,
                        board1, board2, board3, board4, board5,
                        board6, board7, board8, board9,
                        aktuelldran, score1, score2

        	");

	        $stmt->bind_param (
		"sss",
	        $_SESSION['1Spieler'],
        	$_SESSION['2Spieler'],
        	$_SESSION['Matchcode']
    		);

        	$stmt->execute();
	        $match = $stmt->get_result()->fetch_assoc();
		$stmt->close();



        $_SESSION['Matchcode'] = $match['matchcode'];

        $_SESSION['1Spieler'] = $match['spieler1'];

        $_SESSION['scoreX'] = $match['score1'];

        $_SESSION['scoreO'] = $match['score2'];

        $_SESSION['currentPlayer'] = $match['aktuelldran'];


//print_r('match2');


		if ( $_SESSION['d'] == $match['aktuelldran'] ) {

//        		$board = $_GET['board'] ?? array_fill(1, 9, '');
       			$currentPlayer = $_SESSION['currentPlayer'];

   			if (isset($_GET['cell'])) {

                        	$_SESSION['roundFinished'] = false;
                        	$cell = (int)$_GET['cell'];

         			if ($board[$cell] === '') {

                           		$board[$cell] = $currentPlayer;


                                	$stmt = $mysqli->prepare ("
                                        	UPDATE spiel SET
                                        	        board1=?,board2=?,board3=?,
                                        	        board4=?,board5=?,board6=?,
                                        	        board7=?,board8=?,board9=?,
                                        	        aktuelldran=?
                                        	WHERE matchcode = ?
                                	");

                                	$stmt->bind_param(
                                	"ssssssssss",
                                	$board[1],$board[2],$board[3],
                                	$board[4],$board[5],$board[6],
                                	$board[7],$board[8],$board[9],
                                	$_SESSION['currentPlayer'],
                                	$_SESSION['Matchcode'],
                                	);

                                	$stmt->execute();
                                	$stmt->close();

//		print_r(rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr);
//		print_r($match);

				}

			}
		}
	}
}
catch (Exception $e){
print $e->getMessage();
die ($e->getMessage());
}


$stmt = $mysqli->prepare ("
	SELECT * FROM spiel
	WHERE matchcode = ?
");

$stmt->bind_param(
"s",
$_SESSION['Matchcode'],
);

$stmt->execute();
$match = $stmt->get_result()->fetch_assoc();
$stmt->close();


$_SESSION['1Spieler'] = $match['spieler1'];
$_SESSION['2Spieler'] = $match['spieler2'];


if ($match) {
//print_r('boardspeichern246');
	$_SESSION['1Spieler'] = $match['spieler1'];


	$_SESSION['scoreX'] = $match['score1'];
	$_SESSION['scoreO'] = $match['score2'];

	$_SESSION['currentPlayer'] = $match['aktuelldran'];

        $board = [
        1 => $match['board1'],
        2 => $match['board2'],
        3 => $match['board3'],
        4 => $match['board4'],
        5 => $match['board5'],
        6 => $match['board6'],
        7 => $match['board7'],
        8 => $match['board8'],
        9 => $match['board9']
    	];
}

else {

    $board = array_fill(1, 9, '');

}


//print_r('tessssssssssssssssssst');


function createPlayerIfNotExists($mysqli, $name) {
    if ($name === '') return;

    $stmt = $mysqli->prepare("SELECT 1 FROM spieler WHERE Name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $insert = $mysqli->prepare("
            INSERT INTO spieler (Name, Score, Created, Updated)
            VALUES (?, 0, NOW(), NOW())
        ");
        $insert->bind_param("s", $name);
        $insert->execute();
        $insert->close();
    }

    $stmt->close();
}


if (isset($_GET['1Spieler'])) {
    $_SESSION['1Spieler'] = $_GET['1Spieler'];
}
if (isset($_GET['2Spieler'])) {
    $_SESSION['2Spieler'] = $_GET['2Spieler'];
}

if (isset($_SESSION['1Spieler'])) {
    createPlayerIfNotExists($mysqli, $_SESSION['1Spieler']);
}
if (isset($_SESSION['2Spieler'])) {
    createPlayerIfNotExists($mysqli, $_SESSION['2Spieler']);
}


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
        )

	{
          return $board[$a];
        }

    }
    return null;
}


//print_r('mal gucken');

if ($_SESSION['d'] == $match['aktuelldran']) {

//print_r('klappt');

//	$board = $_GET['board'] ?? array_fill(1, 9, '');
	$currentPlayer = $_SESSION['currentPlayer'];

   if (isset($_GET['cell'])) {

		        $_SESSION['roundFinished'] = false;
		        $cell = (int)$_GET['cell'];

//print_r('normalfeld\r\n');
//print_r($board[$cell]);

		if ($board[$cell] === '') {

		            $board[$cell] = $currentPlayer;


				$stmt = $mysqli->prepare ("
					UPDATE spiel SET
						board1=?,board2=?,board3=?,
						board4=?,board5=?,board6=?,
						board7=?,board8=?,board9=?,
						aktuelldran=?
					WHERE matchcode = ?
				");

				$stmt->bind_param(
				"sssssssssss",
				$board[1],$board[2],$board[3],
				$board[4],$board[5],$board[6],
				$board[7],$board[8],$board[9],
				$_SESSION['currentPlayer'],
				$_SESSION['Matchcode'],
				);

				$stmt->execute();
				$stmt->close();


		$winner = checkWinner($board, $winCombos);

            	/* X gewinnt */
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

 				$stmt = $mysqli->prepare("
                        		UPDATE spiel
                        		SET score1 = score1 + 1 
					WHERE matchcode = ?
                		    ");
				$stmt->bind_param("s", $_SESSION['Matchcode']);
				$stmt->execute();
				$stmt->close();

	                    $_SESSION['roundFinished'] = true;
        	        }


			$_SESSION['currentPlayer'] = 'O';

    			$stmt = $mysqli->prepare("
        			UPDATE spiel
        			SET
            				board1='', board2='', board3='',
            				board4='', board5='', board6='',
            				board7='', board8='', board9='',
            				aktuelldran='O'
        			WHERE matchcode = ?
    				");

    			$stmt->bind_param(
        		"s",
        		$_SESSION['Matchcode']
    			);

    			$stmt->execute();
    			$stmt->close();


                	header("Location: match.php");
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

				$stmt = $mysqli->prepare("
                        		UPDATE spiel
                        		SET score2 = score2 + 1
					WHERE matchcode = ?
                	    	");
				$stmt->bind_param("s", $_SESSION['Matchcode']);
                        	$stmt->execute();
                        	$stmt->close();


                    	$_SESSION['roundFinished'] = true;
                	}


			$_SESSION['currentPlayer'] = 'X';

    			$stmt = $mysqli->prepare("
        		UPDATE spiel
        		SET
        		    board1='', board2='', board3='',
        		    board4='', board5='', board6='',
        		    board7='', board8='', board9='',
        		    aktuelldran='X'
        		WHERE matchcode = ?
    			");

   			$stmt->bind_param(
        		"s",
        		$_SESSION['Matchcode']
    			);

			$stmt->execute();
    			$stmt->close();

	      	        header("Location: match.php");
	   	        exit;
		}

            	elseif (!in_array('', $board, true)) {


		$_SESSION['currentPlayer'] =
        		($_SESSION['currentPlayer'] === 'X') ? 'O' : 'X';

	    	$stmt = $mysqli->prepare("
	        	UPDATE spiel
	        	SET
	        	    board1='', board2='', board3='',
	        	    board4='', board5='', board6='',
	        	    board7='', board8='', board9='',
	        	    aktuelldran = ?
	        	WHERE matchcode = ?
	    	");

	    	$stmt->bind_param(
	    	    "ss",
	    	    $_SESSION['currentPlayer'],
	    	    $_SESSION['Matchcode']
	    	);

	    	$stmt->execute();
	    	$stmt->close();

	    	header("Location: match.php");
	    	exit;


//	              	$board = array_fill(1, 9, '');
//                	$_SESSION['currentPlayer'] = 'X';
            	}


            	else {
                	$_SESSION['currentPlayer'] =
                    	($currentPlayer === 'X') ? 'O' : 'X';
            	}
	}
   }
//print_r('sp');
// speichere aktuellen spiel stand
$stmt = $mysqli->prepare("
		UPDATE spiel
		SET

		board1=?,board2=?,board3=?,
		board4=?,board5=?,board6=?,
		board7=?,board8=?,board9=?,
		aktuelldran = ?
		WHERE matchcode = ?
      ");

      $stmt->bind_param(
      "sssssssssss",
      $board[1],$board[2],$board[3],
      $board[4],$board[5],$board[6],
      $board[7],$board[8],$board[9],
      $_SESSION['currentPlayer'],
      $_SESSION['Matchcode'],
              );

      $stmt->execute();
      $stmt->close();
//print_r('speichern427');
}

else {


    //    $board = $_GET['board'] ?? array_fill(1, 9, '');
        $currentPlayer = $_SESSION['currentPlayer'];


}


$currentPlayer = $_SESSION['currentPlayer'];

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

<form method="get">

	<div class="container" >

		<div class="oben">

			<div class="player">
				<div class="symbol X">X</div>
				<div class="player-name">
				<?= htmlspecialchars($match['spieler1']) ?>  <!-- htmlspecialchars schützt vor HTML/Java -->
				</div>
			</div>	
	
			<div class="points">
				<div class="scores">
					<div id="p1" class="score"><?= $_SESSION['scoreX'] ?></div>
					<div class="senkrechte-linie"></div>
					<div id="p2" class="score"><?= $_SESSION['scoreO'] ?></div>
				</div>

			</div>

			<div class="player">
				<div class="symbol O">O</div>
				<div class="player-name">

					<?= $match['spieler2'] ? htmlspecialchars($match['spieler2']) :  'Auf Spieler 2<br> warten' ?>
 
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

		</div>

	</div>
</form>

<br>

<div class="matchcode">

	Der Matchcode ist:
	<input class="code" name="code" value="<?= $_SESSION['Matchcode'] ?? '' ?>">

</div>

<form action="" method="get" class="submit-row">

    <button type="submit" class="zentral button" name="aktualisieren" value="1">Aktualisieren</button>

</form>


<div class="submit-row">

	<br>
	<a href="/highscore.php" class="zentral button" >Highscore</a>

</div>



</body>
</html>

