<?php
require_once 'requiredParameter.php';

function getWeaponDescription(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponDescription');
}