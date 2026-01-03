<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_SPEC1_HIT_BONUS = 'meleeSpec1HitBonus';

function getMeleeSpec1HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC1_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}