<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_SPEC2_HIT_BONUS = 'meleeSpec2HitBonus';

function getMeleeSpec2HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC2_HIT_BONUS, OPTIONAL_STRING_PARAMETER);
}