<?php
require_once 'requiredParameter.php';

function getStrengthBonusAvailable(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'strengthBonusAvailable');
}