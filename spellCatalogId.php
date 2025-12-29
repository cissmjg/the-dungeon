<?php
require_once 'requiredParameter.php';

function getSpellCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellCatalogId');
}