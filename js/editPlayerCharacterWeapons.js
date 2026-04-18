export function confirmPlayerCharacterWeaponDelete(formId, playerCharacterWeaponId, playerCharacterWeaponIdValue, weaponDescription) {
    if (confirm("Are you sure you want to delete the weapon named '" + weaponDescription + "'") == false) {
        return false;
    }

    const jqPlayerCharacterWeaponId = '#' + playerCharacterWeaponId;
    const jqFormId = '#' + formId;

    $(jqPlayerCharacterWeaponId).val(playerCharacterWeaponIdValue);
    $(jqFormId).submit();
}

window.confirmPlayerCharacterWeaponDelete = confirmPlayerCharacterWeaponDelete;
