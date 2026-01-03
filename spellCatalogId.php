<?php
require_once __DIR__ . '/webio/requiredParameter.php';

function getSpellCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'spellCatalogId');
}