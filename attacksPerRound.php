<?php
require_once 'requiredParameter.php';

function getAttacksPerRound(&$errors, &$input) {
	getRequiredStringParameter($errors, $input, __FILE__, 'attacksPerRound');
}