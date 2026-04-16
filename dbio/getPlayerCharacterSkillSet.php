<?php
$errors = [];
$input = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/../dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../classes/playerCharacterSkillSet.php';

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);
if(count($errors) > 0) {
    die(json_encode($errors));
}

RestHeaderHelper::emitRestHeaders();
echo json_encode($player_character_skill_set);

?>