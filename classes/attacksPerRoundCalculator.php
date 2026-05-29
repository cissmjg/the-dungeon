<?php
    require_once 'accountClassSummary.php';
    require_once 'characterDetails.php';
    require_once 'playerCharacterSkill.php';
    require_once 'playerCharacterSkillSet.php';
    require_once 'playerCharacterWeapon.php';

    require_once __DIR__ . '/../dbio/constants/attacksPerRound.php';
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
    require_once __DIR__ . '/../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../dbio/constants/weapons.php';
    require_once __DIR__ . '/../dbio/constants/skills.php';

    class AttacksPerRoundCalculator {

        private $character_details;
        public function getCharacterDetails() {
            return $this->character_details;
        }

        private $player_character_skill_set;
        public function getPlayerCharacterSkillSet() {
            return $this->player_character_skill_set;
        }

        private $player_character_weapon;
        public function getPlayerCharacterWeapon() {
            return $this->player_character_weapon;
        }

        private $combat_mode = COMBAT_MODE_UNMOUNTED;
        public function getCombatMode() {
            return $this->combat_mode;
        }

        private $melee_attacks_progression_table = [AttacksPerRound::One, AttacksPerRound::ThreeEvery2, AttacksPerRound::Two, AttacksPerRound::FiveEvery2];
        private $melee_specialized_attacks_progression_table = [AttacksPerRound::ThreeEvery2, AttacksPerRound::Two, AttacksPerRound::FiveEvery2];
        private $archer_bow_progression_table = [AttacksPerRound::Two, AttacksPerRound::Three, AttacksPerRound::Four];
        private $cavalier_short_bow_progression_table = [AttacksPerRound::Two, AttacksPerRound::Three, AttacksPerRound::Four];
        private $short_bow_half_rate_progression_table = [AttacksPerRound::One, AttacksPerRound::ThreeEvery2, AttacksPerRound::Two];

        public function __construct(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, $combat_mode) {
            $this->character_details = $character_details;
            $this->player_character_skill_set = $player_character_skill_set;
            $this->player_character_weapon = $player_character_weapon;
            $this->combat_mode = $combat_mode;
        }

        public function getAttacksPerRound($weapon_type) {

            $attacks_per_round = AttacksPerRound::One;

            if ($weapon_type == WEAPON_TYPE_MISSILE) {
                if ($this->getCombatMode() == COMBAT_MODE_MOUNTED) {
                    $attacks_per_round = $this->getArrowHalfRate();
                } else {
                    $attacks_per_round = $this->getMissileAttackRate();
                }
            }

            if ($weapon_type == WEAPON_TYPE_MELEE) {
                $attacks_per_round = $this->getMeleeAttackRate();
            }

            return $attacks_per_round;
        }

        private function getMeleeAttackRate() {
            $attacks_per_round = AttacksPerRound::One;

            $is_specialized = count($this->getPlayerCharacterSkillSet()->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getPlayerCharacterWeapon()->getWeaponProficiencyId())) > 0;
            if ($is_specialized) {
                $level_progression_table = [1, 7, 13];
                $character_level = $this->getCharacterDetails()->getFighterTypeLevel();
                $attacks_per_round = $this->getMeleeSpecializedAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->containsClassId(BARBARIAN)) {
                $level_progression_table = [1, 6, 11];
                $character_level = $this->getCharacterDetails()->getLevelForClass(BARBARIAN);
                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->containsClassId(RANGER)) {
                $level_progression_table = [1, 8, 14];
                $character_level = $this->getCharacterDetails()->getLevelForClass(RANGER);
                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->isArcherType()) {
                $character_level = $this->getCharacterDetails()->getFighterTypeLevel();
                $level_progression_table = [1, 9, 16];
                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->containsClassId(PALADIN)) {
                $level_progression_table = [1, 7, 13, 19];
                
                $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();
                $is_lance = $this->getPlayerCharacterWeapon()->getMeleeWeaponSubtype() == WEAPON_SUBTYPE_LANCE;
                $is_preferred = $this->getPlayerCharacterWeapon()->getIsPreferred();

                $character_level = $this->getCharacterDetails()->getPrimaryClass()->getClassLevel();
                if ($is_preferred) {
                    $character_level += 5;
                }

                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->containsClassId(CAVALIER)) {
                $level_progression_table = [1, 6, 11, 16];
                
                $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();
                $is_lance = $this->getPlayerCharacterWeapon()->getMeleeWeaponSubtype() == WEAPON_SUBTYPE_LANCE;
                $is_preferred = $this->getPlayerCharacterSkillSet()->isWeaponPreferred($weapon_proficiency_id) || $is_lance;

                $character_level = $this->getCharacterDetails()->getPrimaryClass()->getClassLevel();
                if ($is_preferred) {
                    $character_level += 5;
                }

                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->containsClassId(ELVEN_CAVALIER)) {
                $level_progression_table = [1, 6, 11, 16];

                $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();
                $is_long_sword = $weapon_proficiency_id == LONG_SWORD || $weapon_proficiency_id == ELVEN_THIN_BLADE;
                $is_preferred = $this->getPlayerCharacterSkillSet()->isWeaponPreferred($weapon_proficiency_id) || $is_long_sword;

                $character_level = $this->getCharacterDetails()->getPrimaryClass()->getClassLevel();
                if ($is_preferred) {
                    $character_level += 4;
                }

                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->isFighterType()) {
                $level_progression_table = [1, 7, 13];
                $character_level = $this->getCharacterDetails()->getFighterTypeLevel();
                $attacks_per_round = $this->getMeleeAttacksPerRound($level_progression_table, $character_level);
            }

            return $attacks_per_round;
        }

        private function getMissileAttackRate() {
            $attacks_per_round = AttacksPerRound::One;

            $is_specialized = count($this->getPlayerCharacterSkillSet()->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getPlayerCharacterWeapon()->getWeaponProficiencyId())) > 0;
            if ($is_specialized) {
                $best_melee_class_id = $this->getCharacterDetails()->getBestMeleeClassId();
                $character_level = $this->getCharacterDetails()->getLevelForClass($best_melee_class_id);
                $attacks_per_round = $this->getSpecializedMissileAttacksPerRound($character_level);
            } else if ($this->getCharacterDetails()->isArcherType() && $this->getPlayerCharacterWeapon()->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW && $this->getPlayerCharacterWeapon()->getWeaponProficiencyId() != SHORT_BOW) {
                $level_progression_table = [1, 7, 13];
                $character_level = $this->getCharacterDetails()->getFighterTypeLevel();
                $attacks_per_round = $this->getArcherBowAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
                $level_progression_table = [1, 7, 13];
                $character_level = $this->getCharacterDetails()->getPrimaryClass()->getClassLevel();
                $is_preferred = $this->getPlayerCharacterWeapon()->getIsPreferred();

                if ($is_preferred) {
                    $character_level += 4;
                }

                $attacks_per_round = $this->getCavalierShortBowAttacksPerRound($level_progression_table, $character_level);
            } else {
                $weapon_subtype = $this->getPlayerCharacterWeapon()->getMissileWeaponSubtype();
                $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();

                if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $attacks_per_round = $this->calculateNonSpecializedCrossbowAttacks($weapon_proficiency_id);
                } else if ($weapon_proficiency_id == DAGGER) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_proficiency_id == KNIFE) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_proficiency_id == KUNAI) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_proficiency_id == UCHI_NE) {
                    $attacks_per_round = AttacksPerRound::Two;
                }
            }

            return $attacks_per_round;
        }

        private function getSpecializedMissileAttacksPerRound($character_level) {
            $attacks_per_round = AttacksPerRound::One;

            $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();
            $weapon_subtype = $this->getPlayerCharacterWeapon()->getMissileWeaponSubtype();
            $weapon_type = $this->getPlayerCharacterWeapon()->getMissileWeaponType();

            if ($character_level >= 1 && $character_level < 7) {
                if ($weapon_proficiency_id == DAGGER) {
                    $attacks_per_round = AttacksPerRound::Three;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $attacks_per_round = $this->calculateSpecializedCrossbowAttacksLevel1UpTo7($weapon_proficiency_id);
                } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == DART) {
                    $attacks_per_round = AttacksPerRound::Four;
                } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
                    $attacks_per_round = AttacksPerRound::ThreeEvery2;
                }
            }

            if ($character_level >= 7 && $character_level < 13) {
                if ($weapon_proficiency_id == DAGGER) {
                    $attacks_per_round = AttacksPerRound::Four;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
                    $attacks_per_round = AttacksPerRound::Three;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $attacks_per_round = $this->calculateSpecializedCrossbowAttacksLevel7UpTo13($weapon_proficiency_id);
                } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
                    $attacks_per_round = AttacksPerRound::ThreeEvery2;
                } else if ($weapon_proficiency_id == DART) {
                    $attacks_per_round = AttacksPerRound::Five;
                } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
                    $attacks_per_round = AttacksPerRound::Two;
                }
            }

            if ($character_level >= 13) {
                if ($weapon_proficiency_id == DAGGER) {
                    $attacks_per_round = AttacksPerRound::Five;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
                    $attacks_per_round = AttacksPerRound::Four;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $attacks_per_round = $this->calculateSpecializedCrossbowAttacksLevel13AndOver($weapon_proficiency_id);
                } else if ($weapon_proficiency_id == LASSO || $weapon_proficiency_id == STAFF_SLING) {
                    $attacks_per_round = AttacksPerRound::Two;
                } else if ($weapon_proficiency_id == DART) {
                    $attacks_per_round = AttacksPerRound::Six;
                } else if ($weapon_type == WEAPON_TYPE_MISSILE) {
                    $attacks_per_round = AttacksPerRound::FiveEvery2;
                }
            }

            return $attacks_per_round;
        }

        private function getArrowHalfRate() {
            $attacks_per_round = AttacksPerRound::OneEvery2;

            $is_specialized = count($this->getPlayerCharacterSkillSet()->getAllSkillInstancesForWeapon(SPECIALIZATION, $this->getPlayerCharacterWeapon()->getWeaponProficiencyId())) > 0;
            if ($is_specialized) {
                $level_progression_table = [1, 7, 13];
                $best_melee_class_id = $this->getCharacterDetails()->getBestMeleeClassId();
                $character_level = $this->getCharacterDetails()->getLevelForClass($best_melee_class_id);
                $attacks_per_round = $this->getShortBowHalfRateAttacksPerRound($level_progression_table, $character_level);
            } else if ($this->getCharacterDetails()->getPrimaryClass()->getClassId() == ELVEN_CAVALIER) {
                $level_progression_table = [1, 7, 13];
                $character_level = $this->getCharacterDetails()->getPrimaryClass()->getClassLevel();
                $is_preferred = $this->getPlayerCharacterWeapon()->getIsPreferred();

                if ($is_preferred) {
                    $character_level += 4;
                }

                $attacks_per_round = $this->getCavalierShortBowHalfRateAttacksPerRound($level_progression_table, $character_level);
            } else {
                $weapon_subtype = $this->getPlayerCharacterWeapon()->getMissileWeaponSubtype();
                $weapon_proficiency_id = $this->getPlayerCharacterWeapon()->getWeaponProficiencyId();
                $has_rapid_reload = $this->getPlayerCharacterSkillSet()->getAllSkillInstances(RAPID_RELOAD);

                if ($weapon_subtype == WEAPON_SUBTYPE_BOW) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_subtype == WEAPON_SUBTYPE_CROSSBOW) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == DAGGER) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == DOKYU) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == KNIFE) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == KUNAI) {
                    $attacks_per_round = AttacksPerRound::One;
                } else if ($weapon_proficiency_id == UCHI_NE) {
                    $attacks_per_round = AttacksPerRound::One;
                }
            }

            return $attacks_per_round;
        }

        /* Having rapid reload will double the number of attacks for non specialized characters */
        private function calculateNonSpecializedCrossbowAttacks($weapon_proficiency_id) {

            $has_rapid_reload = $this->getPlayerCharacterSkillSet()->getAllSkillInstances(RAPID_RELOAD);
            switch($weapon_proficiency_id) {
                    case LIGHT_CROSSBOW:
                    case PISTOL_GRIP_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::Two;
                        } else {
                            $attacks_per_round = AttacksPerRound::One;
                        }
                        break;
                    case GREAT_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::OneEvery2;
                        } else {
                            $attacks_per_round = AttacksPerRound::OneEvery3;
                        }
                        break;
                        if ($has_rapid_reload) {
                        } else {

                        }
                        break;
                    case HEAVY_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::OneEvery2;
                        break;
                    case HAND_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::One;
                        break;
                    case DOKYU:
                        $attacks_per_round = AttacksPerRound::Two;
                        break;
                    default:
                        $attacks_per_round = AttacksPerRound::One;
                }

            return $attacks_per_round;
        }

        /* DM ruling in email dated 5/20/2026
        UA also has a table for attacks per round based on the weapon. Having rapid reload will bump the number of attacks up by one click as patterned in the table. 
        So for each category the PC climbs they will gain 1/2 attack per round so as follows :

            Level    Att/Round    Weapon Speeds
            1-6      2/1          2/8
            7-12     5/2          2/8/(EOR)    PC gets 3 shots every other round
            13+      3/1          2/8/EOR

        This is a bit of a misnomer ... Rapid Reload gives the character 2 attacks per round WITHOUT specialization. 
        With Specialization the rate goes up 1 'click' (i.e. 1/2 more attack per round).
        */
        private function calculateSpecializedCrossbowAttacksLevel1UpTo7($weapon_proficiency_id) {

            $has_rapid_reload = $this->getPlayerCharacterSkillSet()->getAllSkillInstances(RAPID_RELOAD);
            switch($weapon_proficiency_id) {
                    case LIGHT_CROSSBOW:
                    case PISTOL_GRIP_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::FiveEvery2;
                        } else {
                            $attacks_per_round = AttacksPerRound::One;
                        }
                        break;
                    case GREAT_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::One;
                        } else {
                            $attacks_per_round = AttacksPerRound::OneEvery2;
                        }
                        break;
                    case HEAVY_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::OneEvery2;
                        break;
                    case HAND_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::ThreeEvery2;
                        break;
                    case Dokyu:
                        $attacks_per_round = AttacksPerRound::Two;
                        break;
                    default:
                        $attacks_per_round = AttacksPerRound::One;
                }

            return $attacks_per_round;
        }

        private function calculateSpecializedCrossbowAttacksLevel7UpTo13($weapon_proficiency_id) {

            $has_rapid_reload = $this->getPlayerCharacterSkillSet()->getAllSkillInstances(RAPID_RELOAD);
            switch($weapon_proficiency_id) {
                    case LIGHT_CROSSBOW:
                    case PISTOL_GRIP_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::Three;
                        } else {
                            $attacks_per_round = AttacksPerRound::ThreeEvery2;
                        }
                        break;
                    case GREAT_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::One;
                        break;
                    case HEAVY_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::One;
                        break;
                    case HAND_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::Two;
                        break;
                    case Dokyu:
                        $attacks_per_round = AttacksPerRound::Two;
                        break;
                    default:
                        $attacks_per_round = AttacksPerRound::One;
                }

            return $attacks_per_round;
        }

        private function calculateSpecializedCrossbowAttacksLevel13AndOver($weapon_proficiency_id) {

            $has_rapid_reload = $this->getPlayerCharacterSkillSet()->getAllSkillInstances(RAPID_RELOAD);
            switch($weapon_proficiency_id) {
                    case LIGHT_CROSSBOW:
                    case PISTOL_GRIP_CROSSBOW:
                        if ($has_rapid_reload) {
                            $attacks_per_round = AttacksPerRound::SevenEvery2;
                        } else {
                            $attacks_per_round = AttacksPerRound::Three;
                        }
                        break;
                    case GREAT_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::One;
                        break;
                    case HEAVY_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::ThreeEvery2;
                        break;
                    case HAND_CROSSBOW:
                        $attacks_per_round = AttacksPerRound::FiveEvery2;
                        break;
                    case Dokyu:
                        $attacks_per_round = AttacksPerRound::Two;
                        break;
                    default:
                        $attacks_per_round = AttacksPerRound::One;
                }

            return $attacks_per_round;
        }

        private function getMeleeAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->melee_attacks_progression_table);
        }

        private function getMeleeSpecializedAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->melee_specialized_attacks_progression_table);
        }

        private function getArcherBowAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->archer_bow_progression_table);
        }

        private function getCavalierShortBowAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->cavalier_short_bow_progression_table);
        }

        private function getCavalierShortBowHalfRateAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->short_bow_half_rate_progression_table);
        }

        private function getShortBowHalfRateAttacksPerRound($level_progression_table, $character_level) {
            return $this->getWeaponAttacksPerRound($level_progression_table, $character_level, $this->short_bow_half_rate_progression_table);
        }

        private function getWeaponAttacksPerRound($level_progression_table, $character_level, $weapon_progression_table) {
            $level_index = 0;
            $level_progression_count = count($level_progression_table) - 1;

            $lower_level = $level_progression_table[0];
            $upper_level = $level_progression_table[1];
            do {

                if ($level_index == $level_progression_count) {
                    return $weapon_progression_table[$level_index];
                }

                $lower_level = $level_progression_table[$level_index];
                $upper_level = $level_progression_table[$level_index + 1];

                if ($character_level >= $lower_level && $character_level < $upper_level) {
                    return $weapon_progression_table[$level_index];
                }

                $level_index++;
            } while (true);
        }

        private function isRapidReloadCrossbowType($weapon_proficiency_id) {
            return $weapon_proficiency_id == LIGHT_CROSSBOW || $weapon_proficiency_id == GREAT_CROSSBOW || $weapon_proficiency_id == PISTOL_GRIP_CROSSBOW;
        }
    }
?>