<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';

require_once __DIR__ . '/../classes/playerCharacterReadySpellSet.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

// Get player and Character Name
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$player_character_ready_spell_set = new PlayerCharacterReadySpellSet();
$player_character_ready_spell_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($player_character_ready_spell_set);
}

?>