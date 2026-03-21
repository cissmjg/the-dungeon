<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/characterClassName.php';
require_once __DIR__ . '/../webio/playerCharacterClassId.php';
require_once __DIR__ . '/../webio/characterLevel.php';
require_once __DIR__ . '/../classes/characterDetails.php';
require_once __DIR__ . '/../classes/attributeMetadata.php';
require_once __DIR__ . '/../classes/characterSpellInfo.php';

require_once __DIR__ . '/constants/spellTypes.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);

$character_ids = getCharacterIds($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME]);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if (count($errors) > 0) {
	die(json_encode($errors));
}

$attribute_metadata = new AttributeMetadata($character_details);

$log[] = "SUCCESS|";
$log[] = "Input: " . $input[PLAYER_NAME] . ", " . $input[CHARACTER_NAME] . ", " . $input[CHARACTER_CLASS_NAME];
$input['spell_type_id_1'] = $character_ids['spell_type_id_1'];
$input['spell_type_id_2'] = $character_ids['spell_type_id_2'];

$log[] = "IDs: " . $character_ids['player_character_race_id'] . ", " . $character_ids['generic_race_id'] . ", " . $character_details->getCharacterIntelligence() . "/" . $character_details->getCharacterSuperIntelligence() . ", " . $input['spell_type_id_1'] . ", " . $input['spell_type_id_2'];

// Is spell processing necessary
if ($input['spell_type_id_1'] == NULL && $input['spell_type_id_2'] == NULL) {
	$new_class = promoteCharacterClass($pdo, $input, $errors);
	$log[] = "Promotion: " . $new_class[CHARACTER_NAME] . ", " . $new_class[PLAYER_CHARACTER_CLASS_ID] . ", " . $new_class[CHARACTER_CLASS_NAME] . ", " . $new_class[CHARACTER_LEVEL];

	emitOutput($errors, $log);
	exit;
}

// Base spell slots for current level
$before_character_spell_info = getCharacterSpellInfo($pdo, $input, $errors, $log);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

// Add 1 to character level
$new_class = promoteCharacterClass($pdo, $input, $errors);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$log[] = "Promotion: " . $new_class[CHARACTER_NAME] . ", " . $new_class[PLAYER_CHARACTER_CLASS_ID] . ", " . $new_class[CHARACTER_CLASS_NAME] . ", " . $new_class[CHARACTER_LEVEL];
$player_character_class_id = $new_class[PLAYER_CHARACTER_CLASS_ID];

// Base spell slots for new (promoted) level
$after_character_spell_info = getCharacterSpellInfo($pdo, $input, $errors, $log);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

// Spells for the new level ?
if ($after_character_spell_info->getBaseSlotCounts() == 0) {
	emitOutput($errors, $log);
	exit;
}

// Add any new spell levels
$new_spell_levels_available = getNewSpellLevelForCharacter($pdo, $input, $errors);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

// Allocate new spell levels for character
if (count($new_spell_levels_available) > 0) {
	
	for($i = 0; $i < count($new_spell_levels_available); $i++) {
		$spell_level_available = $new_spell_levels_available[$i];

		$spell_type = $spell_level_available['spell_type_id'];
		$spell_level = $spell_level_available['spell_level'];

		$cantrip_spell_type = $spell_type;
		if ($spell_level == 1) {
			// Healers don't have their own Cantrips, they copy a subset from Clerics
			if ($cantrip_spell_type == SPELL_TYPE_HEALER) {
				$cantrip_spell_type = SPELL_TYPE_CLERIC;
			}

			// Copy Cantrips 
			populateCantripsForPlayerCharacterClass($pdo, $player_character_class_id, $cantrip_spell_type, $errors);
			if (count($errors) > 0) {
				RestHeaderHelper::emitRestHeaders();
				die(json_encode($errors));
			}
		}
		
		$log[] = "Spell allocation: " . $spell_type . ", " . $player_character_class_id . ", " . $spell_level;

		// Spell_type Cleric, Druid, Healer, Shukenja
		if ($spell_type == SPELL_TYPE_CLERIC || $spell_type == SPELL_TYPE_DRUID || $spell_type == SPELL_TYPE_HEALER || $spell_type == SPELL_TYPE_SHUKENJA) {
			$log[] = "populateClericSpells: " . $player_character_class_id . ", " . $character_ids['generic_race_id'] . ", " . $spell_level;
			populateClericSpells($pdo, $player_character_class_id, $character_ids['generic_race_id'], $spell_level, $errors);
			if (count($errors) > 0) {
				RestHeaderHelper::emitRestHeaders();
				die(json_encode($errors));
			}
		}

		// Spell_type Healer, Magic-User, Illusionist, Wu Jen
		if ($spell_type == SPELL_TYPE_HEALER || $spell_type == SPELL_TYPE_MAGIC_USER || $spell_type == SPELL_TYPE_ILLUSIONIST || $spell_type == SPELL_TYPE_WU_JEN) {
			$number_of_slots = $attribute_metadata->getMaxNumberMUSpellSlots();
			$log[] = "allocateNewMUSpellPoolSlot: " . $player_character_class_id . ", " . $spell_level . ", " . $number_of_slots;
			allocateNewMUSpellPoolSlot($pdo, $player_character_class_id, $spell_level, $number_of_slots, $errors);
			if (count($errors) > 0) {
				RestHeaderHelper::emitRestHeaders();
				die(json_encode($errors));
			}
		}
	}
}

// Calculate how many and which level new spell slots are available.
$diff_base_slot_counts = CharacterSpellInfo::calculateSpellInfoDiff($before_character_spell_info, $after_character_spell_info);

$log[] = "After  Character Spell Info: " . $after_character_spell_info;
$log[] = "Before Character Spell Info: " . $before_character_spell_info;
$log[] = "Diff   Character Spell Info: " . $diff_base_slot_counts;

// Allocate slots in player_spell_slot based on the difference in base slots
allocateReadySlotsForPlayerCharacterClass($pdo, $player_character_class_id, $diff_base_slot_counts, $new_spell_levels_available, $character_details->getCharacterWisdom(), $attribute_metadata, $errors, $log);

emitOutput($errors, $log);

function emitOutput($errors, $log) {
	
	RestHeaderHelper::emitRestHeaders();
	if (count($errors) > 0) {
		echo json_encode($errors);
	} else {
		echo json_encode($log);
	}
}

function promoteCharacterClass(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL promoteCharacterClass(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $input[CHARACTER_CLASS_NAME], PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in promoteCharacterClass : " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function getNewSpellLevelForCharacter(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL getNewSpellLevelForCharacter(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $input[CHARACTER_CLASS_NAME], PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getNewSpellLevelForCharacter : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function allocateNewMUSpellPoolSlot(\PDO $pdo, $player_character_class_id, $spell_level, $number_of_slots, &$errors) {
	$sql_exec = "CALL allocateMUPoolSlots (:playerCharacterClassId, :spellLevel, :numberOfSlots)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':spellLevel', $spell_level, PDO::PARAM_INT);
	$statement->bindParam(':numberOfSlots', $number_of_slots);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateNewMUSpellPoolSlot : " . $e->getMessage();
	}
	
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function populateClericSpells(\PDO $pdo, $player_character_class_id, $player_race_id, $spell_level, &$errors) {
	$sql_exec = "CALL populateClericSpells (:playerCharacterClassId, :playerRace, :spellLevel)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':playerRace', $player_race_id, PDO::PARAM_INT);
	$statement->bindParam(':spellLevel', $spell_level, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in populateClericSpells : " . $e->getMessage();
	}
}

function populateCantripsForPlayerCharacterClass($pdo, $player_character_class_id, $spell_type, &$errors) {
	$sql_exec = "CALL populateCantripsForPlayerCharacterClass (:playerCharacterClassId, :spellTypeId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':spellTypeId', $spell_type, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in populateCantripsForPlayerCharacterClass : " . $e->getMessage();
	}
}

function getCharacterSpellInfo(\PDO $pdo, $input, &$errors, &$log) {
	$character_spell_info = new CharacterSpellInfo($input[PLAYER_NAME], $input[CHARACTER_NAME], $input[CHARACTER_CLASS_NAME], $input['spell_type_id_1'], $input['spell_type_id_2']);
	$character_spell_info->init($pdo, $errors, $log);
	if (count($errors) > 0) {
		die(json_encode($errors));
	}

	return $character_spell_info;
}

function allocateReadySlotsForPlayerCharacterClass(\PDO $pdo, $player_character_class_id, $diff_base_slot_counts, $new_spell_levels_available, $character_wisdom, \AttributeMetadata $attribute_metadata, &$errors, &$log) {
	$log_output = "allocateReadySlotsForPlayerCharacterClass: " . "count(new_spell_levels_available): " . count($new_spell_levels_available) . " wisdom: " . $character_wisdom;
	$log[] = $log_output;
	foreach($diff_base_slot_counts->getBaseSlotCounts() AS $spell_type_id => $base_slot_count) {
		allocateReadySlotsForSpellType($pdo, $player_character_class_id, $spell_type_id, $base_slot_count, $new_spell_levels_available, $character_wisdom, $attribute_metadata, $errors, $log);
	}
}

function allocateReadySlotsForSpellType($pdo, $player_character_class_id, $spell_type_id, $base_slot_count, $new_spell_levels_available, $character_wisdom, \AttributeMetadata $attribute_metadata, &$errors, &$log) {
	for ($slot_level = 1; $slot_level <= 9; $slot_level++) {
		$spell_slot_diff = $base_slot_count->getBaseSlotCount($slot_level);
		if ($spell_slot_diff > 0) {
			$log_output = "allocateReadySlotsForSpellType: " . "spell_slot_diff: " . $spell_slot_diff . " slot_level: " . $slot_level;
			$log[] = $log_output;
			for ($j = 0; $j < $spell_slot_diff; $j++) {
				allocateReadyBaseSpellSlot($pdo, $player_character_class_id, $slot_level, $spell_type_id, $errors, $log);
			}
		}
	}

	$log_output = "allocateReadySlotsForSpellType: " . "spell_type_id: " . $spell_type_id . " character_wisdom: " . $character_wisdom;
	$log[] = $log_output;
	// Cleric, Druid, Healer, Shukenja Wisdom bonus
	if ($spell_type_id == SPELL_TYPE_CLERIC || $spell_type_id == SPELL_TYPE_DRUID || $spell_type_id == SPELL_TYPE_HEALER || $spell_type_id == SPELL_TYPE_SHUKENJA) {
		$log_output = "allocateReadySlotsForSpellType: " . ": count(new_spell_levels_available): " . count($new_spell_levels_available);
		$log[] = $log_output;
		for ($i = 0; $i < count($new_spell_levels_available); $i++) {
			$spell_level_available = $new_spell_levels_available[$i];
			$new_spell_type = $spell_level_available['spell_type_id'];				
			if ($new_spell_type == $spell_type_id) {
				$new_spell_level = $spell_level_available['spell_level'];
				$bonus_slot_count = $attribute_metadata->calculateWisdomBonus($new_spell_level);
				$log_output = "allocateReadySlotsForSpellType: " . "new_spell_level: " . $new_spell_level . " bonus_slot_count: " . $bonus_slot_count;
				$log[] = $log_output;
				if ($bonus_slot_count > 0) {
					for($j = 0; $j < $bonus_slot_count; $j++) {
						allocateReadyWisdomSpellSlot($pdo, $player_character_class_id, $new_spell_level, $new_spell_type, $errors, $log);
					}
				}
			}
		}
	}
}

function allocateReadyBaseSpellSlot(\PDO $pdo, $player_character_class_id, $slot_level, $spell_type_id, &$errors, &$log) {
	$log_output = "allocateReadyBaseSpellSlot: " . "spell_type_id: " . $spell_type_id . " slot_level: " . $slot_level;
	$log[] = $log_output;
	$sql_exec = "CALL allocateReadyBaseSpellSlot (:playerCharacterClassId, :slotLevel, :spellTypeId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':slotLevel', $slot_level, PDO::PARAM_INT);
	$statement->bindParam(':spellTypeId', $spell_type_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateReadyBaseSpellSlot : " . $e->getMessage();
	}
}

function allocateReadyWisdomSpellSlot(\PDO $pdo, $player_character_class_id, $slot_level, $spell_type_id, &$errors, &$log) {
	$log_output = "allocateReadyWisdomSpellSlot: " . "spell_type_id: " . $spell_type_id . " slot_level: " . $slot_level;
	$log[] = $log_output;
	$sql_exec = "CALL allocateReadyWisdomSpellSlot (:playerCharacterClassId, :slotLevel, :spellTypeId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterClassId', $player_character_class_id, PDO::PARAM_INT);
	$statement->bindParam(':slotLevel', $slot_level, PDO::PARAM_INT);
	$statement->bindParam(':spellTypeId', $spell_type_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in allocateReadyWisdomSpellSlot : " . $e->getMessage();
	}
}

function getCharacterIds(\PDO $pdo, string $player_name, string $character_name, string $character_class_name) {
	$sql_exec = "CALL getCharacterIds(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	$statement->execute();

	return $statement->fetch(PDO::FETCH_ASSOC);
}
