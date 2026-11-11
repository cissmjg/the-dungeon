<?php

require_once __DIR__ . '/../../dbio/constants/characterRaces.php';

class ThiefFunctionLookup {
    private $base_pick_pocket = [30, 35, 40, 45, 50, 55, 60, 65, 70, 80, 90, 100, 105, 110, 115, 125, 125];
    private $base_open_locks  = [25, 29, 33, 37, 42, 47, 52, 57, 62, 67, 72, 77, 82, 87, 92, 97, 99];
    private $base_find_traps  = [20, 25, 30, 36, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 99];
    private $base_move_silent = [15, 21, 27, 33, 40, 47, 55, 62, 70, 78, 86, 94, 99, 99, 99, 99, 99];
    private $base_hide_shadow = [10, 15, 20, 25, 31, 37, 43, 49, 56, 63, 70, 77, 85, 93, 99, 99, 99];
    private $base_hear_noise  = [10, 10, 15, 15, 20, 20, 25, 25, 30, 30, 35, 35, 40, 40, 50, 50, 55];
    private $base_climb_wall  = [85, 86, 87, 88, 90, 92, 94, 96, 98, 99, 99.1, 99.2, 99.3, 99.4, 99.5, 99.6, 99.7];
    private $base_read_lang   = [ 0,  0,  0, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 80];

    public function getBasePickPocket($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_pick_pocket));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_pick_pocket[$adj_character_level];
    }

    public function getBaseOpenLocks($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_open_locks));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_open_locks[$adj_character_level];
    }

    public function getBaseFindRemoveTraps($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_find_traps));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_find_traps[$adj_character_level];
    }

    public function getBaseMoveSilently($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_move_silent));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_move_silent[$adj_character_level];
    }

    public function getBaseHideInShadows($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_hide_shadow));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_hide_shadow[$adj_character_level];
    }

    public function getBaseHearNoise($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_hear_noise));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_hear_noise[$adj_character_level];
    }

    public function getBaseClimbWalls($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_climb_wall));
        $adj_character_level = $character_level - 1;    // Arrays are zero based

        return $this->base_climb_wall[$adj_character_level];
    }

    public function getBaseReadLanguages($character_level) {
        if ($character_level < 1) {
            return -1;
        }

        $character_level = min($character_level, count($this->base_read_lang));
        $adj_character_level = $character_level - 1;    // Arrays are zero based
        
        return $this->base_read_lang[$adj_character_level];
    }

    public function getRacialAdjustmentForPickPocket($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 0;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 5;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 0;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 10;
            case RACE_HALFLING:
                return 5;
            case RACE_HALF_ORC:
                return -5;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForOpenLocks($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 10;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return -5;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 5;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return 5;
            case RACE_HALF_ORC:
                return 5;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForFindRemoveTraps($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 15;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 0;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 10;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return 5;
            case RACE_HALF_ORC:
                return 5;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForMoveSilently($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 0;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 5;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 5;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return 10;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForHideInShadows($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 0;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 10;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 5;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 5;
            case RACE_HALFLING:
                return 15;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForHearNoise($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 0;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 5;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 10;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return 5;
            case RACE_HALF_ORC:
                return 5;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForClimbWalls($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -10;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 0;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return -15;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return -15;
            case RACE_HALF_ORC:
                return 5;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForReadLanguages($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -5;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return 0;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return 0;
            case RACE_HALF_ELF_HIGH:
            case RACE_HALF_ELF:
            case RACE_HALF_ELF_DARK:
            case RACE_HALF_DROW:
            case RACE_HALF_ELF_GRAY:
            case RACE_HALF_ELF_VALLEY:
            case RACE_HALF_ELF_WILD:
            case RACE_HALF_ELF_WOOD:
                return 0;
            case RACE_HALFLING:
                return -5;
            case RACE_HALF_ORC:
                return -10;
            default:
                return 0;
        }
    }
}
?>