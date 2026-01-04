<?php
require_once __DIR__ . '/webio/requiredParameter.php';
const PAGE_ACTION = 'pageAction';

function getPageAction(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, PAGE_ACTION);
}