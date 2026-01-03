<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const STRENGTH_BONUS_AVAILABLE = 'strengthBonusAvailable';

function getStrengthBonusAvailable(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, STRENGTH_BONUS_AVAILABLE);
}