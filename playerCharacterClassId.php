<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const PLAYER_CHARACTER_CLASS_ID = 'playerCharacterClassId';

function getPlayerCharacterClassId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_CLASS_ID);
}