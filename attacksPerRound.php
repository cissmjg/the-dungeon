<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getAttacksPerRound(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'attacksPerRound');
}