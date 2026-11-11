<?php

class ArcherRangerClassAttributeBuilder extends ClassAttributeBuilder {

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();
        
        if ($character_level >= 1) {
            $damage_bonus = sprintf("+%d damage when fighting giant class creatures", $character_level);
            $class_attribute_set->addClassAttribute($damage_bonus);

            $class_attribute_set->addClassAttribute('Surprise opponents 50% of the time (d6, score 1 through 3)');

            $tracking_pct = 10 + ($character_level * 10);
            $tracking_abaility_text = sprintf('%d%%', $tracking_pct);
            $class_attribute_set->addClassAttributeWithTotal('Tracking', $tracking_abaility_text);
        }

        if ($character_level >= 8) {
            $class_attribute_set->addClassAttribute('Can craft arrows that can be magicked to become Arrows of Slaying');
        }
    }
}
?>