<?php
    const UNKNOWN = 0;
    const MOUNTED = 1;
    const UNMOUNTED = 2;

    function getCavalierCombatModeScription($combat_mode) {
        switch($combat_mode) {
            case MOUNTED:
                return "Mounted";
            case UNMOUNTED:
                return "Unmounted";
            case UNKNOWN:
                return "Unknown";
            default:
                return "Unknown";
        }
    }
?>