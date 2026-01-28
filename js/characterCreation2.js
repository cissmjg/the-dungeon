export function submitTheForm(form_id, characterClassId) {
	let theForm = document.getElementById(form_id);

	theForm.elements[characterClassId].value = "None";

	if (theForm == null) {
		alert("Cannot find form with ID [" + form_id + "]");
	} else {
		theForm.submit();
	}
}

export function disableFinalize(finalizeButton) {
	finalizeButton.disabled = 'true';
	finalizeButton.style.opacity = 0.5;
	finalizeButton.style.cursor = 'not-allowed';
}

window.submitTheForm = submitTheForm;
window.disableFinalize = disableFinalize;