<?php
require_once 'requiredParameter.php';

function getWeaponSpeed(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'weaponSpeed');
}