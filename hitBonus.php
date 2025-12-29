<?php
require_once 'requiredParameter.php';

function getHitBonus(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'hitBonus');
}