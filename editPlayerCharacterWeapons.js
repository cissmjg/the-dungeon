import { buildURL, addParameter } from './RestHelper.js';

export function populateWeaponList(weaponListName, weaponSearchTextboxName) {
    const weaponQueryPattern = '#' + weaponSearchTextboxName;
    const weaponList = "#" + weaponListName;
    let weaponQueryAPI = buildURL('getWeaponProficiencyByPattern');
    weaponQueryAPI = addParameter(weaponQueryAPI, TEXT_INPUT, $(weaponQueryPattern).val());
    $(weaponList).empty();
    $.getJSON(weaponQueryAPI,
        function(data, textStatus, jqXHR) {
            $(weaponList).append(new Option("[Select a Weapon]", 0));
            $.each(data, function(i, weapon_proficiency_object) {
                $(weaponList).append(new Option(weapon_proficiency_object.weapon_proficiency_name, weapon_proficiency_object.weapon_proficiency_id));
            });
        }
    );
    $(weaponList).show();
}

export function getWeaponDetail(addWeaponFormId, weaponCatalogId) {
    const jqAddWeaponFormName = "#" + addWeaponFormId;
    const jqWeaponCatalogIdElementName = "#" + weaponCatalogId;
    $(jqAddWeaponFormName).submit();
}
