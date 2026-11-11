<?php

require_once 'characterDetails.php';
require_once 'classAttributeSet.php';

abstract class ClassAttributeBuilder {

    protected $class_attribute_set;
    public function getClassAttributeSet() {
        return $this->class_attribute_set;
    }

    private $account_class_summary;
    public function getAccountClassSummary() {
        return $this->account_class_summary;
    }

    private $character_details;
    public function getCharacterDetails() {
        return $this->character_details;
    }

    protected function __construct($class_id, CharacterDetails $character_details) {
        $this->account_class_summary = $character_details->getCharacterClass($class_id);
        $this->class_attribute_set = new ClassAttributeSet($class_id, $character_details);
        $this->character_details = $character_details;
    }

    abstract function init();

    public function isEmpty() {
        return $this->getClassAttributeSet()->isEmpty();
    }
}
?>