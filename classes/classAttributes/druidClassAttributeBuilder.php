<?php
class DruidClassAttributeBuilder extends ClassAttributeBuilder {

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 1) {
            $class_attribute_set->addClassAttribute('Gain a new language: Druidic Cant');
            $class_attribute_set->addClassAttributeWithTotal('Saving throw bonus for fire and lightning', '+2');
        }

        if ($character_level >= 3) {
            $class_attribute_set->addClassAttribute('Identification of plant, animal type &amp; pure water');
            $class_attribute_set->addClassAttribute('Pass through overgrown areas without trace at normal movement rate');
        }

        if (character_level >= 7) {
            $class_attribute_set->addClassAttributeWithTotal('Immunity from woodland creature charm', '100%');
            $class_attribute_set->addClassAttributeWithFrequency('Shape change : reptile, bird, mammal (removes 10% to 60% hit points of damage)', '3/day');
        }

        $new_language_count = sprintf("Additional available languages (%d)", $character_level - 2);
        $class_attribute_set->addClassAttribute($new_language_count);
    }
}
?>