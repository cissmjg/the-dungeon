<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerCharacterClassId.php';
require_once __DIR__ . '/../webio/spellSlotLevel.php';
require_once __DIR__ . '/../webio/spellTypeId.php';

$log = [];
$errors = [];
$input = [];

getPlayerCharacterClassId($errors, $input);
getSpellSlotLevel($errors, $input);
getSpellTypeId($errors, $input);

$log[] = "SUCCESS|";
allocateExtraSlot($pdo, $input[PLAYER_CHARACTER_CLASS_ID], $input[SPELL_SLOT_LEVEL], $input[SPELL_TYPE_ID], $errors);

RestHeaderHelper::emitRestHeaders();

if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($log);
}

function allocateExtraSlot(\PDO $pdo, $player_character_class_id, $slot_level, $spell_type_id, &$errors) {
	$sql_exec = "CALL allocateReadyExtraSpellSlot(:playerCharacterClassId, :slotLevel, :spellTypeId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':slotLevel', $slot_level, PDO::PARAM_INT);
	$statement->bindParam(':spellTypeId', $spell_type_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateExtraSlot : " . $e->getMessage();
	}
}