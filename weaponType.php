<?php

const WEAPON_TYPE_MELEE = 1;
const WEAPON_TYPE_MISSILE = 2;
const WEAPON_TYPE_TALENT = 18;

function getWeaponTypeDescription($weapon_type) {
    switch($weapon_type) {
        case WEAPON_TYPE_MELEE:
            return "Melee";
        case WEAPON_TYPE_MISSILE:
            return "Missile";
        case WEAPON_TYPE_TALENT:
            return "Talent";
        default:
            return "UNKNOWN";
    }
}
?>