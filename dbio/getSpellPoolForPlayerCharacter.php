<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';

require_once __DIR__ . '/../classes/playerCharacterSpellPool.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

$player_character_spell_pool = new PlayerCharacterSpellPool();
$player_character_spell_pool->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($player_character_spell_pool);
}
