<?php

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/helper/CurlHelper.php';

$errors = [];
$input = [];

getPlayerName($errors, $input);

$spell_casting_classes = getAllSpellCastingClasses($pdo, $errors);

function getAllSpellCastingClasses(\PDO $pdo, $errors) {
    $sql_exec = "CALL getAllSpellCastingClasses()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getAllSpellCastingClasses : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
    <title>Home</title>
</head>
<body>
    <h3>Actions</h3>
    <ol>
    <?php
        $character_list_url = CurlHelper::buildUrl('characterList');
        $character_list_url = CurlHelper::addParameter($character_list_url, PLAYER_NAME, $input[PLAYER_NAME]);
        echo '<li><div><a href="' . $character_list_url . '">Character List for ' . $input[PLAYER_NAME] . '</a></div></li>'. PHP_EOL;
        $spell_count_url = CurlHelper::buildUrl('characterClassSpellCount');
    ?>
    <li><div>Character spell count by character class and level<br/>
    <form id="spell-count" name="spell-count" action="<?php echo $spell_count_url ?>" method="post">
    <input type="hidden" id="playerName" name="playerName" value="<?php echo $input[PLAYER_NAME] ?>">
    <select id="characterClassName" name="characterClassName">
    <?php
        foreach($spell_casting_classes AS $spell_casting_class) {
            echo '<option value="' . $spell_casting_class['character_class_name'] . '">' . $spell_casting_class['character_class_name'] . '</option>' . PHP_EOL;
        }
    ?>
    </select>
    <button type="submit">Go</button>
    </form>
    </div></li>
    </ol>
</body>
</html>