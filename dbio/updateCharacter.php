<?php

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/pageAction.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/constants/characterAttributes.php';
require_once __DIR__ . '/constants/armorBulkFactor.php';
require_once __DIR__ . '/constants/spellTypes.php';

require_once __DIR__ . '/../classes/characterDetails.php';
require_once __DIR__ . '/../classes/attributeMetadata.php';
require_once __DIR__ . '/../classes/accountClassSummary.php';
require_once __DIR__ . '/../classes/baseSlotCount.php';
require_once __DIR__ . '/../classes/characterSpellInfo.php';

$errors = [];
$input = [];

//Required Character Data
getPlayerName($errors, $input);
getCharacterName($errors, $input);

filterAndSanitizeStrength($input, $errors);
filterAndSanitizeSuperStrength($input, $errors);
filterAndSanitizeIntelligence($input, $errors);
filterAndSanitizeSuperIntelligence($input, $errors);
filterAndSanitize18StarIntelligence($input, $errors);
filterAndSanitizeWisdom($input, $errors);
filterAndSanitizeSuperWisdom($input, $errors);
filterAndSanitizeDexterity($input, $errors);
filterAndSanitizeSuperDexterity($input, $errors);
filterAndSanitize18StarDexterity($input, $errors);
filterAndSanitizeConstitution($input, $errors);
filterAndSanitizeSuperConstitution($input, $errors);
filterAndSanitizeCharisma($input, $errors);
filterAndSanitizeSuperCharisma($input, $errors);
filterAndSanitizeComeliness($input, $errors);
filterAndSanitizeArmorClass($input, $errors);
filterAndSanitizeArmorBulkFactor($input, $errors);
filterAndSanitizeHitPoints($input, $errors);
filterAndSanitizeRaceId($input, $errors);
filterAndSanitizeGender($input, $errors);
filterAndSanitizeCharacterClass1($input, $errors);
filterAndSanitizeCharacterClass2($input, $errors);
filterAndSanitizeCharacterClass3($input, $errors);

//Optional Character Data
filterAndSanitizeMovement($input, $errors);
filterAndSanitizeAlignment($input, $errors);
filterAndSanitizeReligion($input, $errors);
filterAndSanitizeDeity($input, $errors);
filterAndSanitizeHometown($input, $errors);
filterAndSanitizeHitDie($input, $errors);
filterAndSanitizeAge($input, $errors);
filterAndSanitizeApparentAge($input, $errors);
filterAndSanitizeUnnaturalAge($input, $errors);
filterAndSanitizeSocialClass($input, $errors);
filterAndSanitizeHeight($input, $errors);
filterAndSanitizeWeight($input, $errors);
filterAndSanitizeHair($input, $errors);
filterAndSanitizeEyes($input, $errors);
filterAndSanitizeSiblings($input, $errors);
filterAndSanitizeParentsMarried($input, $errors);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];

$upgrade_to_19_dexterity = ($input[CHARACTER_DEXTERITY] == 19 && strcasecmp($input[CHARACTER_HAS_18_STAR_DEXTERITY], "yes") == 0);
$upgrade_to_19_intelligence = ($input[CHARACTER_INTELLIGENCE] == 19 && strcasecmp($input[CHARACTER_HAS_18_STAR_INTELLIGENCE], "yes") == 0);

$prev_character_details = new CharacterDetails();
$prev_character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$prev_attribute_metadata = new AttributeMetadata($prev_character_details);

updateBaseCharacter($pdo, $input, $errors);
if (count($errors) > 0) {
	$errors[] = "Database Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Failure on updateBaseCharacter';
	error_log(implode(' ', $errors));
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$current_character_details = new CharacterDetails();
$current_character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$current_attribute_metadata = new AttributeMetadata($current_character_details);

$slot_max_count_diff = $current_attribute_metadata->getMaxNumberMUSpellSlots() - $prev_attribute_metadata->getMaxNumberMUSpellSlots();
if ($upgrade_to_19_intelligence && $slot_max_count_diff > 0) {
	$log_output = "Player Name: $player_name Character Name: $character_name Spell diff : $slot_max_count_diff";
	error_log($log_output);
	createNewSlotsFor19Intelligence($pdo, $player_name, $character_name, $current_character_details, $slot_max_count_diff, $errors);
}

updateCharacterClasses($pdo, $input, $errors);
if (count($errors) > 0) {
	$errors[] = "Database Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Failure on updateCharacterClasses';
	error_log(implode(' ', $errors));
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

updateOptionalCharacterData($pdo, $input, $errors);
if (count($errors) > 0) {
	$errors[] = "Database Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Failure on updateOptionalCharacterData';
	error_log(implode(' ', $errors));
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$url_crudCharacter = CurlHelper::buildUrl('crudCharacter');
$url_crudCharacter = CurlHelper::addParameter($url_crudCharacter, PLAYER_NAME, $input[PLAYER_NAME]);
$url_crudCharacter = CurlHelper::addParameter($url_crudCharacter, CHARACTER_NAME, $input[CHARACTER_NAME]);
$url_crudCharacter = CurlHelper::addParameter($url_crudCharacter, PAGE_ACTION, 'update');
$url_crudCharacter = CurlHelper::addParameter($url_crudCharacter, 'updateTimestamp', time());

$location_header = CurlHelper::buildLocationHeader($url_crudCharacter);

header($location_header);
exit;

function updateBaseCharacter(\PDO $pdo, $input, &$errors) {

	$null_value = NULL;
	$true_value = true;
	$false_value = false;
	$sql_exec = "CALL updateBaseCharacter(:playerName, :characterName, :characterStrength, :characterSuperStrength, :characterIntelligence, :characterSuperIntelligence, :characterHas18StarIntelligence, :characterWisdom, :characterSuperWisdom, :characterDexterity, :characterSuperDexterity, :characterHas18StarDexterity, :characterConstitution, :characterSuperConstitution, :characterCharisma, :characterSuperCharisma, :characterComeliness, :raceId, :armorClass, :armorBulkFactor, :hitPoints, :genderIn)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterStrength', $input[CHARACTER_STRENGTH], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_STRENGTH])) {
		$statement->bindParam(':characterSuperStrength', $input[CHARACTER_SUPER_STRENGTH], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperStrength', $null_value, PDO::PARAM_NULL);
	}
	$statement->bindParam(':characterIntelligence', $input[CHARACTER_INTELLIGENCE], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_INTELLIGENCE])) {
		$statement->bindParam(':characterSuperIntelligence', $input[CHARACTER_SUPER_INTELLIGENCE], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperIntelligence', $null_value, PDO::PARAM_NULL);
	}

	$has_18_star_intelligence = (strcasecmp($input[CHARACTER_HAS_18_STAR_INTELLIGENCE], "yes") == 0);
	if ($input[CHARACTER_INTELLIGENCE] == 19 && $has_18_star_intelligence) {
		$statement->bindParam(':characterHas18StarIntelligence', $false_value, PDO::PARAM_BOOL);
	} else {
		if ($has_18_star_intelligence) {
			$statement->bindParam(':characterHas18StarIntelligence', $true_value, PDO::PARAM_BOOL);
		} else {
			$statement->bindParam(':characterHas18StarIntelligence', $false_value, PDO::PARAM_BOOL);
		}
	}

	$statement->bindParam(':characterWisdom', $input[CHARACTER_WISDOM], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_WISDOM])) {
		$statement->bindParam(':characterSuperWisdom', $input[CHARACTER_SUPER_WISDOM], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperWisdom', $null_value, PDO::PARAM_NULL);
	}
	$statement->bindParam(':characterDexterity', $input[CHARACTER_DEXTERITY], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_DEXTERITY])) {
		$statement->bindParam(':characterSuperDexterity', $input[CHARACTER_SUPER_DEXTERITY], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperDexterity', $null_value, PDO::PARAM_NULL);
	}

	$has_18_star_dexterity = (strcasecmp($input[CHARACTER_HAS_18_STAR_DEXTERITY], "yes") == 0);
	if ($input[CHARACTER_DEXTERITY] == 19 && $has_18_star_dexterity){
		$statement->bindParam(':characterHas18StarDexterity', $false_value, PDO::PARAM_BOOL);
	} else {
		if ($has_18_star_dexterity) {
			$statement->bindParam(':characterHas18StarDexterity', $true_value, PDO::PARAM_BOOL);
		} else {
			$statement->bindParam(':characterHas18StarDexterity', $false_value, PDO::PARAM_BOOL);
		}
	}

	$statement->bindParam(':characterConstitution', $input[CHARACTER_CONSTITUTION], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_CONSTITUTION])) {
		$statement->bindParam(':characterSuperConstitution', $input[CHARACTER_SUPER_CONSTITUTION], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperConstitution', $null_value, PDO::PARAM_NULL);
	}
	$statement->bindParam(':characterCharisma', $input[CHARACTER_CHARISMA], PDO::PARAM_INT);
	if (!empty($input[CHARACTER_SUPER_CHARISMA])) {
		$statement->bindParam(':characterSuperCharisma', $input[CHARACTER_SUPER_CHARISMA], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':characterSuperCharisma', $null_value, PDO::PARAM_NULL);
	}
	$statement->bindParam(':characterComeliness', $input[CHARACTER_COMELINESS], PDO::PARAM_INT);
	$statement->bindParam(':raceId', $input[CHARACTER_RACE_ID], PDO::PARAM_INT);
	$statement->bindParam(':armorClass', $input[CHARACTER_ARMOR_CLASS], PDO::PARAM_INT);
	$statement->bindParam(':armorBulkFactor', $input[CHARACTER_ARMOR_BULK_FACTOR], PDO::PARAM_INT);
	$statement->bindParam(':hitPoints', $input[CHARACTER_HIT_POINTS], PDO::PARAM_INT);
	$statement->bindParam(':genderIn', $input[CHARACTER_GENDER], PDO::PARAM_STR);
	
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateBaseCharacter : " . $e->getMessage();
	}
	
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function updateCharacterClasses(\PDO $pdo, $input, &$errors) {
	if (isset($input['characterClass1Name'])) {
		updateCharacterClass($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input['characterClass1Name'], $input['characterClass1XP'], $errors);
	}
	
	if (isset($input['characterClass2Name'])) {
		updateCharacterClass($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input['characterClass2Name'], $input['characterClass2XP'], $errors);
	}
	
	if (isset($input['characterClass3Name'])) {
		updateCharacterClass($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input['characterClass3Name'], $input['characterClass3XP'], $errors);
	}
}

function updateCharacterClass(\PDO $pdo, $player_name, $character_name, $character_class_name, $num_XP, &$errors) {
	$sql_exec = "CALL updateXPForPlayerCharacterClass(:playerName, :characterName, :characterClassName, :numXP)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);	
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	$statement->bindParam(':numXP', $num_XP, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateXPForPlayerCharacterClass for Character Class Name [" . $character_class_name . "]: " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function updateOptionalCharacterData(\PDO $pdo, $input, &$errors) {
	$null_value = NULL;
	$sql_exec = "CALL updateOptionalCharacterData(:playerName, :characterName, :movementIn, :alignmentIn, :religionIn, :deityIn, :hometownIn, :hit_dieIn, :ageIn, :apparent_ageIn, :unnatural_ageIn, :social_classIn, :heightIn, :weightIn, :hairIn, :eyesIn, :siblingsIn, :parents_marriedIn)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);

	if (!empty($input[CHARACTER_MOVEMENT])) {
		$statement->bindParam(':movementIn', $input[CHARACTER_MOVEMENT], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':movementIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_ALIGNMENT])) {
		$statement->bindParam(':alignmentIn', $input[CHARACTER_ALIGNMENT], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':alignmentIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_RELIGION])) {
		$statement->bindParam(':religionIn', $input[CHARACTER_RELIGION], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':religionIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_DEITY])) {
		$statement->bindParam(':deityIn', $input[CHARACTER_DEITY], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':deityIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_HOMETOWN])) {
		$statement->bindParam(':hometownIn', $input[CHARACTER_HOMETOWN], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':hometownIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_HIT_DIE])) {
		$statement->bindParam(':hit_dieIn', $input[CHARACTER_HIT_DIE], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':hit_dieIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_AGE])) {
		$statement->bindParam(':ageIn', $input[CHARACTER_AGE], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':ageIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_APPARENT_AGE])) {
		$statement->bindParam(':apparent_ageIn', $input[CHARACTER_APPARENT_AGE], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':apparent_ageIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_UNNATURAL_AGE])) {
		$statement->bindParam(':unnatural_ageIn', $input[CHARACTER_UNNATURAL_AGE], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':unnatural_ageIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_SOCIAL_CLASS])) {
		$statement->bindParam(':social_classIn', $input[CHARACTER_SOCIAL_CLASS], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':social_classIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_HEIGHT])) {
		$statement->bindParam(':heightIn', $input[CHARACTER_HEIGHT], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':heightIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_WEIGHT])) {
		$statement->bindParam(':weightIn', $input[CHARACTER_WEIGHT], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':weightIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_HAIR])) {
		$statement->bindParam(':hairIn', $input[CHARACTER_HAIR], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':hairIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_EYES])) {
		$statement->bindParam(':eyesIn', $input[CHARACTER_EYES], PDO::PARAM_STR);
	} else {
		$statement->bindParam(':eyesIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_SIBLINGS])) {
		$statement->bindParam(':siblingsIn', $input[CHARACTER_SIBLINGS], PDO::PARAM_INT);
	} else {
		$statement->bindParam(':siblingsIn', $null_value, PDO::PARAM_NULL);
	}

	if (!empty($input[CHARACTER_PARENTS_MARRIED])) {
		$statement->bindParam(':parents_marriedIn', $input[CHARACTER_PARENTS_MARRIED], PDO::PARAM_BOOL);
	} else {
		$statement->bindParam(':parents_marriedIn', $null_value, PDO::PARAM_NULL);
	}
	
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateOptionalCharacterData : " . $e->getMessage();
	}
}

function createNewSlotsFor19Intelligence(PDO $pdo, $player_name, $character_name, CharacterDetails $character_details, $slot_max_count_diff, &$errors) {
	foreach($character_details->getCharacterClasses() AS $character_class) {
		$spell_type_id_1 = 0;
		$spell_type_id_2 = 0;
		
		if (!empty($character_class->getSpellcasterType1())) {
			$spell_type_id_1 = $character_class->getSpellcasterType1()->getSpellTypeId();
		}

		if (!empty($character_class->getSpellcasterType2())) {
			$spell_type_id_2 = $character_class->getSpellcasterType2()->getSpellTypeId();
		}

		$has_arcane_spell_book = hasArcaneSpellBook($spell_type_id_1, $spell_type_id_2);
		$log_output = "Spell Type 1: [$spell_type_id_1] Spell Type 2: [$spell_type_id_2] Arcane Spellbook: [" . var_export($has_arcane_spell_book, true) . ']';
		error_log($log_output);

		if ($has_arcane_spell_book) {
			$class_name = $character_class->getClassName();
			$log_output = "Class name: [$class_name] Spell Type 1: [$spell_type_id_1] Spell Type 2: [$spell_type_id_2]";
			error_log($log_output);
			checkClassForArcaneSpellSlots($pdo, $player_name, $character_name, $character_class, $slot_max_count_diff, $spell_type_id_1, $spell_type_id_2, $errors);
		}
	}
}

function checkClassForArcaneSpellSlots(PDO $pdo, $player_name, $character_name, AccountClassSummary $character_class, $slot_max_count_diff, $spell_type_id_1, $spell_type_id_2, &$errors) {
	$log = [];
	$character_spell_info = new CharacterSpellInfo($player_name, $character_name, $character_class->getClassName(), $spell_type_id_1, $spell_type_id_2);
    $character_spell_info->init($pdo, $errors, $log);
    foreach($character_spell_info->getBaseSlotCounts() AS $spell_type_id => $base_slot_count) {
		if ($spell_type_id == SPELL_TYPE_MAGIC_USER || $spell_type_id == SPELL_TYPE_ILLUSIONIST || $spell_type_id == SPELL_TYPE_WU_JEN || $spell_type_id == SPELL_TYPE_HEALER) {
			$class_id = $character_class->getClassId();
			$log_output = "Class ID: $class_id Spell Type: $spell_type_id Base Slot Count: $base_slot_count";
			error_log($log_output);
			allocate19IntelligenceMUSpellSlots($pdo, $character_class, $base_slot_count, $slot_max_count_diff, $errors);
		}
	}
}

function allocate19IntelligenceMUSpellSlots(PDO $pdo, AccountClassSummary $character_class, BaseSlotCount $base_slot_count, $slot_max_count_diff, &$errors) {
	for ($slot_level = 1; $slot_level <= 9; $slot_level++) {
		$spell_slot_19_int = $base_slot_count->getBaseSlotCount($slot_level);
		if ($spell_slot_19_int > 0) {
			$player_character_class_id = $character_class->getPlayerCharacterClassId();
			$log_output = "Player Character Class ID: $player_character_class_id Slot Level: $slot_level Count: $slot_max_count_diff";
			error_log($log_output);
			allocateNewArcaneSpellPoolSlot($pdo, $player_character_class_id, $slot_level, $slot_max_count_diff, $errors);
			if (count($errors) > 0) {
				die(json_encode($errors));
			}
		}
	}
}

function allocateNewArcaneSpellPoolSlot(\PDO $pdo, $player_character_class_id, $spell_level, $number_of_slots, &$errors) {
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

function hasArcaneSpellBook($spell_type_1, $spell_type_2) {
	
	$spell_type_1_arcane = ($spell_type_1 == SPELL_TYPE_MAGIC_USER || $spell_type_1 == SPELL_TYPE_ILLUSIONIST || $spell_type_1 == SPELL_TYPE_WU_JEN || $spell_type_1 == SPELL_TYPE_HEALER);
	$spell_type_2_arcane = ($spell_type_2 == SPELL_TYPE_MAGIC_USER || $spell_type_2 == SPELL_TYPE_ILLUSIONIST || $spell_type_2 == SPELL_TYPE_WU_JEN || $spell_type_2 == SPELL_TYPE_HEALER);
	
	return $spell_type_1_arcane || $spell_type_2_arcane;
}

function filterAndSanitizeStrength(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_STRENGTH);
}

function filterAndSanitizeSuperStrength(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_STRENGTH);
}

function filterAndSanitizeIntelligence(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_INTELLIGENCE);
}

function filterAndSanitizeSuperIntelligence(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_INTELLIGENCE);
}

function filterAndSanitize18StarIntelligence(&$input, &$errors) {
	filterAndSantizeStringFormField($input, $errors, CHARACTER_HAS_18_STAR_INTELLIGENCE);
}

function filterAndSanitizeWisdom(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_WISDOM);
}

function filterAndSanitizeSuperWisdom(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_WISDOM);
}

function filterAndSanitizeDexterity(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_DEXTERITY);
}

function filterAndSanitizeSuperDexterity(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_DEXTERITY);
}

function filterAndSanitize18StarDexterity(&$input, &$errors) {
	filterAndSantizeStringFormField($input, $errors, CHARACTER_HAS_18_STAR_DEXTERITY);
}

function filterAndSanitizeConstitution(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_CONSTITUTION);
}

function filterAndSanitizeSuperConstitution(&$input, $errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_CONSTITUTION);
}

function filterAndSanitizeCharisma(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_CHARISMA);
}

function filterAndSanitizeSuperCharisma(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_CHARISMA);
}

function filterAndSanitizeComeliness(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_COMELINESS);
}

function filterAndSanitizeArmorClass(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_ARMOR_CLASS);
}

function filterAndSanitizeArmorBulkFactor(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_ARMOR_BULK_FACTOR);
	if (empty($input[CHARACTER_ARMOR_BULK_FACTOR])) {
		$input[CHARACTER_ARMOR_BULK_FACTOR] = BULKY_FULL;
	}
}

function filterAndSanitizeHitPoints(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_HIT_POINTS);
}

function filterAndSanitizeRaceId(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_RACE_ID);
}

function filterAndSanitizeGender(&$input, &$errors) {
	filterAndSantizeStringFormField($input, $errors, CHARACTER_GENDER);
}

function filterAndSanitizeCharacterClass1(&$input, &$errors) {
	filterAndSantizeStringFormField($input, $errors, 'characterClass1Name');
	filterAndSantizeIntegerFormField($input, $errors, 'characterClass1XP');
}

function filterAndSanitizeCharacterClass2(&$input, &$errors) {
    filterAndSantizeOptionalStringFormField($input, $errors, 'characterClass2Name');
	filterAndSantizeOptionalIntegerFormField($input, $errors, 'characterClass2XP');
}

function filterAndSanitizeCharacterClass3(&$input, &$errors) {
    filterAndSantizeOptionalStringFormField($input, $errors, 'characterClass3Name');
	filterAndSantizeOptionalIntegerFormField($input, $errors, 'characterClass3XP');
}

function filterAndSanitizeMovement(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_MOVEMENT);
}

function filterAndSanitizeAlignment(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_ALIGNMENT);
}

function filterAndSanitizeReligion(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_RELIGION);
}

function filterAndSanitizeDeity(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_DEITY);
}

function filterAndSanitizeHometown(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_HOMETOWN);
}

function filterAndSanitizeHitDie(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_HIT_DIE);
}

function filterAndSanitizeAge(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_AGE);
}

function filterAndSanitizeApparentAge(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_APPARENT_AGE);
}

function filterAndSanitizeUnnaturalAge(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_UNNATURAL_AGE);
}

function filterAndSanitizeSocialClass(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_SOCIAL_CLASS);
}

function filterAndSanitizeHeight(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_HEIGHT);
}

function filterAndSanitizeWeight(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_WEIGHT);
}

function filterAndSanitizeHair(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_HAIR);
}

function filterAndSanitizeEyes(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_EYES);
}

function filterAndSanitizeSiblings(&$input, &$errors) {
	filterAndSantizeOptionalStringFormField($input, $errors, CHARACTER_SIBLINGS);
}

function filterAndSanitizeParentsMarried(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_PARENTS_MARRIED);
}

function filterAndSantizeStringFormField(&$input, &$errors, $form_field_name) {
	$form_field = filter_input(INPUT_POST, $form_field_name, FILTER_SANITIZE_STRING);
	if ($form_field == NULL) {
		$errors[] = "Input Error|";
		$errors[] = __FILE__ . "|";
		$errors[] = 'Form field [' . $form_field_name . '] is missing';
		RestHeaderHelper::emitRestHeaders();
		die(json_encode($errors));
	}

	$input[$form_field_name] = $form_field;
}

function filterAndSantizeIntegerFormField(&$input, &$errors, $form_field_name) {
	$form_field = filter_input(INPUT_POST, $form_field_name, FILTER_SANITIZE_NUMBER_INT);
	if ($form_field == NULL) {
		$errors[] = "Input Error|";
		$errors[] = __FILE__ . "|";
		$errors[] = 'Form field [' . $form_field_name . '] is missing';
		RestHeaderHelper::emitRestHeaders();
		die(json_encode($errors));
	}

	$input[$form_field_name] = $form_field;
}

function filterAndSantizeOptionalStringFormField(&$input, &$errors, $form_field_name) {
	$form_field = filter_input(INPUT_POST, $form_field_name, FILTER_SANITIZE_STRING);
	if ($form_field != NULL) {
        $input[$form_field_name] = $form_field;
	}
}

function filterAndSantizeOptionalIntegerFormField(&$input, &$errors, $form_field_name) {
	$form_field = filter_input(INPUT_POST, $form_field_name, FILTER_SANITIZE_NUMBER_INT);
	if ($form_field != NULL) {
		$input[$form_field_name] = $form_field;
	}
}
?>