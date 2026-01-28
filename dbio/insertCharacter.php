<?php

const PLAYER_CHARACTER_ID = 'playerCharacterId';

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/constants/characterAttributes.php';
require_once __DIR__ . '/../characterActionRoutes.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

$errors = [];
$input = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);

filterAndSanitizeStrength($input, $errors);
filterAndSanitizeSuperStrength($input, $errors);
filterAndSanitizeIntelligence($input, $errors);
filterAndSanitizeSuperIntelligence($input, $errors);
filterAndSanitizeWisdom($input, $errors);
filterAndSanitizeSuperWisdom($input, $errors);
filterAndSanitizeDexterity($input, $errors);
filterAndSanitizeSuperDexterity($input, $errors);
filterAndSanitizeConstitution($input, $errors);
filterAndSanitizeSuperConstitution($input, $errors);
filterAndSanitizeCharisma($input, $errors);
filterAndSanitizeComeliness($input, $errors);
filterAndSanitizeArmorClass($input, $errors);
filterAndSanitizeHitPoints($input, $errors);
filterAndSanitizeRaceId($input, $errors);
filterAndSanitizeGender($input, $errors);
filterAndSanitizeCharacterClass1($input, $errors);
filterAndSanitizeCharacterClass2($input, $errors);
filterAndSanitizeCharacterClass3($input, $errors);
	
$new_character = insertBaseCharacter($pdo, $input, $errors);
if (count($errors) > 0) {
	$errors[] = "Database Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Failure on insertBaseCharacter';
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}
	
$input[PLAYER_CHARACTER_ID] = $new_character[PLAYER_CHARACTER_ID];
insertCharacterClasses($pdo, $input, $errors);
if (count($errors) > 0) {
	$errors[] = "Database Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Failure on insertCharacterClasses';
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$redirect_url = CurlHelper::buildCharacterActionRouterUrl();
$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_ACTION, CHARACTER_ACTION_VIEW_CHARACTER);
$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

$redirect_header = CurlHelper::buildLocationHeader($redirect_url);
header($redirect_header);

exit;

function insertBaseCharacter(\PDO $pdo, $input, &$errors) {

	$null_value = NULL;
	$sql_exec = "CALL createBaseCharacter(:playerName, :characterName, :characterStrength, :characterSuperStrength, :characterIntelligence, :characterSuperIntelligence, :characterWisdom, :characterSuperWisdom, :characterDexterity, :characterSuperDexterity, :characterConstitution, :characterSuperConstitution, :characterCharisma, :characterComeliness, :raceId, :armorClass, :hitPoints, :genderIn)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterStrength', $input[CHARACTER_STRENGTH], PDO::PARAM_INT);
	$statement->bindParam(':characterSuperStrength', $null_value, PDO::PARAM_NULL);
	$statement->bindParam(':characterIntelligence', $input[CHARACTER_INTELLIGENCE], PDO::PARAM_INT);
	$statement->bindParam(':characterSuperIntelligence', $null_value, PDO::PARAM_NULL);
	$statement->bindParam(':characterWisdom', $input[CHARACTER_WISDOM], PDO::PARAM_INT);
	$statement->bindParam(':characterSuperWisdom', $null_value, PDO::PARAM_NULL);
	$statement->bindParam(':characterDexterity', $input[CHARACTER_DEXTERITY], PDO::PARAM_INT);
	$statement->bindParam(':characterSuperDexterity', $null_value, PDO::PARAM_NULL);
	$statement->bindParam(':characterConstitution', $input[CHARACTER_CONSTITUTION], PDO::PARAM_INT);
	$statement->bindParam(':characterSuperConstitution', $null_value, PDO::PARAM_NULL);
	$statement->bindParam(':characterCharisma', $input[CHARACTER_CHARISMA], PDO::PARAM_INT);
	$statement->bindParam(':characterComeliness', $input[CHARACTER_COMELINESS], PDO::PARAM_INT);
	$statement->bindParam(':raceId', $input[CHARACTER_RACE_ID], PDO::PARAM_INT);
	$statement->bindParam(':armorClass', $input[CHARACTER_ARMOR_CLASS], PDO::PARAM_INT);
	$statement->bindParam(':hitPoints', $input[CHARACTER_HIT_POINTS], PDO::PARAM_INT);
	$statement->bindParam(':genderIn', $input[CHARACTER_GENDER], PDO::PARAM_STR);
	
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in insertBaseCharacter : " . $e->getMessage();
	}
	
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function insertCharacterClasses(\PDO $pdo, $input, &$errors) {
	if (!empty($input[CHARACTER_PRIMARY_CLASS])) {
		insertCharacterClass($pdo, $input[PLAYER_CHARACTER_ID], $input[CHARACTER_PRIMARY_CLASS], $errors);
		if (count($errors) > 0) {
			return;
		}
	}
	
	if (!empty($input[CHARACTER_SECONDARY_CLASS])) {
		insertCharacterClass($pdo, $input[PLAYER_CHARACTER_ID], $input[CHARACTER_SECONDARY_CLASS], $errors);
		if (count($errors) > 0) {
			return;
		}
	}
	
	if (!empty($input[CHARACTER_TERTIARY_CLASS])) {
		insertCharacterClass($pdo, $input[PLAYER_CHARACTER_ID], $input[CHARACTER_TERTIARY_CLASS], $errors);
	}
}

function insertCharacterClass(\PDO $pdo, $player_character_id, $character_class_id, &$errors) {
	$sql_exec = "CALL addClassToCharacter(:playerCharacterId, :characterClassId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterId', $player_character_id, PDO::PARAM_INT);	
	$statement->bindParam(':characterClassId', $character_class_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in insertCharacterClass for Character Class ID [" . $character_class_id . "]: " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
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

function filterAndSanitizeConstitution(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_CONSTITUTION);
}

function filterAndSanitizeSuperConstitution($input, $errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SUPER_CONSTITUTION);
}

function filterAndSanitizeCharisma(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_CHARISMA);
}

function filterAndSanitizeComeliness(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_COMELINESS);
}

function filterAndSanitizeArmorClass(&$input, &$errors) {
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_ARMOR_CLASS);
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
	filterAndSantizeIntegerFormField($input, $errors, CHARACTER_PRIMARY_CLASS);
}

function filterAndSanitizeCharacterClass2(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_SECONDARY_CLASS);
}

function filterAndSanitizeCharacterClass3(&$input, &$errors) {
	filterAndSantizeOptionalIntegerFormField($input, $errors, CHARACTER_TERTIARY_CLASS);
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

function filterAndSantizeOptionalIntegerFormField(&$input, &$errors, $form_field_name) {
	$form_field = filter_input(INPUT_POST, $form_field_name, FILTER_SANITIZE_NUMBER_INT);
	if ($form_field != NULL) {
		$input[$form_field_name] = $form_field;
	}
}
?>