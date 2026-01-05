<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_HIT_BONUS = 'meleeHitBonus';

function getMeleeHitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}