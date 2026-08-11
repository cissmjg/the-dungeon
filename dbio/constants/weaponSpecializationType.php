<?php
enum WeaponSpecializationType: int {
    case None  = 1;
    case Melee = 2;
    case Missile = 3;

    public function getDescription(): string
    {
        return match($this) {
            self::None => 'None',
            self::Melee => 'Melee',
            self::Missile => 'Missile',
        };
    }
}
?>