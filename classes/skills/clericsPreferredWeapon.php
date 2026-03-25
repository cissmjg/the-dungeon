<?php
    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once 'candidateWeaponSkill.php';

    class ClericsPreferredWeapon extends CandidateWeaponSkill {
        protected function getSkillId() {
            return CLERICS_PREFERRED_WEAPON;
        }

        // Debug
        private $deity;
        private $deity_has_weapon;

        private $base_qualified;

        private $deity_weapon_id_list;

        private $clerics_preferred_weapons;
        public function getClericsPreferredWeapon() {
            return $this->clerics_preferred_weapons;
        }

        public function setClericsPreferredWeapon($clerics_preferred_weapon) {
            $this->clerics_preferred_weapons = $clerics_preferred_weapon;
        }

        protected function isCharacterQualified(\CharacterDetails $character_details, \PlayerCharacterSkillSet $player_character_skill_set) {
            $base_qualified = parent::isCharacterQualified($character_details, $player_character_skill_set);
            $this->base_qualified = $base_qualified;

            if (!$base_qualified) {
                return false;
            }

            $this->deity = $character_details->getDeity();
            $this->deity_has_weapon = array_key_exists($character_details->getDeity(), $this->clerics_preferred_weapons);

            // Ensure the deity has a preferred weapon(s)
            if (!array_key_exists($character_details->getDeity(), $this->clerics_preferred_weapons)) {
                return false;
            }

            // Get the deity's preferred weapon(s)
            $this->deity_weapon_id_list = $this->clerics_preferred_weapons[$character_details->getDeity()];

            return in_array($this->getWeaponProficiencyValue(), $this->deity_weapon_id_list);
        }

        public function dump() {
            $output  = parent::dump();
            $output .= 'Base qualified : ' . var_export($this->base_qualified, true)  . PHP_EOL;
            $output .= 'Deity : ' . $this->deity . PHP_EOL;
            $output .= 'Deity has weapon ' . var_export($this->deity_has_weapon, true) . PHP_EOL;
            $output .= 'Deity weapon list ' . print_r($this->deity_weapon_id_list, true) . PHP_EOL;
            $output .= 'Weapon proficiency Id ' . $this->getWeaponProficiencyValue() . PHP_EOL;

            return $output;
        }
    }
?>