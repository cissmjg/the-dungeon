<?php

class ElvenCavalierClassAttributeBuilder extends ClassAttributeBuilder {

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();
        
        if ($character_level >= 1) {
            $thrown_pct = 15 - ($character_level - 1);  // 15% = 100% - 85% (Unearthed Arcana wording is poor)
            $thrown_pct_text = sprintf("%d%%", $thrown_pct);
            $class_attribute_set->addClassAttributeWithTotal('Chance of being thrown/damage from throw from saddle', $thrown_pct_text);

            $class_attribute_set->addClassAttribute('90% resistence to magical mind attacks');
            $class_attribute_set->addClassAttribute('+2 save vs illusion spells');
            $class_attribute_set->addClassAttribute('Can parry with weapons of proficiency more effectively than fighters can');

            // If the Elven Cavalier is a Good Aignment
            if (str_contains($this->getCharacterDetails()->getAlignment(), 'Good')) {
                $class_attribute_set->addClassAttribute('Radiates Protection from Fear aura 1" radius');
                $class_attribute_set->addClassAttribute('Can function at negative hit point totals');
                $class_attribute_set->addClassAttribute('Gain an additional d4 points for each full week of normal healing');
            }
        }

        if ($character_level >= 4 && $this->getCharacterDetails()->getGender() == 'Female') {
            $class_attribute_set->addClassAttribute('Can ride and handle unicorns');
        }

        if ($character_level >= 5) {
            $class_attribute_set->addClassAttribute('Can urge mount to +2" movement speed for up to 6 turns');
        }

        if ($character_level >= 7) {
            $class_attribute_set->addClassAttribute('Can ride and handle giant eagles (specially bred mounts)');
        }

        if ($character_level >= 8) {
            $class_attribute_set->addClassAttribute('Can ride and handle a pegasus');
        }

        if ($character_level >= 10) {
            $class_attribute_set->addClassAttribute('Can ride and handle griffons and/or hippogriffs');
        }

        if ($character_level >= 10) {
            $class_attribute_set->addClassAttribute('Can ride and handle dragons');
        }
    }
}
?>
