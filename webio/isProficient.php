<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const IS_PROFICIENT = 'isProficient';

function getIsProficient(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, IS_PROFICIENT);
}