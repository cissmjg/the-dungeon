<?php
    const COMBAT_MODE_UNKNOWN = 0;
    const COMBAT_MODE_MOUNTED = 1;
    const COMBAT_MODE_UNMOUNTED = 2;

    function getCavalierCombatModeScription($combat_mode) {
        switch($combat_mode) {
            case COMBAT_MODE_MOUNTED:
                return "Mounted";
            case COMBAT_MODE_UNMOUNTED:
                return "Unmounted";
            case COMBAT_MODE_UNKNOWN:
                return "Unknown";
            default:
                return "Unknown";
        }
    }
?>