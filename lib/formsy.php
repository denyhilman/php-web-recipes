<?php // 0.2.4

/* requires
* bootstrap-1.4.0 subfolder
* jquery-1.7.1 subfolder
* jquery-ui-1.8.16 subfolder
* jquery-validate-1.9.0 subfolder
* jwysiwyg-0.97 subfolder
* jwysiwyg-editor.css file
*/
require_once('isEmail.php');
require_once('stripMagicQuotes.php');

/*
Future Tasks:
* Integrate timepicker @ http://jonthornton.github.com/jquery-timepicker/

Example Form Usage
==================
require_once('includes/formsy.php');
require_once('includes/getBrowserUrl.php');
require_once('includes/mustache.php');
require_once('includes/stripMagicQuotes.php');

$elts = array(
	formsy_addDropdown('title', 'Title:', true, array(
		array('', 'Select'),
		array('Mister', 'Mr'),
		array('Missus', 'Mrs')
	)),
	formsy_addTextField('firstName', 'First Name:', true),
	formsy_addTextField('lastName', 'Last Name:', true),
	formsy_addDigitField('age', 'Age:', false),
	formsy_addEmailField('email', 'Email:', true),
	formsy_addDatePicker('dob', 'Date of Birth:', false),
	formsy_addAutocomplete('favLanguage', 'Favourite Language:', false, array(
		'ActionScript', 'AppleScript', 'ASP', 'Bash', 'BASIC', 'Boo', 'C','C++','Clojure','COBOL', 'CoffeeScript', 'ColdFusion', 'D', 'Erlang', 'F#', 'Fortran', 'Groovy', 'Haskell', 'Java', 'JavaScript', 'Lisp', 'Ocaml', 'Perl', 'PHP', 'Python', 'Ruby', 'Scala', 'Scheme'
	), array(
		'requireSelectionFromList' => true
	)),
	formsy_addWysiwyg('info', 'Information:', true)
);

if (formsy_isSubmitted($_POST) && formsy_isValidated($elts, $_POST)) {

	// insert into database
	// $insertId = formsy_insertValuesToDb($elts, $_POST, $db, 'surveyTable');

	// send email
	// $message = formsy_renderHtmlValueTable($elts, $_POST);
	// mailer_send('to@someone.com', 'subject line', $message, 'From Someone', 'from@someone.com', 'text/html');

	echo formsy_renderHtmlValueTable($elts, $_POST);
	
	echo 'Thank you message goes here!';

} else {
	$formName = 'addForm';

	$formsyHead = formsy_renderHead(array(
		'name' => $formName,
		'jQueryValidateOnLoad' => formsy_isSubmitted($_POST),
		'elements' => $elts
	));

	$formsyForm = formsy_renderInputForm(array(
		'name' => $formName,
		'actionUrl' => getBrowserUrl(),
		'method' => 'POST',
		'submitButtonText' => 'Add',
		'elements' => $elts,
		'values' => $_POST
	));

	echo mustache_renderString('<!DOCTYPE html><html><head>{{{formsyHead}}}</head><body><div class="container"><div class="page-header"><h1>Form Title</h1></div>{{{formsyForm}}}</div></body></html>', array(
		'formsyHead' => $formsyHead,
		'formsyForm' => $formsyForm
	));
}

For more examples, look at the test/formsyTest.php
*/

function formsy_addAutocomplete($name, $label, $mandatory, $items, $options = array()) {
	return array_merge($options, array(
		'items' => $items,
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => 'formsy_renderAutocompleteHead',
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderAutocompleteInputElement',
		'requires' => array('jQueryUi'),
		'validationFunctions' => array('formsy_validateMandatoryField', 'formsy_validateNonMandatoryField', 'formsy_validateAutocomplete')
	));
}

function formsy_addCheckBox($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderCheckBoxDbValue',
		'renderHtmlValueFunction' => 'formsy_renderCheckBoxHtmlValue',
		'renderInputElementFunction' => 'formsy_renderCheckBoxInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField')
	));
}

function formsy_addDatePicker($name, $label, $mandatory, $options = array()) {
	// $options can supply jQueryUiDateFormat & phpDateFormat
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => 'formsy_renderDatePickerHead',
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderDatePickerHtmlValue',
		'renderInputElementFunction' => 'formsy_renderDatePickerInputElement',
		'requires' => array('jQueryUi'),
		'validationFunctions' => array('formsy_validateMandatoryField', 'formsy_validateNonMandatoryField', 'formsy_validateDatePicker')
	));
}

function formsy_addDigitField($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderDigitInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField', 'formsy_validateNonMandatoryField', 'formsy_validateDigitField')
	));
}

function formsy_addDropDown($name, $label, $mandatory, $items, $options = array()) {
	return array_merge($options, array(
		'items' => $items,
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderDropDownHtmlValue',
		'renderInputElementFunction' => 'formsy_renderDropdownInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField', 'formsy_validateNonMandatoryField', 'formsy_validateDropdown')
	));
}

function formsy_addEmailField($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderEmailInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField', 'formsy_validateNonMandatoryField', 'formsy_validateEmailField')
	));
}

function formsy_addPasswordField($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderPasswordInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField')
	));
}

function formsy_addTextArea($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderTextAreaInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField')
	));
}

function formsy_addTextField($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => null,
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderGenericHtmlValue',
		'renderInputElementFunction' => 'formsy_renderTextInputElement',
		'validationFunctions' => array('formsy_validateMandatoryField')
	));
}

function formsy_addWysiwyg($name, $label, $mandatory, $options = array()) {
	return array_merge($options, array(
		'mandatory' => $mandatory,
		'name' => $name,
		'label' => $label,
		'renderElementHeadFunction' => 'formsy_renderWysiwygHead',
		'renderDbValueFunction' => 'formsy_renderGenericDbValue',
		'renderHtmlValueFunction' => 'formsy_renderWysiwygHtmlValue',
		'renderInputElementFunction' => 'formsy_renderWysiwygInputElement',
		'requires' => array('jwysiwyg'),
		'validationFunctions' => array('formsy_validateMandatoryField')
	));
}

function formsy_buildClassAttribute($elt, $additionalClasses = array()) {
	$classes = isset($elt['classes']) ? $elt['classes'] : array();

	if ($elt['mandatory'] === true) {
		$classes[] = 'required';
	}
	foreach ($additionalClasses as $value) {
		$classes[] = $value;
	}

	return sizeof($classes) == 0 ? '' : ' class="' . implode(' ', $classes) . '"';
}

function formsy_insertValuesToDb($elts, $values, $db, $table, $additionalValues = array()) {
	$dbFields = array();
	$dbValues = array();

	foreach($elts as $elt) {
		$name = $elt['name'];
		if (isset($values[$name])) {
			$dbFields[] = "$name=?";
			$dbValues[] = renderDbValue($elt, $values);
		}
	}
	foreach($additionalValues as $key => $value) {
		$dbFields[] = "$key=?";
		$dbValues[] = $value;
	}

	$fieldString = implode(',', $dbFields);
	try {
		$db->execute("INSERT INTO $table SET $fieldString", $dbValues);
		return $db->Insert_ID();
	} catch (Exception $e) {
		print_r($e);
	}
}

function formsy_isSubmitted($values) {
	return sizeof($values) > 0;
}

function formsy_isValidated($elts, $values) {
	foreach ($elts as $elt) {
		$result = formsy_validateField($elt, $values);
		if ($result === false) {
			return false;
		}
	}
	return true;
}

function formsy_parseDropDownItem($item) {
	if (gettype($item) === 'array') {
		$optionValue = $item[0];
		$optionText = $item[1];
	} else {
		$optionValue = $optionText = $item;
	}
	return array($optionValue, $optionText);
}

function formsy_renderAutocompleteHead($options, $elt) {
	$name = $elt['name'];
	$itemJson = json_encode($elt['items']);
	$lines = array("var {$name}AutocompleteList = $itemJson;");

	if (isset($elt['requireSelectionFromList']) && $elt['requireSelectionFromList'] === true) {
		$lines[] = "$.validator.addMethod('{$name}RequireSelectionFromListValidator',";
		$lines[] = "  function(value, element) {";
		if ($elt['mandatory'] === false) {
			$lines[] = "    if (value == '') return true;";
		}
		$lines[] = "    for (var key in {$name}AutocompleteList) {";
		$lines[] = "      if ({$name}AutocompleteList[key] == value) {";
		$lines[] = "        return true;";
		$lines[] = "      }";
		$lines[] = "    }";
		$lines[] = "    return false;";
		$lines[] = "  },";
		$lines[] = "  'Please select a value from the list.'";
		$lines[] = ");";
	}
	$lines[] = "$('#{$name}').autocomplete({ minLength: 0, source: {$name}AutocompleteList });";

	return implode("\n", $lines);
}

function formsy_renderAutocompleteInputElement($elt, $values) {
	$name = $elt['name'];
	$additionalClasses = isset($elt['requireSelectionFromList']) && $elt['requireSelectionFromList'] === true ? array("{$name}RequireSelectionFromListValidator") : array();

	return formsy_renderTextInputElement($elt, $values, 'text', $additionalClasses);
}

function formsy_renderCheckBoxDbValue($elt, $values) {
	$name = $elt['name'];
	return isset($values[$name]) ? 1 : 0;
}

function formsy_renderCheckBoxHtmlValue($elt, $values) {
	$name = $elt['name'];
	return isset($values[$name]) ? 'Ticked' : 'Unticked';
}

function formsy_renderCheckBoxInputElement($elt, $values) {
	$name = $elt['name'];
	$checked = isset($values[$name]) ? ' checked' : '';

	$classAttr = formsy_buildClassAttribute($elt);

	return "<input{$classAttr} type=\"checkbox\" id=\"$name\" name=\"$name\"$checked />";
}

function formsy_renderDatePickerHead($options, $elt) {
	$name = $elt['name'];
	$jQueryUiDateFormat = isset($elt['jQueryUiDateFormat']) ? $elt['jQueryUiDateFormat'] : 'd M yy';
	return "$('#{$name}_visible').datepicker({ altField: '#$name', altFormat: 'yy-mm-dd', dateFormat: '$jQueryUiDateFormat' });";
}

function formsy_renderDatePickerInputElement($elt, $values) {
	$name = $elt['name'];
	$hiddenValue = isset($values[$name]) ? ' value="' . htmlentities($values[$name]) . '"' : '';
	$visibleValue = isset($values["{$name}_visible"]) ? ' value="' . htmlentities($values["{$name}_visible"]) . '"' : '';

	$classAttr = formsy_buildClassAttribute($elt, array('date'));

	$hiddenElement = "<input type=\"hidden\" type=\"hidden\" id=\"$name\" name=\"$name\"{$hiddenValue} />";
	$visibleElement = "<input{$classAttr} type=\"text\" id=\"{$name}_visible\" name=\"{$name}_visible\"{$visibleValue} />";

	return $hiddenElement . $visibleElement;
}

function formsy_renderDatePickerHtmlValue($elt, $values) {
	$name = $elt['name'];
	$phpDateFormat = isset($elt['phpDateFormat']) ? $elt['phpDateFormat'] : 'j M Y';
	return isset($values[$name]) && $values[$name] !== '' ? date($phpDateFormat, strtotime($values[$name])) : '';
}

function formsy_renderDbValue($elt, $values) {
	return $elt['renderDbValueFunction']($elt, $values);
}

function formsy_renderDigitInputElement($elt, $values) {
	return formsy_renderTextInputElement($elt, $values, 'text', array('digits'));
}

function formsy_renderDropdownHtmlValue($elt, $values) {
	$name = $elt['name'];
	if (isset($values[$name])) {
		$value = $values[$name];

		foreach($elt['items'] as $item) {
			list($optionValue, $optionText) = formsy_parseDropDownItem($item);
			if ($optionValue === $value) {
				return htmlentities($optionText);
			}
		}
	} else {
		return '';
	}
}

function formsy_renderDropdownInputElement($elt, $values) {
	$name = $elt['name'];
	$postValue = isset($values[$name]) ? $values[$name] : null;
	$classAttr = formsy_buildClassAttribute($elt);

	$lines = array();
	$lines[] = "<select{$classAttr} name=\"$name\" id=\"$name\">";

	foreach($elt['items'] as $item) {
		list($optionValue, $optionText) = formsy_parseDropDownItem($item);
		$selected = ($postValue !== null && $optionValue === $postValue) ? ' selected' : '';
		$lines[] = "<option value=\"$optionValue\"{$selected}>$optionText</option>";
	}

	$lines[] = '</select>';
	return implode("\n", $lines);
}

function formsy_renderElementHead($options, $elt) {
	$renderElementHeadFunction = $elt['renderElementHeadFunction'];
	return $renderElementHeadFunction !== null ? $renderElementHeadFunction($options, $elt) : '';
}

function formsy_renderEmailInputElement($elt, $values) {
	return formsy_renderTextInputElement($elt, $values, 'text', array('email'));
}

function formsy_renderGenericDbValue($elt, $values) {
	$name = $elt['name'];
	return isset($values[$name]) ? $values[$name] : null;
}

function formsy_renderGenericHtmlValue($elt, $values) {
	$name = $elt['name'];
	return isset($values[$name]) ? htmlentities($values[$name]) : '';
}

function formsy_renderHead($options = array()) {
	/*
	$options = array(
		'name' => '',
		'useDevFiles' => false,
		'jQueryValidateOnLoad' => true,
		'elements' => $elts
	);
	*/

	$options = array_merge(array(
		'useDevFiles' => false
	), $options);

	$elts = $options['elements'];

	$lines = array();
	if ($options['useDevFiles'] === true) {
		$lines[] = '<link rel="stylesheet" href="includes/bootstrap-1.4.0/bootstrap.css" />';
		$lines[] = '<script src="includes/jquery-1.7.1/jquery-1.7.1.js"></script>';
		$lines[] = '<script src="includes/jquery-validate-1.9.0/jquery.validate.js"></script>';
	} else {
		$lines[] = '<link rel="stylesheet" href="includes/bootstrap-1.4.0/bootstrap.min.css" />';
		$lines[] = '<script src="includes/jquery-1.7.1/jquery-1.7.1.min.js"></script>';
		$lines[] = '<script src="includes/jquery-validate-1.9.0/jquery.validate.min.js"></script>';
	}

	// create requires array
	$requires = array();
	foreach($elts as $elt) {
		if (isset($elt['requires'])) {
			foreach($elt['requires'] as $requiredComponent) {
				if (!in_array($requiredComponent, $requires)) {
					$requires[] = $requiredComponent;
				}
			}
		}
	}

	if (in_array('jQueryUi', $requires)) {
		$lines[] = '<link rel="stylesheet" href="includes/jquery-ui-1.8.16/redmond/jquery-ui-1.8.16.css" />';
		$lines[] = '<script src="includes/jquery-ui-1.8.16/jquery-ui-1.8.16.min.js"></script>';
	}

	if (in_array('jwysiwyg', $requires)) {
		$lines[] = '<link href="includes/jwysiwyg-0.97/jquery.wysiwyg.css" rel="stylesheet" type="text/css" />';
		$lines[] = '<script type="text/javascript" src="includes/jwysiwyg-0.97/jquery.wysiwyg.js"></script>';
		$lines[] = '<script type="text/javascript" src="includes/jwysiwyg-0.97/controls/wysiwyg.image.js"></script>';
		$lines[] = '<script type="text/javascript" src="includes/jwysiwyg-0.97/controls/wysiwyg.link.js"></script>';
		$lines[] = '<script type="text/javascript" src="includes/jwysiwyg-0.97/controls/wysiwyg.table.js"></script>';
	}

	$lines[] = '<script src="includes/formsy.js"></script>';
	$lines[] = '<link rel="stylesheet" href="includes/formsy.css" />';
	$lines[] = '<script>';
	$lines[] = '  (function($) {';
	$lines[] = '    $(function() {';
	$lines[] = '      var submitHandlers = [];';

	foreach($elts as $elt) {
		$line = formsy_renderElementHead($options, $elt);
		if ($line !== '') {
			$lines[] = $line;
		}
	}

	// start up jquery validate
	$lines[] = "      $('form#$options[name]').validate({";
	$lines[] = "        submitHandler: function(form) {";
	$lines[] = "          for (var h in submitHandlers) {";
	$lines[] = "            if (submitHandlers[h]() == false) return;";
	$lines[] = "          }";
	$lines[] = "          form.submit();";
	$lines[] = "        }";
	$lines[] = "      });";

	// If form was submitted and we want to validate it, we need to perform validation upon loading of the webpage
	if ($options['jQueryValidateOnLoad']) {
		$lines[] = "      $('form#$options[name]').valid();";
	}

	$lines[] = '    });';
	$lines[] = '  })(jQuery);';
	$lines[] = '</script>';
	return implode("\n", $lines);
}

function formsy_renderHtmlValue($elt, $values) {
	return $elt['renderHtmlValueFunction']($elt, $values);
}

function formsy_renderHtmlValueTable($elts, $values) {
	$lines = array('<table>');
	foreach ($elts as $elt) {
		$label = htmlentities($elt['label']);
		$name = $elt['name'];
		$htmlValue = formsy_renderHtmlValue($elt, $values);
		$lines[] = "<tr><td>$label</td><td>$htmlValue</td></tr>";
	}
	$lines[] = '</table>';
	return implode("\n", $lines);
}

function formsy_renderInputElement($elt, $values) {
	return $elt['renderInputElementFunction']($elt, $values);
}

function formsy_renderInputForm($options) {
	/*
	$options = array(
		'name' => '',
		'actionUrl' => $_SERVER['REQUEST_URI'],
		'method' => 'POST',
		'submitButtonText' => 'Submit',
		'elements' => $elts,
		'values' => $_POST
	);
	*/

	$name = $options['name'];
	$elts = $options['elements'];
	$values = $options['values'];

	$lines = array();
	$lines[] = "<form action=\"$options[actionUrl]\" method=\"$options[method]\" id=\"$name\" name=\"$name\">";

	foreach($elts as $elt) {
		$eltName = $elt['name'];

		$lines[] = '<div class="clearfix">';
		$lines[] = "<label for=\"$elt[name]\">$elt[label]</label>";
		$lines[] = '<div class="input">';
		$lines[] = formsy_renderInputElement($elt, $values);
		$lines[] = "<label for=\"$eltName\" generated=\"true\" class=\"error padLeft\"></label>";
		$lines[] = '</div>';
		$lines[] = '</div>';
	}

	$lines[] = "<div class=\"actions\"><input type=\"submit\" class=\"btn primary\" value=\"$options[submitButtonText]\" /></div>";
	$lines[] = '</form>';
	return implode("\n", $lines);
}

function formsy_renderInputFormDictionary($elts, $values) {
	$result = array();
	foreach ($elts as $elt) {
		$name = $elt['name'];
		$result[$name] = formsy_renderInputElement($elt, $values);
	}
	return $result;
}

function formsy_renderPasswordInputElement($elt, $values) {
	return formsy_renderTextInputElement($elt, $values, 'password');
}

function formsy_renderTextAreaInputElement($elt, $values) {
	$name = $elt['name'];
	$postValue = isset($values[$name]) ? htmlentities($values[$name]) : '';
	$classAttr = formsy_buildClassAttribute($elt);

	return "<textarea{$classAttr} id=\"$name\" name=\"$name\">{$postValue}</textarea>";
}

function formsy_renderTextInputElement($elt, $values, $inputType = 'text', $additionalClasses = array()) {
	$name = $elt['name'];
	$postValue = isset($values[$name]) ? ' value="' . htmlentities($values[$name]) . '"' : '';

	$classAttr = formsy_buildClassAttribute($elt, $additionalClasses);

	return "<input{$classAttr} type=\"$inputType\" id=\"$name\" name=\"$name\"{$postValue} />";
}

function formsy_renderWysiwygHead($options, $elt) {
	$name = $elt['name'];

	$lines = array();
	$lines[] = "$('#{$name}').wysiwyg({";
	$lines[] = "  controls: {";
	$lines[] = "    html: { visible: true },";
	$lines[] = "    paragraph: { visible: true }";
	$lines[] = "  },";
	$lines[] = "  css: 'includes/jwysiwyg-editor.css'";
	$lines[] = "});";

	if ($elt['mandatory'] === true) {
		$lines[] = "var validateMandatory{$name} = getMandatoryWysiwygValidator('$options[name]', '{$name}');";
		$lines[] = "submitHandlers.push(validateMandatory{$name});";
		$lines[] = "$('#{$name}').wysiwyg('document')";
		$lines[] = "  .bind('cut', validateMandatory{$name})";
		$lines[] = "  .bind('paste', validateMandatory{$name})";
		$lines[] = "  .keyup(validateMandatory{$name});";
	}

	return implode("\n", $lines);
}

function formsy_renderWysiwygHtmlValue($elt, $values) {
	// TODO: add HTML sanitisation library
	$name = $elt['name'];
	return isset($values[$name]) ? $values[$name] : '';
}

function formsy_renderWysiwygInputElement($elt, $values) {
	$name = $elt['name'];
	$postValue = isset($values[$name]) ? htmlentities($values[$name]) : '';
	$classAttr = formsy_buildClassAttribute($elt);
	return "<textarea{$classAttr} id=\"$name\" name=\"$name\">{$postValue}</textarea>";
}

function formsy_validateAutocomplete($elt, $values) {
	if (isset($elt['requireSelectionFromList']) && $elt['requireSelectionFromList'] === true) {
		$name = $elt['name'];
		$value = $values[$name];

		$foundMatch = false;
		foreach($elt['items'] as $item) {
			if ($item === $value) {
				$foundMatch = true;
				break;
			}
		}
		return $foundMatch;
	} else {
		return true;
	}
}

function formsy_validateDatePicker($elt, $values) {
	$name = $elt['name'];
	$value = $values[$name];

	$matched = preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches);
	if ($matched === 1) {
		$year = $matches[1];
		$month = $matches[2];
		$day = $matches[3];

		return checkdate($month, $day, $year);
	} else {
		return false;
	}
}

function formsy_validateDigitField($elt, $values) {
	$name = $elt['name'];
	$value = $values[$name];
	return preg_match('/^\d*$/', $value) === 1 ? true : false;
}

function formsy_validateDropdown($elt, $values) {
	$name = $elt['name'];
	$value = $values[$name];

	$foundMatch = false;
	foreach($elt['items'] as $item) {
		list($optionValue, $optionText) = formsy_parseDropDownItem($item);
		if ($optionValue === $value) {
			$foundMatch = true;
			break;
		}
	}
	return $foundMatch;
}

function formsy_validateEmailField($elt, $values) {
	$name = $elt['name'];
	return isEmail($values[$name]);
}

function formsy_validateField($elt, $values) {
	$validationFunctions = $elt['validationFunctions'];
	foreach ($validationFunctions as $func) {
		$result = $func($elt, $values);
		if ($result !== 'continue') {
			return $result;
		}
	}
	return $result === 'continue' ? true : $result;
}

function formsy_validateMandatoryField($elt, $values) {
	$name = $elt['name'];
	if ($elt['mandatory'] === true && (!isset($values[$name]) || $values[$name] === '')) {
		return false;
	} else {
		return 'continue';
	}
}

function formsy_validateNonMandatoryField($elt, $values) {
	$name = $elt['name'];
	if ($elt['mandatory'] === false && (!isset($values[$name]) || $values[$name] === '')) {
		return true;
	} else {
		return 'continue';
	}
}
?>