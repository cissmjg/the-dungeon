<?php
    const BULKY_NON = 1;
    const BULKY_FAIRLY = 2;
    const BULKY_FULL = 3;
    const BULKY_MAGICAL = 4;
    const BULKY_SPELL = 5;

    function getArmorBulkDescription($armor_bulk_factor) {
        switch($armor_bulk_factor) {
            case BULKY_NON:
                return "Non-Bulky";
            case BULKY_FAIRLY:
                return "Fairly Bulky";
            case BULKY_FULL:
                return "Bulky";
            case BULKY_MAGICAL:
                return "Magical Bulky";
            case BULKY_SPELL:
                return "Spell Bulky";
            default:
                return "UNKNOWN";
        }
    }

    function getTwoWeaponArmorBulkPenalty($armor_bulk_factor) {
        if ($armor_bulk_factor == BULKY_NON || $armor_bulk_factor == BULKY_MAGICAL || $armor_bulk_factor == BULKY_SPELL) {
            return 0;
        }

        if ($armor_bulk_factor == BULKY_FAIRLY) {
            return -2;
        }

        if ($armor_bulk_factor == BULKY_FULL) {
            return -4;
        }

        return -4;
    }
?>