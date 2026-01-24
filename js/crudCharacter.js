export function deleteCharacter(characterForm, characterDescription) {
	if (confirm("Are you sure you want to delete the character named '" + characterDescription + "'") == false) {
		return false;
	}

	characterForm.submit();
}

window.deleteCharacter = deleteCharacter;