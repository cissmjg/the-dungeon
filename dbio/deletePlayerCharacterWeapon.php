<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WeaponIOHelper.php';
require_once __DIR__ . '/../webio/playerCharacterWeaponId.php';

getPlayerCharacterWeaponId($errors, $input);

WeaponIOHelper::deleteWeaponForPlayerCharacter($pdo, $input[PLAYER_CHARACTER_WEAPON_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Weapon Delete|";
    $log[] = "playerCharacterWeaponId: " . $input[PLAYER_CHARACTER_WEAPON_ID];

    echo json_encode($log);
}
