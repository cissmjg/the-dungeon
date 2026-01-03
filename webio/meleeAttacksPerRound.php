<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_ATTACKS_PER_ROUND = 'meleeAttacksPerRound';

function getMeleeAttacksPerRound(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_ATTACKS_PER_ROUND, OPTIONAL_STRING_PARAMETER);
}