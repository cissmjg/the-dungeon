<?php
require_once 'RestHeaderHelper.php';

function getRequiredStringParameter(&$errors, &$input, $calling_module, $parameter_name) {
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
        $errors[] = "Input Error|";
        $errors[] = $calling_module. "|";
        $errors[] = 'Parameter [' . $parameter_name . '] is missing';
        foreach($_POST AS $key => $value) {
            $errors[$key] = $value;
        }
        RestHeaderHelper::emitRestHeaders();
        die(json_encode($errors));
    }
}

function getRequiredIntegerParameter(&$errors, &$input, $calling_module, $parameter_name) {
	// Get required parameter
    if (!empty($_GET[$parameter_name])) {
        $parameter_value = filter_input(INPUT_GET, $parameter_name, FILTER_SANITIZE_NUMBER_INT);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] is not a valid GET integer';
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
            $errors[] = 'Parameter [' . $parameter_name . '] is not a valid POST integer';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
        }
    } else if (isset($_GET[$parameter_name]) && $_GET[$parameter_name] == "0") {
        $input[$parameter_name] = "0";
    } else if (isset($_POST[$parameter_name]) && $_POST[$parameter_name] == "0") {
        $input[$parameter_name] = "0";
    } else {
        $errors[] = "Input Error|";
        $errors[] = $calling_module. "|";
        $errors[] = 'Parameter [' . $parameter_name . '] is missing';
        RestHeaderHelper::emitRestHeaders();
        die(json_encode($errors));
    }
}

function getRequiredBooleanParameter(&$errors, &$input, $calling_module, $parameter_name) {
	// Get required parameter
    if (!empty($_GET[$parameter_name])) {
        $parameter_value = filter_input(INPUT_GET, $parameter_name, FILTER_VALIDATE_BOOLEAN);
        if ($parameter_value == NULL ) {
            $errors[] = "Input Error|";
            $errors[] = $calling_module. "|";
            $errors[] = 'Parameter [' . $parameter_name . '] contains invalid data';
            RestHeaderHelper::emitRestHeaders();
            die(json_encode($errors));
        } else {
            $input[$parameter_name] = trim($parameter_value);
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
        $errors[] = "Input Error|";
        $errors[] = $calling_module. "|";
        $errors[] = 'Parameter [' . $parameter_name . '] is missing';
        $errors[] = $_GET;
        $errors[] = isset($_GET[$parameter_name]);
        $errors[] = $_GET[$parameter_name];
        RestHeaderHelper::emitRestHeaders();
        die(json_encode($errors));
    }
}
