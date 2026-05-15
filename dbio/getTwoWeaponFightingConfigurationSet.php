<?php

$errors = [];
$input = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

require_once __DIR__ . '/../classes/twoWeaponFightingConfigurationSet.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$two_weapon_fighting_configuration_sets = new TwoWeaponFightingConfigurationSet();
$two_weapon_fighting_configuration_sets->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($two_weapon_fighting_configuration_sets);
}
