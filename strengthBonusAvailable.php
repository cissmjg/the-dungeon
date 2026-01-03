<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getStrengthBonusAvailable(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'strengthBonusAvailable');
}