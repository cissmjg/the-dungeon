<?php

const WEAPON_SUBTYPE_MISC_MELEE = 1;
const WEAPON_SUBTYPE_MISC_MISSILE = 2;
const WEAPON_SUBTYPE_BOW = 3;
const WEAPON_SUBTYPE_CROSSBOW = 4;
const WEAPON_SUBTYPE_ARROW = 5;
const WEAPON_SUBTYPE_QUARREL = 6;
const WEAPON_SUBTYPE_AXE = 7;
const WEAPON_SUBTYPE_POLE_ARM = 8;
const WEAPON_SUBTYPE_CLUB = 9;
const WEAPON_SUBTYPE_ONE_HANDED_SWORD = 10;
const WEAPON_SUBTYPE_HAMMER = 11;
const WEAPON_SUBTYPE_LANCE = 12;
const WEAPON_SUBTYPE_SLING = 13;
const WEAPON_SUBTYPE_BULLET = 14;
const WEAPON_SUBTYPE_BLOW_GUN = 15;
const WEAPON_SUBTYPE_NEEDLE = 16;
const WEAPON_SUBTYPE_TWO_HANDED_SWORD = 17;
const WEAPON_SUBTYPE_TALENT = 18;

function getWeaponSubtypeDescription($weapon_subtype) {
    switch($weapon_subtype) {
        case WEAPON_SUBTYPE_MISC_MELEE:
            return "Misc. Melee";
        case WEAPON_SUBTYPE_MISC_MISSILE:
            return "Misc. Missile";
        case WEAPON_SUBTYPE_BOW:
            return "Bow";
        case WEAPON_SUBTYPE_CROSSBOW:
            return "Crossbow";
        case WEAPON_SUBTYPE_ARROW:
            return "Arrow";
        case WEAPON_SUBTYPE_QUARREL:
            return "Quarrel";
        case WEAPON_SUBTYPE_AXE:
            return "Axe";
        case WEAPON_SUBTYPE_POLE_ARM:
            return "Pole Arm";
        case WEAPON_SUBTYPE_CLUB:
            return "Club";
        case WEAPON_SUBTYPE_ONE_HANDED_SWORD:
            return "One Handed Sword";
        case WEAPON_SUBTYPE_HAMMER:
            return "Hammer";
        case WEAPON_SUBTYPE_LANCE:
            return "Lance";
        case WEAPON_SUBTYPE_SLING:
            return "Sling";
        case WEAPON_SUBTYPE_BULLET:
            return "Bullet";
        case WEAPON_SUBTYPE_BLOW_GUN:
            return "Blow gun";
        case WEAPON_SUBTYPE_NEEDLE:
            return "Needle";
        case WEAPON_SUBTYPE_TWO_HANDED_SWORD:
            case "Two Handed Sword";
        case WEAPON_SUBTYPE_TALENT:
            case "Talent";
        default:
            return "UNKNOWN";
    }
}
?>