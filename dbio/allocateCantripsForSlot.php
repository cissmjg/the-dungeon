<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/characterClassName.php';
require_once __DIR__ . '/../webio/spellCatalogId.php';
require_once __DIR__ . '/../webio/spellSlotId.php';
require_once __DIR__ . '/../webio/spellLevel.php';

const NUMBER_OF_CANTRIPS_PER_SPELL_LEVEL = 4;

$log = [];
$errors = [];
$input = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);
getSpellSlotId($errors, $input);
getSpellLevel($errors, $input);

$number_of_cantrips = (int)$input[SPELL_LEVEL] * (int)NUMBER_OF_CANTRIPS_PER_SPELL_LEVEL;

$log[] = "SUCCESS|";
$log[] = "Cantrip Count: " . $number_of_cantrips . "|";

for ($i = 0; $i < $number_of_cantrips; $i++) {
	allocateCantripForSlot($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME], $input[SPELL_SLOT_ID], $errors);
}

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($log);
}

function allocateCantripForSlot(\PDO $pdo, $player_name, $character_name, $character_class_name, $parent_spell_slot_id, &$errors) {
	$sql_exec = "CALL allocateCantripForSlot(:playerName, :characterName, :characterClassName, :parentSpellSlotId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	$statement->bindParam(':parentSpellSlotId', $parent_spell_slot_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateCantripForSlot : " . $e->getMessage();
	}
}