<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefHearNoiseRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        $this->attribute_metadata = $attribute_metadata;

        $thief_level = $this->getEquivalentHearNoiseThiefLevel($character_details);
        if ($thief_level < 1) {
            return;
        }
        
        $base_hear_noise = $this->getBaseHearNoisePct($thief_level);
        $this->getRmCollection()->add($base_hear_noise);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_hear_noise = $this->getRacialHearNoisePct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_hear_noise);
    }

    private function getBaseHearNoisePct($thief_level) {
        $base_hear_noise_desc = 'Base Hear Noise';
        $base_hear_noise_pct = $this->getThiefLookupFunction()->getBaseHearNoise($thief_level);
        $base_hear_noise = new RmFactor($base_hear_noise_desc, $base_hear_noise_pct);

        return $base_hear_noise;
    }

    private function getRacialHearNoisePct($thief_race, $thief_race_id) {
        $racial_adjustment_hear_noise_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_hear_noise_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForHearNoise($thief_race_id);
        $racial_hear_noise = new RmFactor($racial_adjustment_hear_noise_desc, $racial_adjustment_hear_noise_pct);

        return $racial_hear_noise;
    }

    private function getEquivalentHearNoiseThiefLevel(CharacterDetails $character_details) {
        $thief_level = $character_details->getLevelForClass(THIEF);
        if ($thief_level > 0) {
            return $thief_level;
        }

        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($assassin_level >= 3) {
            return ($assassin_level - 2);
        }

        return -1;
    }
}
?>
