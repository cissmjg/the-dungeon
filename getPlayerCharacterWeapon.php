<?php

$errors = [];
$input = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerCharacterWeapon.php';
require_once 'playerCharacterWeaponId.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'characterAttributes.php';
require_once 'weaponProficiencyId.php';
require_once 'weaponDetail.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$weapon_detail = new PlayerCharacterWeapon();
$weapon_detail->init($pdo, $input['playerCharacterWeaponId'], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($weapon_detail);
}
