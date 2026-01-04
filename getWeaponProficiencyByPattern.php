<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'textInput.php';

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

// Get raw text from web page
getTextInput($errors, $input);

// Filter out SQL verbs
$filtered_text = str_replace($sql_verbs, "", $input[TEXT_INPUT]);

// Get Weapons matching the pattern
$weapons = getWeaponProficiencyByPattern($pdo, $filtered_text, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($weapons);
}
		
function getWeaponProficiencyByPattern(\PDO $pdo, $input_text, &$errors) {
	$sql_exec = "CALL getWeaponProficiencyByPattern(:weaponPatternName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':weaponPatternName', $input_text, PDO::PARAM_STR);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponProficiencyByPattern : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
