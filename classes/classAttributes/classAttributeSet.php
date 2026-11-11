<?php
require_once 'characterDetails.php';
require_once 'classAttribute.php';

class ClassAttributeSet implements IteratorAggregate, JsonSerializable {
    
    /** @var ClassAttribute[] */

    private array $classAttributeList = [];

    private $account_class_summary;
    public function getAccountClassSummary() {
        return $this->account_class_summary;
    }

    public function __construct($class_id, CharacterDetails $character_details) {
        $this->account_class_summary = $character_details->getCharacterClass($class_id);
    }

    public function addClassAttribute($description) {
        $class_attribute = new ClassAttribute($description);
        $this->classAttributeList[] = $class_attribute;

        return $class_attribute;
    }

    public function addClassAttributeWithFrequency($description, $frequency) {
        $class_attribute = $this->addClassAttribute($description);
        if (!empty($class_attribute)) {
            $class_attribute->setFrequency($frequency);
        }
    }

    public function addClassAttributeWithTotal($description, $total) {
        $class_attribute = $this->addClassAttribute($description);
        if (!empty($class_attribute)) {
            $class_attribute->setTotal($total);
        }
    }

    public function addClassAttributeWithCollection($description, $rm_collection, $total) {
        $class_attribute = $this->addClassAttribute($description);
        if (!empty($rm_collection)) {
            $class_attribute->setRmCollection($rm_collection);
        }

        if (!empty($total)) {
            $class_attribute->setTotal($total);
        }
    }

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->classAttributeList);
    }

    /** @return ClassAttribute[] */
    public function getAll(): array {
        return $this->classAttributeList;
    }

    public function isEmpty() {
        return count($this->classAttributeList) == 0;
    }
}
?>