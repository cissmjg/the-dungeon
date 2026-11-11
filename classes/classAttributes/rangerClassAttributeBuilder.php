<?php
class RangerClassAttributeBuilder extends ClassAttributeBuilder {
    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 1) {
            $class_attribute_set->addClassAttributeWithTotal('Additional points of damage when fighting "giant class" creatures', $character_level);
            $class_attribute_set->addClassAttribute('Surprise opponents 50% of the time');
            $class_attribute_set->addClassAttribute('Surprised on 1 on d6');
            $tracking_pct_total = 10 + (character_level * 10);  // UA page 21
            if ($tracking_pct_total > 110) {
                $tracking_pct_total = 110;
            }
            $tracking_pct_total = sprintf("%d%%", $tracking_pct_total);
            $class_attribute_set->addClassAttributeWithTotal('Base Outdoor Tracking', $tracking_pct_total);
        }

        if ($character_level >= 10) {
            $class_attribute_set->addClassAttribute('Use all non-written magic items which pertain to clairaudience, clairvoyance, ESP, and telepathy');
        }
    }
}
?>