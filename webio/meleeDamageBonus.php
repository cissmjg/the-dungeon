<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_DAMAGE_BONUS = 'meleeDamageBonus';

function getMeleeDamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_DAMAGE_BONUS, OPTIONAL_STRING_PARAMETER);
}