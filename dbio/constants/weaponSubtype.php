<?php

const MISC_MELEE = 1;
const MISC_MISSILE = 2;
const BOW = 3;
const CROSSBOW = 4;
const ARROW = 5;
const QUARREL = 6;
const AXE = 7;
const POLE_ARM = 8;
const CLUB = 9;
const ONE_HANDED_SWORD = 10;
const HAMMER = 11;
const LANCE = 12;
const SLING = 13;
const BULLET = 14;
const BLOW_GUN = 15;
const NEEDLE = 16;
const TWO_HANDED_SWORD = 17;
const TALENT = 18;

function getWeaponSubtypeDescription($weapon_subtype) {
    switch($weapon_subtype) {
        case MISC_MELEE:
            return "Misc. Melee";
        case MISC_MISSILE:
            return "Misc. Missile";
        case BOW:
            return "Bow";
        case CROSSBOW:
            return "Crossbow";
        case ARROW:
            return "Arrow";
        case QUARREL:
            return "Quarrel";
        case AXE:
            return "Axe";
        case POLE_ARM:
            return "Pole Arm";
        case CLUB:
            return "Club";
        case ONE_HANDED_SWORD:
            return "One Handed Sword";
        case HAMMER:
            return "Hammer";
        case LANCE:
            return "Lance";
        case SLING:
            return "Sling";
        case BULLET:
            return "Bullet";
        case BLOW_GUN:
            return "Blow gun";
        case NEEDLE:
            return "Needle";
        case TWO_HANDED_SWORD:
            case "Two Handed Sword";
        case TALENT:
            case "Talent";
        default:
            return "UNKNOWN";
    }
}
?>