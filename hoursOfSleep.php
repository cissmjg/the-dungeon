<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getHoursOfSleep(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'hoursOfSleep');
}