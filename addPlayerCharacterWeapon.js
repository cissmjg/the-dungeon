import { populateWeaponLocation, craftStatusChanged, updateHitBonus, updateDamageBonus, populateDefaultHitDamageBonuses } from './playerCharacterWeaponIO.js';

// Attach to global scope
window.populateWeaponLocation = populateWeaponLocation;
window.craftStatusChanged = craftStatusChanged;
window.updateHitBonus = updateHitBonus;
window.updateDamageBonus = updateDamageBonus;
window.populateDefaultHitDamageBonuses = populateDefaultHitDamageBonuses;
