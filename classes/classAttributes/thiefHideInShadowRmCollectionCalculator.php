<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefHideInShadowTrapRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

        $this->attribute_metadata = $attribute_metadata;
        $thief_level = $this->getEquivalentHideInShadowThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_hide_in_shadow = $this->getBaseHideInShadowPct($thief_level);
        $this->getRmCollection()->add($base_hide_in_shadow);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_hide_in_shadow = $this->getRacialHideInShadowPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_hide_in_shadow);

        $dexterity_hide_in_shadow = $this->getDexterityHideInShadow();
        $this->getRmCollection()->add($dexterity_hide_in_shadow);

        $stealthy_hide_in_shadow = $this->getStealthyHideInShadow($player_character_skill_set);
        if (!empty($stealthy_hide_in_shadow)) {
            $this->getRmCollection()->add($stealthy_hide_in_shadow);
        }
    }

    private function getBaseHideInShadowPct($thief_level) {
        $base_hide_in_shadow_desc = 'Base Hide In Shadows';
        $base_hide_in_shadow_pct = $this->getThiefLookupFunction()->getBaseHideInShadows($thief_level);
        $base_hide_in_shadow = new RmFactor($base_hide_in_shadow_desc, $base_hide_in_shadow_pct);

        return $base_hide_in_shadow;
    }

    private function getRacialHideInShadowPct($thief_race, $thief_race_id) {
        $racial_adjustment_hide_in_shadow_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_hide_in_shadow_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForHideInShadows($thief_race_id);
        $racial_hide_in_shadow = new RmFactor($racial_adjustment_hide_in_shadow_desc, $racial_adjustment_hide_in_shadow_pct);

        return $racial_hide_in_shadow;
    }

    private function getDexterityHideInShadow() {
        $dexterity_adjustment_hide_in_shadow_desc = 'Dexterity Adjustment';
        $dexterity_adjustment_hide_in_shadow_pct = $this->attribute_metadata->getHideInShadowsDexterityAdjustment();
        $dexterity_hide_in_shadow = new RmFactor($dexterity_adjustment_hide_in_shadow_desc, $dexterity_adjustment_hide_in_shadow_pct);

        return $dexterity_hide_in_shadow;
    }

    private function getEquivalentHideInShadowThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $thief_level = $character_details->getLevelForClass(THIEF);
        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        $barbarian_level = $character_details->getLevelForClass(BARBARIAN);
        $hide_in_shadow_level = count($player_character_skill_set->getAllSkillInstances(HIDE));
        $has_stealthy = count($player_character_skill_set->getAllSkillInstances(STEALTHY)) > 0;

        if ($thief_level == 0 && $assassin_level == 0 && $barbarian_level == 0 && $has_stealthy) {
            return 2 + $hide_in_shadow_level;   // Stealthy skill allows non 'thieves' to Hide In Shadows as a 2nd level thief
        }

        if ($thief_level > 0) {
            return $hide_in_shadow_level + $thief_level;
        } else if ($barbarian_level > 0) {
            return $hide_in_shadow_level + $barbarian_level;
        } else if ($assassin_level > 2) {
            return $hide_in_shadow_level + ($assassin_level - 2);
        }

        return $hide_in_shadow_level;
    }

    private function getStealthyHideInShadow(PlayerCharacterSkillSet $player_character_skill_set) {
        $stealthy_hide_in_shadow = null;
        $stealthy_hide_in_shadow_pct = $this->getStealthySkillPct($player_character_skill_set);
        if ($stealthy_hide_in_shadow_pct > 0) {
            $stealthy_hide_in_shadow_desc = 'Stealthy (skill)';
            $stealthy_hide_in_shadow = new RmFactor($stealthy_hide_in_shadow_desc, $stealthy_hide_in_shadow_pct);
        }

        return $stealthy_hide_in_shadow;
    }

    private function getStealthySkillPct(PlayerCharacterSkillSet $player_character_skill_set) {
        $stealthy_hide_in_shadow_pct = 0;
        $stealthy_skill_count = count($player_character_skill_set->getAllSkillInstances(STEALTHY));
        if ($stealthy_skill_count > 0) {
            $stealthy_hide_in_shadow_pct = $stealthy_skill_count * 10;  // Each instance of Stealthy grants +10% Hide In Shadow
        }

        return $stealthy_hide_in_shadow_pct;
    }
}
?>
