<?php
declare(strict_types=1);
require_once 'RestHeaderHelper.php';

function getOptionalStringParameter(&$errors, &$input, $calling_module, $parameter_name, $default_value) {
	// Get required parameter
    if (!empty($_GET[$parameter_name])) {
        $parameter_value = filter_input(INPUT_GET, $parameter_name, FILTER_SANITIZE_STRING);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] is missing';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else if (!empty($_POST[$parameter_name])) {
        $parameter_value = filter_input(INPUT_POST, $parameter_name, FILTER_SANITIZE_STRING);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] is missing';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else {
        $input[$parameter_name] = trim($default_value);
    }
}

function getOptionalIntegerParameter(&$errors, &$input, $calling_module, $parameter_name, $default_value) {
	// Get required parameter
    if (!empty($_GET[$parameter_name])) {
        $parameter_value = filter_input(INPUT_GET, $parameter_name, FILTER_SANITIZE_NUMBER_INT);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] is invalid';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else if (!empty($_POST[$parameter_name])) {
        $parameter_value = filter_input(INPUT_POST, $parameter_name, FILTER_SANITIZE_NUMBER_INT);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] is invalid';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else {
        $input[$parameter_name] = $default_value;
    }
}

function getOptionalBooleanParameter(&$errors, &$input, $calling_module, $parameter_name, $default_value) {
	// Get optional parameter
    if (!empty($_GET[$parameter_name])) {
        $parameter_value = filter_input(INPUT_GET, $parameter_name, FILTER_VALIDATE_BOOLEAN);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] contains invalid data';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = $parameter_value;
        }
    } else if (!empty($_POST[$parameter_name])) {
        $parameter_value = filter_input(INPUT_POST, $parameter_name, FILTER_VALIDATE_BOOLEAN);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] contains invalid data';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else if (isset($_GET[$parameter_name]) && $_GET[$parameter_name] == "0") {
        $input[$parameter_name] = false;
    } else if (isset($_POST[$parameter_name]) && $_POST[$parameter_name] == "0") {
        $input[$parameter_name] = false;
    } else {
        $input[$parameter_name] = $default_value;
    }
}
