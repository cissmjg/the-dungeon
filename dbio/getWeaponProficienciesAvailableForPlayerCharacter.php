<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/textInput.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

$sql_verbs = [];
$sql_verbs[] = "CREATE";
$sql_verbs[] = "ALTER";
$sql_verbs[] = "DROP";
$sql_verbs[] = "TRUNCATE";
$sql_verbs[] = "RENAME ";
$sql_verbs[] = "COMMENT"; 
$sql_verbs[] = "INSERT";
$sql_verbs[] = "UPDATE";
$sql_verbs[] = "DELETE";
$sql_verbs[] = "COMMIT";
$sql_verbs[] = "ROLLBACK";
$sql_verbs[] = "SAVEPOINT";
$sql_verbs[] = "TRANSACTION";
$sql_verbs[] = "GRANT";
$sql_verbs[] = "REVOKE";
$sql_verbs[] = "SELECT";

getPlayerName($errors, $input);
getCharacterName($errors, $input);

// Get raw text from web page
getTextInput($errors, $input);

// Filter out SQL verbs
$filtered_text = str_replace($sql_verbs, "", $input[TEXT_INPUT]);

// Get Weapons matching the pattern
$available_weapon_proficiencies = getWeaponProficienciesAvailableForPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $filtered_text, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($available_weapon_proficiencies);
}
		
function getWeaponProficienciesAvailableForPlayerCharacter(\PDO $pdo, $player_name, $character_name, $input_text, &$errors) {
	$sql_exec = "CALL getWeaponProficienciesAvailableForPlayerCharacter(:playerName, :characterName, :weaponPatternName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':weaponPatternName', $input_text, PDO::PARAM_STR);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponProficienciesAvailableForPlayerCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
