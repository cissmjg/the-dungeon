<?php

    const TWO_WEAPON_FIGHTING_UNKNOWN = 0;
    const TWO_WEAPON_FIGHTING_MAIN_HAND = 1;
    const TWO_WEAPON_FIGHTING_OFF_HAND = 2;

    function getTwoWeaponFightHandDescription($two_weapon_fighting_hand) {
        switch($two_weapon_fighting_hand) {
            case TWO_WEAPON_FIGHTING_MAIN_HAND;
                return "Main Hand";
            case TWO_WEAPON_FIGHTING_OFF_HAND:
                RETURN "Off Hand";
            default:
                return "Unknown";
        }
    }
?>