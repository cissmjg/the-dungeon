<?php
require_once __DIR__ . '/requiredParameter.php';
const PLAYER_CHARACTER_SKILL_ID = 'playerCharacterSkillId';

function getPlayerCharacterSkillId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_SKILL_ID);
}