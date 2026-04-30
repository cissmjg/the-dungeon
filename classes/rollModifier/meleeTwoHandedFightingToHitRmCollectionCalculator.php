<?php

    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';

    require_once __DIR__ . '/../../dbio/constants/twoWeaponFightingHand.php';
    require_once __DIR__ . '/../../dbio/constants/armorBulkFactor.php';

    class MeleeTwoWeaponFightingToHitRmCollectionCalculator extends MeleeToHitRmCollectionCalculator {

        protected $rm_weapon_collection;
        public function getRmCollection() {
            return $this->rm_weapon_collection;
        }

        private $two_weapon_fighting_hand = TWO_WEAPON_FIGHTING_UNKNOWN;
        public function getTwoWeaponFightingHand() {
            return $this->two_weapon_fighting_hand;
        }

        public function setTwoWeaponFightingHand($two_weapon_fighting_hand) {
            $this->two_weapon_fighting_hand = $two_weapon_fighting_hand;
        }

        public function aggregate() {
            $rmFactorResult = 0;
            foreach($this->rm_weapon_collection AS $rmFactor) {
                $rmFactorResult += $rmFactor->getRMData();
            }

            return $rmFactorResult;
        }

        public function getMainHandRmCollection() {
            return $this->getRmCollection();
        }

        private $rm_offhand_collection;
        public function getOffHandRmCollection() {
            return $this->rm_offhand_collection;
        }

        public function __construct() {
            $this->rm_weapon_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);

            $rm_armor_bulk_penalty = $this->calculateArmorBulkPenalty($character_details->getArmorBulkFactor());
            $rm_armor_bulk_penalty->setRmCategory(ROLL_MODIFIER_PENALTY);
            $this->rm_weapon_collection->add($rm_armor_bulk_penalty);

            $rm_dexterity_penalty = $this->calculateDexterityPenalty($attribute_metadata);
            $rm_dexterity_penalty->setRmCategory(ROLL_MODIFIER_PENALTY);
            $this->rm_weapon_collection->add($rm_dexterity_penalty);

            if ($this->two_weapon_fighting_hand == TWO_WEAPON_FIGHTING_OFF_HAND) {
                $rm_offhand_penalty_desc = "Off Hand Penalty";
                $rm_offhand_penalty_modifier = -2;
                $rm_offhand_penalty = new RmFactor($rm_offhand_penalty_desc, $rm_offhand_penalty_modifier);
                $rm_offhand_penalty->setRmCategory(ROLL_MODIFIER_PENALTY);
                $this->rm_weapon_collection->add($rm_offhand_penalty);
            }
        }

        private function calculateArmorBulkPenalty($armor_bulk_factor) {
            $rm_armor_bulk_penalty_desc = "Armor Bulk Penalty";
            $rm_armor_bulk_penalty_modifier = getTwoWeaponArmorBulkPenalty($armor_bulk_factor);
            return new RmFactor($rm_armor_bulk_penalty_desc, $rm_armor_bulk_penalty_modifier);
        }

        private function calculateDexterityPenalty(AttributeMetadata $attribute_metadata) {
            $rm_dexterity_penalty_desc = "Dexterity Penalty";
            $rm_dexterity_penalty_modifier = $attribute_metadata->getTwoWeaponDexterityPenalty();
            return new RmFactor($rm_dexterity_penalty_desc, $rm_dexterity_penalty_modifier);
        }
    }
?>