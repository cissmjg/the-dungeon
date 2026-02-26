<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';

$log = [];
$errors = [];
$input = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getWeaponProficiencyId($errors, $input);

addWeaponProficiencyToPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input[WEAPON_PROFICIENCY_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));	
}

$url = CurlHelper::buildCharacterActionRouterUrl();
$url = CurlHelper::addParameter($url, CHARACTER_ACTION, paramValue: CHARACTER_ACTION_EDIT_PLAYER_CHARACTER_WEAPON_PROFICIENCIES);
$url = CurlHelper::addParameter($url, PLAYER_NAME, paramValue: $input[PLAYER_NAME]);
$url = CurlHelper::addParameter($url, CHARACTER_NAME, $input[CHARACTER_NAME]);

$location_header = CurlHelper::buildLocationHeader($url);
header($location_header);

function addWeaponProficiencyToPlayerCharacter(\PDO $pdo, $player_name, $character_name, $weapon_proficiency_id, &$errors) {
	$sql_exec = "CALL addWeaponProficiency(:playerName, :characterName, :weaponProficiencyId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':weaponProficiencyId', $weapon_proficiency_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addWeaponProficiencyToPlayerCharacter : " . $e->getMessage();
	}
}