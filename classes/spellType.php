<?php

class SpellType implements JsonSerializable {
    private $spell_type_id;
    private $spell_type_name;

    public function __construct($spell_type_id, $spell_type_name) {
        $this->spell_type_id = $spell_type_id;
        $this->spell_type_name = $spell_type_name;
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getSpellTypeId() {
        return $this->spell_type_id;
    }

    public function getSpellTypeName() {
        return $this->spell_type_name;
    }
}
?>