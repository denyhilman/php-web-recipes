// 0.0.1

function getMandatoryWysiwygValidator(formId, elementId) {
	return function() {
		var options = {};
		var validator = $('form#' + formId).validate()
		if ($('#' + elementId).val() == '') {
			options[elementId] = 'This field is required.';
			validator.showErrors(options);
			return false;
		} else {
			options[elementId] = '';
			validator.showErrors(options);
			return true;
		}
	}
}