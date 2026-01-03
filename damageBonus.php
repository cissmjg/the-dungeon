<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getDamageBonus(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'damageBonus');
}