<?php
class HealerClassAttributeBuilder extends ClassAttributeBuilder {

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 4) {
            $class_attribute_set->addClassAttribute('Detect molds, slimes, etc. at a 40-foot distance');
        }

        if ($character_level >= 8) {
            $class_attribute_set->addClassAttribute('Identify general types of potions');
        }

        if (character_level >= 12) {
            $class_attribute_set->addClassAttribute('Read magic-user and clerical scrolls');
        }
    }
}
?>