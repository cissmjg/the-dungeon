<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/constants/weapons.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/playerCharacterWeaponId.php';
require_once __DIR__ . '/../webio/playerCharacterWeapon2Id.php';

require_once __DIR__ . '/../classes/playerCharacterWeapon.php';

$input = [];
$log = [];
$errors = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);
getPlayerCharacterWeapon2Id($errors, $input);

$player_character_weapon1 = new PlayerCharacterWeapon();
$player_character_weapon1->init($pdo, $input[PLAYER_CHARACTER_WEAPON_ID], $errors);

$player_character_weapon2 = new PlayerCharacterWeapon();
$player_character_weapon2->init($pdo, $input[PLAYER_CHARACTER_WEAPON2_ID], $errors);

$weapon1_melee_speed = 100;
$weapon2_melee_speed = 100;
if ($player_character_weapon1->getWeaponProficiencyId() == BATTLE_AXE) {
    $weapon1_melee_speed = 7;
} else {
    $weapon1_melee_speed = $player_character_weapon1->getMeleeWeaponSpeed();
}

if ($player_character_weapon2->getWeaponProficiencyId() == BATTLE_AXE) {
    $weapon2_melee_speed = 7;
} else {
    $weapon2_melee_speed = $player_character_weapon2->getMeleeWeaponSpeed();
}

$main_hand_weapon_id = 0;
$off_hand_weapon_id = 0;

// Per Two Weapon Fighting rules, the slower weapon (higher weapon speed) goes in the main hand
if ($weapon1_melee_speed == $weapon2_melee_speed) {
    $main_hand_weapon_id = $player_character_weapon1->getWeaponId();
    $off_hand_weapon_id = $player_character_weapon2->getWeaponId();
} else if ($weapon1_melee_speed > $weapon2_melee_speed) {
    $main_hand_weapon_id = $player_character_weapon1->getWeaponId();
    $off_hand_weapon_id = $player_character_weapon2->getWeaponId();
} else {
    $main_hand_weapon_id = $player_character_weapon2->getWeaponId();
    $off_hand_weapon_id = $player_character_weapon1->getWeaponId();
}

addTwoWeaponConfigurationToPlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $main_hand_weapon_id, $off_hand_weapon_id,  $errors);
if (count($errors) > 0) {
    die(json_encode($errors));
}

RestHeaderHelper::emitRestHeaders();
$log[] = "SUCCESS|";
$log[] = "Two Weapon Configuration Added|";

echo json_encode($log);

function addTwoWeaponConfigurationToPlayerCharacter(\PDO $pdo, $player_name, $character_name, $weapon1_id, $weapon2_id, &$errors) {
	$sql_exec = "CALL addTwoWeaponConfiguration(:playerName, :characterName, :weapon1Id, :weapon2Id)";

    $statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':weapon1Id', $weapon1_id, PDO::PARAM_INT);
    $statement->bindParam(':weapon2Id', $weapon2_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addTwoWeaponConfigurationToPlayerCharacter : " . $e->getMessage();
	}
}

?>