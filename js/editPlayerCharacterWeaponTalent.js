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

window.confirmPlayerCharacterSkillDelete = confirmPlayerCharacterSkillDelete;
window.submitAddWeaponTalentForm = submitAddWeaponTalentForm;
window.updateOffhandWeaponProficiencyId = updateOffhandWeaponProficiencyId;
window.submitAddSkillForm = submitAddSkillForm
