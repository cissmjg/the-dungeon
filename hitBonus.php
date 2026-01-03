<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getHitBonus(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'hitBonus');
}