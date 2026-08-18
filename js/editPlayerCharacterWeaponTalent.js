import { confirmPlayerCharacterSkillDelete, submitAddSkillForm } from './candidateSkill.js';

export function submitAddWeaponTalentForm(form_id, skill_catalog_element_id, skill_catalog_value, weapon2_element_id) {

    const TWO_WEAPON_FIGHTING_SKILL_ID = 162;
    const jqAddWeaponTalentForm = $('#' + form_id);
    const jqSkillCatalogElement = $('#' + skill_catalog_element_id);
    const jqWeapon2Element = $('#' + weapon2_element_id);

    if (
            skill_catalog_value == TWO_WEAPON_FIGHTING_SKILL_ID && jqWeapon2Element.val() == '-1' ||
            skill_catalog_value == TWO_WEAPON_FIGHTING_SKILL_ID && jqWeapon2Element.val() == ''
       ) 
    {
        alert('Please select a 2nd weapon');
        return false;
    }

    jqSkillCatalogElement.val(skill_catalog_value);
    jqAddWeaponTalentForm.submit();
}

export function updateOffhandWeaponProficiencyId(weapon2ProficiencyId, oneHandWeaponListId) {
    const jqWeapon2ProficiencyElement = $('#' + weapon2ProficiencyId);
    const jqOneHandWeaponList = $('#' + oneHandWeaponListId);

    jqWeapon2ProficiencyElement.val(jqOneHandWeaponList.val());
}

export function updateWeaponSpecializationTypeId(weapon_specialization_type_select_element, update_weapon_specialization_type_element_id, weapon_specialization_type_none) {
    const jqSelectedWeaponSpecializationType = $(weapon_specialization_type_select_element).val();

    if (jqSelectedWeaponSpecializationType == weapon_specialization_type_none) {
        alert('Please select a specialization type');
        return false;
    }

    let jqWeaponSpecializationTypeIdElement = $('#' + update_weapon_specialization_type_element_id);
    jqWeaponSpecializationTypeIdElement.val(jqSelectedWeaponSpecializationType);
    return true;
}

export function addWeaponSpecializationSkill(form_id, skill_catalog_element_id, skill_catalog_value, weapon2_element_id, skill_icon_container, update_weapon_specialization_type_element_id, weapon_specialization_type_none) {
    
    let skill_container = $(skill_icon_container).parent(); 
    let weapon_specialization_type_select_element = skill_container.find("select");
    if(updateWeaponSpecializationTypeId(weapon_specialization_type_select_element, update_weapon_specialization_type_element_id, weapon_specialization_type_none)) {
        submitAddWeaponTalentForm(form_id, skill_catalog_element_id, skill_catalog_value, weapon2_element_id);
    }
}

window.addWeaponSpecializationSkill = addWeaponSpecializationSkill;
window.confirmPlayerCharacterSkillDelete = confirmPlayerCharacterSkillDelete;
window.submitAddWeaponTalentForm = submitAddWeaponTalentForm;
window.updateOffhandWeaponProficiencyId = updateOffhandWeaponProficiencyId;
window.updateWeaponSpecializationTypeId = updateWeaponSpecializationTypeId;
window.submitAddSkillForm = submitAddSkillForm
