<?php
require_once 'requiredParameter.php';

function getHitBonusSpec1(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'hitBonusSpec1', $default_value);
}