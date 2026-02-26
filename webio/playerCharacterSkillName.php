<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

const PLAYER_CHARACTER_SKILL_NAME = 'playerCharacterSkillName';

function getPlayerCharacterSkillName(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, PLAYER_CHARACTER_SKILL_NAME);
}

function getOptionalPlayerCharacterSkillName(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, PLAYER_CHARACTER_SKILL_NAME, OPTIONAL_STRING_PARAMETER);
}