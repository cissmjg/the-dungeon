<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once 'characterClassName.php';
require_once 'spellLevel.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once 'emptySpellSlot.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);
getSpellLevel($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];
$character_class_name = $input[CHARACTER_CLASS_NAME];
$spell_level = $input['spellLevel'];
if ($spell_level == -1) {
	$spell_level = 0;
}

$result = getSpellPoolForPlayerCharacter($pdo, $player_name, $character_name, $character_class_name, $spell_level, $errors);

getOptionalBooleanParameter($errors, $input, __FILE__, 'removeEmpty', false);

if ($input['removeEmpty']) {
	$filtered_spell_pool = [];
	foreach($result AS $spell_pool_entry) {
		if ($spell_pool_entry['spell_name'] != EMPTY_SLOT_SPELL_NAME) {
			$filtered_spell_pool[] = $spell_pool_entry;
		}
	}
	$result = $filtered_spell_pool;
}

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($result);
}

function getSpellPoolForPlayerCharacter(\PDO $pdo, $player_name, $character_name, $character_class_name, $spell_level, &$errors) {
	$sql_exec = "CALL getSpellPoolForPlayerCharacter(:playerName, :characterName, :characterClassName, :spellLevel)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	$statement->bindParam(':spellLevel', $spell_level, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getSpellPoolForPlayerCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
