<?php
class PaladinClassAttributeBuilder extends ClassAttributeBuilder {
    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();
        
        if ($character_level >= 1) {
            $class_attribute_set->addClassAttribute("Detect Evil up to 60'");
            $class_attribute_set->addClassAttribute('Protection from Evil 1" radius');
            $class_attribute_set->addClassAttributeWithTotal('All saving throws', '+2');
            $class_attribute_set->addClassAttribute('Immunity to disease');
            $class_attribute_text = sprintf('Heals %d hit points of damage', $character_level * 2);
            $class_attribute_set->addClassAttributeWithFrequency($class_attribute_text, '1/day');
            $cure_disease_freq = sprintf('%d/day', intdiv($character_level, 5) + 1);
            $class_attribute_set->addClassAttributeWithFrequency('Cure Disease', $cure_disease_freq);
        }

        if ($character_level >= 3) {
            $locale = 'en_US';
            $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);

            $class_attribute_text = sprintf("Affect undead as a %s level Cleric", $nf->format($character_level - 2));
            $class_attribute_set->addClassAttribute($class_attribute_text);
        }
    }
}
?>