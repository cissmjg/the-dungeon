<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getPageAction(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'pageAction');
}