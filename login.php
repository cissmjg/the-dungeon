<?php
$errors = [];

$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once 'CurlHelper.php';

$players = getPlayerList($pdo, $errors);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <style>
        input {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
        }

        input:focus {
            outline: none;
        }

        label {
            color: lightgray;
            font-size: 14px;
            vertical-align: sub;
        }

    </style>
    <script src="backButtonDisable.js" type="text/javascript"></script>
    <script type="text/javascript">
        function checkForm(loginForm) {
            let pwdField = document.getElementById("password");
            if (pwdField != null) {
                if (pwdField.value.length == 0) {
                    alert('Please enter a password');
                    return false;
                }
            }

            loginForm.submit();
        }
    </script>
</head>
<body style="background-color: whitesmoke; margin: 15px; font-family: sans-serif; font-size: 24px;">
    <?php
        $login_url = CurlHelper::buildUrl('characterActionRouter');
    ?>
    <div style="float: left;">
        <img src="Thumbs up.jpg" height="400">
    </div>
    <div style="float: left; background-color: white; height: 400px; padding: 15px;">
        <div style="text-align:center; padding-top: 37px; padding-bottom: 37px;">Login to the dungeon</div>
        <form action="<?= $login_url ?>" method="post">
            <input type="hidden" name="characterAction" value="login">
            <label for="playerName">Username</label>
            <select style="font-size: 24px; width: 100%; margin-top: 5px;" name="playerName" id="playerName">
            <?php
                foreach($players AS $player) {
                    echo '<option value="' . $player['player_name'] . '">' . $player['player_name'] . '</option>' . PHP_EOL;
                }
            ?>
            </select>
            <label for="password">Password</label>
            <input style="font-size: 24px; width: 97%; margin-top: 5px; border-top:none; border-left: none; border-right: none;" type="password" name="password" id="password">
            <div style="text-align:center; margin-top: 30px; ">
                <button style="font-size: 24px;" onclick="event.preventDefault(); checkForm(this.form);">Sign in</button>
            </div>
        </form>
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