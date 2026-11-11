<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefClimbWallRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        $this->attribute_metadata = $attribute_metadata;

        $thief_level = $this->getEquivalentClimbWallThiefLevel($character_details);
        if ($thief_level < 1) {
            return;
        }
        
        $base_climb_wall = $this->getBaseClimbWallPct($thief_level);
        $this->getRmCollection()->add($base_climb_wall);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_climb_wall = $this->getRacialClimbWallPct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_climb_wall);

        $climb_wall_pct = $this->getClimbWallSkillPct($player_character_skill_set);
        if (!empty($climb_wall_pct)) {
            $this->getRmCollection()->add($climb_wall_pct);
        }
    }

    private function getBaseClimbWallPct($thief_level) {
        $base_climb_wall_desc = 'Base Climb Wall';
        $base_climb_wall_pct = $this->getThiefLookupFunction()->getBaseClimbWalls($thief_level);
        $base_climb_wall = new RmFactor($base_climb_wall_desc, $base_climb_wall_pct);

        return $base_climb_wall;
    }

    private function getRacialClimbWallPct($thief_race, $thief_race_id) {
        $racial_adjustment_climb_wall_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_climb_wall_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForClimbWalls($thief_race_id);
        $racial_climb_wall = new RmFactor($racial_adjustment_climb_wall_desc, $racial_adjustment_climb_wall_pct);

        return $racial_climb_wall;
    }

    private function getEquivalentClimbWallThiefLevel(CharacterDetails $character_details) {
        $thief_level = $character_details->getLevelForClass(THIEF);
        if ($thief_level > 0) {
            return $thief_level;
        }

        $barbarian_level = $character_details->getLevelForClass(BARBARIAN);
        if ($barbarian_level > 0) {
            return $barbarian_level;
        }

        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($assassin_level > 2) {
            return ($assassin_level - 2);
        }

        return -1;
    }

    private function getClimbWallSkillPct(PlayerCharacterSkillSet $player_character_skill_set) {
        $rm_climb_wall = null;
        $climb_wall_pct = 0;
        $climb_skill_count = count($player_character_skill_set->getAllSkillInstances(CLIMB));
        if ($climb_skill_count > 0) {
            $climb_wall_pct = $climb_skill_count * 20;  // Each instance of Climb grants +20% Climb Walls
            $rm_climb_wall = new RmFactor('Climb Wall Skill', $climb_wall_pct);
        }

        return $rm_climb_wall;
    }
}
?>
