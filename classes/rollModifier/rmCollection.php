<?php
require_once 'rmFactor.php';

class RmCollection implements IteratorAggregate {
    /** @var rmFactor[] */
    private array $rmFactorList = [];

    public function add(RmFactor $rmFactor): void {
        $this->rmFactorList[] = $rmFactor;
    }

    public function addAll(RmCollection $rmCollection) {
        foreach($rmCollection AS $rmFactor) {
            $this->add($rmFactor);
        }
    }

    /** @return RmFactor[] */
    public function getAll(): array {
        return $this->rmFactorList;
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->rmFactorList);
    }

    public function aggregate() {
        $rmFactorResult = 0;
        foreach($this->rmFactorList AS $rmFactor) {
            $rmFactorResult += $rmFactor->getRMData();
        }

        return $rmFactorResult;

    }
}
?>