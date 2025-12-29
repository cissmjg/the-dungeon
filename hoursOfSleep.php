<?php
require_once 'requiredParameter.php';

function getHoursOfSleep(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'hoursOfSleep');
}