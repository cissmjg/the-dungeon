<?php
$errors = [];

require_once __DIR__ . '/env.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once __DIR__ . '/helper/CurlHelper.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterAction.php';

$players = getPlayerList($pdo, $errors);
$login_url = CurlHelper::buildCharacterActionRouterUrl();

$page_title = 'Login';
$img_url = STARTING_URL . 'Thumbs up.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Cache-Control" content="no-store">

    <title><?= $page_title ?></title>

    <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="dnd-default.css">

    <script src="login.js" type="module"></script>
    <link href="login.css" rel="stylesheet">
</head>
<body style="background-color: whitesmoke; margin: 15px; font-family: sans-serif; font-size: 24px;">
    <div class="main">
        <div class="left">
            <img src="<?= $img_url ?>" height="400" width="190" title="Thumbs up">
        </div>
        <div class="right">
            <div style="text-align: center; padding-top: 74px; width:">Login to The Dungeon</div>
            <form action="<?= $login_url ?>" method="POST">
                <input type="hidden" name="<?= CHARACTER_ACTION ?>" value="login">
                <label for="playerName">Username</label>
                <select style="font-size: 24px; width: 100%; margin-top: 5px;" name="<?= PLAYER_NAME ?>" id="<?= PLAYER_NAME ?>">
                <?php
                    foreach($players AS $player) {
                        echo '<option value="' . $player['player_name'] . '">' . $player['player_name'] . '</option>' . PHP_EOL;
                    }
                ?>
                </select>
                <label for="password">Password</label>
                <input style="font-size: 24px; width: 95%; margin-top: 5px; border-top:none; border-left: none; border-right: none;" type="password" name="password" id="password">
                <div style="text-align:center; margin-top: 30px; ">
                    <button style="font-size: 24px;" onclick="event.preventDefault(); checkForm(this.form);">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
function getPlayerList(\PDO $pdo, &$errors) {
	$sql_exec = "CALL getPlayerList()";

	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in index.php : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>