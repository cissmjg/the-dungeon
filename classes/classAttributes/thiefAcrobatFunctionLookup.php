<?php

require_once __DIR__ . '/../../dbio/constants/characterRaces.php';

class ThiefAcrobatFunctionLookup {

    private $tightrope_walking = [75, 80, 85, 90, 95, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100];

    //In feet
    private $pole_vaulting  = [9, 9.5, 10, 10.5, 11, 11.5, 12, 12.5, 13, 13.5, 14, 14.5, 15, 15.5, 16, 16.5, 17, 17.5];

    //In feet
    private $high_jumping  = [4, 4.25, 4.5, 4.75, 5, 5.25, 5.5, 5.75, 6.25, 6.5, 7, 7.5, 8, 8.5, 9, 9, 9, 9];

    //In feet
    private $broad_jump_standing = [5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12, 12, 12];

    //In feet
    private $broad_jump_running = [9, 9.5, 10, 10.5, 11, 11.5, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 22];

    private $tumble_attack  = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 20, 20, 20];

    private $tumble_evade  = [10, 15, 20, 25, 30, 35, 40, 45, 50, 52, 54, 56, 58, 60, 60, 60, 60, 60];

    private $no_damage_pct   = [25, 50, 75, 25, 50, 75, 25, 50, 75, 20, 40, 60, 80, 20, 40, 60, 80, 20];

    //In feet
    private $no_damage_distance   = [10, 10, 10, 20, 20, 20, 30, 30, 30, 40, 40, 40, 40, 50, 50, 50, 50, 60];

    public function getBaseTightropeWalking($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->tightrope_walking) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->tightrope_walking[$adj_character_level];
    }

    public function getBasePoleVaulting($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->pole_vaulting) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->pole_vaulting[$adj_character_level];
    }

    public function getBaseHighJumping($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->high_jumping) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->high_jumping[$adj_character_level];
    }

    public function getBaseBroadJumpStanding($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->broad_jump_standing) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->broad_jump_standing[$adj_character_level];
    }

    public function getBaseBroadJumpRunning($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->broad_jump_standing) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->broad_jump_running[$adj_character_level];
    }

    public function getBaseTumbleAttack($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->tumble_attack) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->tumble_attack[$adj_character_level];
    }

    public function getBaseTumbleEvade($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->tumble_evade) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->tumble_evade[$adj_character_level];
    }

    public function getBaseFallNoDamagePercentage($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->no_damage_pct) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->no_damage_pct[$adj_character_level];
    }

    public function getBaseFallNoDamageDistance($character_level) {
        if ($character_level < 6) {
            return -1;
        }

        $character_level = min($character_level, count($this->no_damage_distance) + 5);
        $adj_character_level = $character_level - 6;    // Arrays are zero based

        return $this->no_damage_distance[$adj_character_level];
    }

    public function getRacialAdjustmentForTIghtropeWalking($race_id) {
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
                return 10;
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
                return 5;
            case RACE_HALFLING:
                return 0;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    //In feet
    public function getRacialAdjustmentForPoleVaulting($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -2;
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
                return -2;
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
                return -2;
            case RACE_HALF_ORC:
                return 5;
            default:
                return 0;
        }
    }

    //In feet
    public function getRacialAdjustmentForHighJumping($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -1;
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
                return -1;
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
                return -1;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    //In feet
    public function getRacialAdjustmentForBroadJumpingStanding($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -2;
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
                return -1.5;
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
                return -1.5;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    //In feet
    public function getRacialAdjustmentForBroadJumpingRunning($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return -3;
            case RACE_ELF:
            case RACE_DARK_ELF:
            case RACE_GRAY_ELF:
            case RACE_HIGH_ELF:
            case RACE_VALLEY_ELF:
            case RACE_WILD_ELF:
            case RACE_WOOD_ELF:
                return -1;
            case RACE_DEEP_GNOME:
            case RACE_SURFACE_GNOME:
                return -4;
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
                return -4;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForTumbleAttack($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return +10;
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
                return 5;
            case RACE_HALF_ORC:
                return 0;
            default:
                return 0;
        }
    }

    public function getRacialAdjustmentForTumbleEvade($race_id) {
        switch ($race_id) {
            case RACE_DWARF:
            case RACE_GRAY_DWARF:
            case RACE_MOUNTAIN_DWARF:
            case RACE_HILL_DWARF:
            case RACE_KOROBOKURU:
                return 5;
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

    public function getRacialAdjustmentForFallNoDamagePercentage($race_id) {
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
                return 0;
            case RACE_HALFLING:
                return 5;
            case RACE_HALF_ORC:
                return 10;
            default:
                return 0;
        }
    }
}
?>