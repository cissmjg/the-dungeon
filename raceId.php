<?php
require_once 'requiredParameter.php';

function getRaceId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'race_id');
}