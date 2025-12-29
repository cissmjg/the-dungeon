<?php
require_once 'requiredParameter.php';

function getIsProficient(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'isProficient');
}