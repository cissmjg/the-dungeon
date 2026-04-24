<?php

$errors = [];
$input = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerCharacterWeaponId.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

require_once __DIR__ . '/../classes/weaponDetail.php';
require_once __DIR__ . '/../classes/playerCharacterWeapon.php';
require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if(count($errors) > 0) {
    die(json_encode($errors));
}

$weapon_detail = new PlayerCharacterWeapon();
$weapon_detail->init($pdo, $input[PLAYER_CHARACTER_WEAPON_ID], $player_character_skill_set, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($weapon_detail);
}
