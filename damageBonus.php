<?php
require_once 'requiredParameter.php';

function getDamageBonus(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'damageBonus');
}