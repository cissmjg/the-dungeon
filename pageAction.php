<?php
require_once 'requiredParameter.php';

function getPageAction(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'pageAction');
}