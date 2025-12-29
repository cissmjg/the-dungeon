<?php

function buildHiddenTag($name, $value) {
    return buildHiddenTagWithId($name, $name, $value);
}

function buildHiddenTagWithId($name, $id, $value) {
    $hidden_tag = '<input type="hidden" ';
    $hidden_tag_id_name = 'id="' . $id . '" name="' . $name . '" ';
    $hidden_tag .= $hidden_tag_id_name;
    $hidden_value = 'value="' . $value . '"';
    $hidden_tag .= $hidden_value;
    $hidden_tag .= '>';

    return $hidden_tag;
}

?>