<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getHitBonusSpec2(&$errors, &$input, $default_value) {
	getOptionalStringParameter($errors, $input, __FILE__, 'hitBonusSpec2', $default_value);
}