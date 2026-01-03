<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getHitBonusSpec3(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'hitBonusSpec3', $default_value);
}