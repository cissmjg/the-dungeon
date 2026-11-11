<?php

class ArcherClassAttributeBuilder extends ClassAttributeBuilder {

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 8) {
            $class_attribute_set->addClassAttribute('Can craft arrows that can be magicked to become Arrows of Slaying');
        }
    }
}
?>
