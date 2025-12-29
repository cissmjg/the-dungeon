<?php

    const RACE_NONE = 1;
    const RACE_HUMAN = 2;
    const RACE_ELF = 3;	
    const RACE_DARK_ELF = 4;	
    const RACE_GRAY_ELF = 5;	
    const RACE_HIGH_ELF = 6;	
    const RACE_VALLEY_ELF = 7;	
    const RACE_WILD_ELF = 8;	
    const RACE_WOOD_ELF = 9;	
    const RACE_HALF_ELF_HIGH = 10;
    const RACE_HALF_ELF = 10;
    const RACE_DWARF = 11;	
    const RACE_GRAY_DWARF = 12;	
    const RACE_MOUNTAIN_DWARF = 13;	
    const RACE_HILL_DWARF = 14;	
    const RACE_KOROBOKURU = 15;	
    const RACE_DEEP_GNOME = 16;	
    const RACE_SURFACE_GNOME = 17;	
    const RACE_HALFLING = 18;	
    const RACE_HALF_ORC = 19;	
    const RACE_HENGEYOKAI = 20;	
    const RACE_SPIRIT_FOLK = 21;	
    const RACE_HALF_ELF_DARK = 22;
    const RACE_HALF_DROW = 22;
    const RACE_OOMPA_LLOOMPA = 23;
    const RACE_HALF_ELF_GRAY = 24;	
    const RACE_HALF_ELF_VALLEY = 25;	
    const RACE_HALF_ELF_WILD = 26;	
    const RACE_HALF_ELF_WOOD = 27;
 
function lookupRaceID($race_name) {
    switch($race_name) {
        case "NONE":	
            return RACE_NONE;
        case "Human":	
            return RACE_HUMAN;
        case "Elf":	
            return RACE_ELF;	
        case "Dark Elf":	
            return RACE_DARK_ELF;	
        case "Gray Elf":	
            return RACE_GRAY_ELF;	
        case "High Elf":	
            return RACE_HIGH_ELF;	
        case "Valley Elf":	
            return RACE_VALLEY_ELF;	
        case "Wild Elf":	
            return RACE_WILD_ELF;	
        case "Wood Elf":	
            return RACE_WOOD_ELF;	
        case "Half-Elf (High)":	
            return RACE_HALF_ELF_HIGH;	
        case "Dwarf":	
            return RACE_DWARF;	
        case "Gray Dwarf":	
            return RACE_GRAY_DWARF;	
        case "Mountain Dwarf":	
            return RACE_MOUNTAIN_DWARF;	
        case "Hill Dwarf":	
            return RACE_HILL_DWARF;	
        case "Korobokuru":	
            return RACE_KOROBOKURU;	
        case "Deep Gnome":	
            return RACE_DEEP_GNOME;	
        case "Surface Gnome":	
            return RACE_SURFACE_GNOME;	
        case "Halfling":	
            return RACE_HALFLING;	
        case "Half-Orc":	
            return RACE_HALF_ORC;	
        case "Hengeyokai":	
            return RACE_HENGEYOKAI;	
        case "Spirit Folk":	
            return RACE_SPIRIT_FOLK;	
        case "Half-Elf (Dark)":	
            return RACE_HALF_ELF_DARK;	
        case "Oompa Lloompa":	
            return RACE_OOMPA_LLOOMPA;	
        case "Half-Elf (Gray)":	
            return RACE_HALF_ELF_GRAY;	
        case "Half-Elf (Valley)":	
            return RACE_HALF_ELF_VALLEY;	
        case "Half-Elf (Wild)":	
            return RACE_HALF_ELF_WILD;	
        case "Half-Elf (Wood)":	
            return RACE_HALF_ELF_WOOD;     
        default:
            return -1;
    }
}

function getGenericRaceID($race_name) {
    switch($race_name) {
        case "NONE":	
            return RACE_NONE;
        case "Human":	
            return RACE_HUMAN;
        case "Elf":	
            return RACE_ELF;	
        case "Dark Elf":	
            return RACE_ELF;	
        case "Gray Elf":	
            return RACE_ELF;	
        case "High Elf":	
            return RACE_ELF;	
        case "Valley Elf":	
            return RACE_ELF;	
        case "Wild Elf":	
            return RACE_ELF;	
        case "Wood Elf":	
            return RACE_ELF;	
        case "Half-Elf (High)":	
            return RACE_ELF;	
        case "Dwarf":	
            return RACE_DWARF;	
        case "Gray Dwarf":	
            return RACE_DWARF;	
        case "Mountain Dwarf":	
            return RACE_DWARF;	
        case "Hill Dwarf":	
            return RACE_DWARF;	
        case "Korobokuru":	
            return RACE_DWARF;	
        case "Deep Gnome":	
            return RACE_SURFACE_GNOME;	
        case "Surface Gnome":	
            return RACE_SURFACE_GNOME;	
        case "Halfling":	
            return RACE_HALFLING;	
        case "Half-Orc":	
            return RACE_HALF_ORC;	
        case "Hengeyokai":	
            return RACE_HENGEYOKAI;	
        case "Spirit Folk":	
            return RACE_SPIRIT_FOLK;	
        case "Half-Elf (Dark)":	
            return RACE_HALF_ELF_DARK;	
        case "Oompa Lloompa":	
            return RACE_OOMPA_LLOOMPA;	
        case "Half-Elf (Gray)":	
            return RACE_ELF;	
        case "Half-Elf (Valley)":	
            return RACE_ELF;	
        case "Half-Elf (Wild)":	
            return RACE_ELF;	
        case "Half-Elf (Wood)":	
            return RACE_ELF;     
        default:
            return -1;
    }
}
?>