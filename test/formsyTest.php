<?php
require_once(__DIR__ . '/../includes/formsy.php');

// TODO: write test for formsy_renderInputFormDictionary
// TODO: write test for wysiwyg

class FormsyTestCase extends PHPUnit_Framework_TestCase {
	public function testAutocomplete() {
		$elt = formsy_addAutocomplete('favLanguage', 'Favourite Language:', true, array('ActionScript', 'AppleScript', 'ASP', 'Bash', 'BASIC', 'Boo', 'C','C++','Clojure','COBOL', 'CoffeeScript', 'ColdFusion', 'D', 'Erlang', 'F#', 'Fortran', 'Groovy', 'Haskell', 'Java', 'JavaScript', 'Lisp', 'Ocaml', 'Perl', 'PHP', 'Python', 'Ruby', 'Scala', 'Scheme'));

		$this->doInputElementTest($elt, array(
			'expectedElementHead' => 'var favLanguageAutocompleteList = ["ActionScript","AppleScript","ASP","Bash","BASIC","Boo","C","C++","Clojure","COBOL","CoffeeScript","ColdFusion","D","Erlang","F#","Fortran","Groovy","Haskell","Java","JavaScript","Lisp","Ocaml","Perl","PHP","Python","Ruby","Scala","Scheme"];
$(\'#favLanguage\').autocomplete({ minLength: 0, source: favLanguageAutocompleteList });',
			'expectedOutputForMandatoryInputElement' => '<input class="required" type="text" id="favLanguage" name="favLanguage" />',
			'expectedOutputForEmptyInputElement' => '<input type="text" id="favLanguage" name="favLanguage" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => 'Erlang',
			'expectedOutputForPopulatedInputElement' => '<input type="text" id="favLanguage" name="favLanguage" value="Erlang" />',
			'expectedOutputForPopulatedDbValue' => 'Erlang',
			'expectedOutputForPopulatedHtmlValue' => 'Erlang',
			'populateInvalidElementValues' => null
		));
	}

	public function testAutocompleteThatRequiresSelectionFromList() {
		$elt = formsy_addAutocomplete('favLanguage', 'Favourite Language:', true, array('ActionScript', 'AppleScript', 'ASP', 'Bash', 'BASIC', 'Boo', 'C','C++','Clojure','COBOL', 'CoffeeScript', 'ColdFusion', 'D', 'Erlang', 'F#', 'Fortran', 'Groovy', 'Haskell', 'Java', 'JavaScript', 'Lisp', 'Ocaml', 'Perl', 'PHP', 'Python', 'Ruby', 'Scala', 'Scheme'), array(
				'requireSelectionFromList' => true
			));

		$this->doInputElementTest($elt, array(
			'expectedElementHead' => 'var favLanguageAutocompleteList = ["ActionScript","AppleScript","ASP","Bash","BASIC","Boo","C","C++","Clojure","COBOL","CoffeeScript","ColdFusion","D","Erlang","F#","Fortran","Groovy","Haskell","Java","JavaScript","Lisp","Ocaml","Perl","PHP","Python","Ruby","Scala","Scheme"];
$.validator.addMethod(\'favLanguageRequireSelectionFromListValidator\',
  function(value, element) {
    for (var key in favLanguageAutocompleteList) {
      if (favLanguageAutocompleteList[key] == value) {
        return true;
      }
    }
    return false;
  },
  \'Please select a value from the list.\'
);
$(\'#favLanguage\').autocomplete({ minLength: 0, source: favLanguageAutocompleteList });',
			'expectedOutputForMandatoryInputElement' => '<input class="required favLanguageRequireSelectionFromListValidator" type="text" id="favLanguage" name="favLanguage" />',
			'expectedOutputForEmptyInputElement' => '<input class="favLanguageRequireSelectionFromListValidator" type="text" id="favLanguage" name="favLanguage" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => 'PHP',
			'expectedOutputForPopulatedInputElement' => '<input class="favLanguageRequireSelectionFromListValidator" type="text" id="favLanguage" name="favLanguage" value="PHP" />',
			'expectedOutputForPopulatedDbValue' => 'PHP',
			'expectedOutputForPopulatedHtmlValue' => 'PHP',
			'populateInvalidElementValues' => "THIS DOESN'T EXIST IN THE LIST"
		));
	}

	public function testCheckBox() {
		$elt = formsy_addCheckBox('checkboxName', 'Display:', true);
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<input class="required" type="checkbox" id="checkboxName" name="checkboxName" />',
			'expectedOutputForEmptyInputElement' => '<input type="checkbox" id="checkboxName" name="checkboxName" />',
			'expectedOutputForEmptyDbValue' => 0,
			'expectedOutputForEmptyHtmlValue' => 'Unticked',
			'populateValidElementValue' => true,
			'expectedOutputForPopulatedInputElement' => '<input type="checkbox" id="checkboxName" name="checkboxName" checked />',
			'expectedOutputForPopulatedDbValue' => 1,
			'expectedOutputForPopulatedHtmlValue' => 'Ticked',
			'populateInvalidElementValues' => null
		));
	}

	public function testDatepicker() {
		$elt = formsy_addDatePicker('dob', 'Date of Birth:', true);

		$this->doInputElementTest($elt, array(
			'expectedElementHead' => "$('#dob_visible').datepicker({ altField: '#dob', altFormat: 'yy-mm-dd', dateFormat: 'd M yy' });",
			'expectedOutputForMandatoryInputElement' => '<input type="hidden" type="hidden" id="dob" name="dob" /><input class="required date" type="text" id="dob_visible" name="dob_visible" />',
			'expectedOutputForEmptyInputElement' => '<input type="hidden" type="hidden" id="dob" name="dob" /><input class="date" type="text" id="dob_visible" name="dob_visible" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => '1980-11-03',
			'expectedOutputForPopulatedInputElement' => '<input type="hidden" type="hidden" id="dob" name="dob" value="1980-11-03" /><input class="date" type="text" id="dob_visible" name="dob_visible" />',
			'expectedOutputForPopulatedDbValue' => '1980-11-03',
			'expectedOutputForPopulatedHtmlValue' => '3 Nov 1980',
			'populateInvalidElementValues' => array("THIS ISN'T A DATE", '12-08-05', '2010-13-01')
		));
	}

	public function testDigitField() {
		$elt = formsy_addDigitField('zip', 'Postcode:', true);
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<input class="required digits" type="text" id="zip" name="zip" />',
			'expectedOutputForEmptyInputElement' => '<input class="digits" type="text" id="zip" name="zip" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => '1234',
			'expectedOutputForPopulatedInputElement' => '<input class="digits" type="text" id="zip" name="zip" value="1234" />',
			'expectedOutputForPopulatedDbValue' => '1234',
			'expectedOutputForPopulatedHtmlValue' => '1234',
			'populateInvalidElementValues' => array("THIS ISN'T A DIGIT", '321.12')
		));
	}

	public function testDropdownWithSelectedValue() {
		$elt = formsy_addDropdown('dropdownName', 'Age Group:', true, array(
			array('', 'Select'),
			array('8to12', '8 to 12'),
			array('13to18', '13 to 18'),
			array('19to30', '19 to 30'),
			array('31to50', '31 to 50'),
			array('51to70', '51 to 70'),
			array('70plus', '70+')
		));
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<select class="required" name="dropdownName" id="dropdownName">
<option value="">Select</option>
<option value="8to12">8 to 12</option>
<option value="13to18">13 to 18</option>
<option value="19to30">19 to 30</option>
<option value="31to50">31 to 50</option>
<option value="51to70">51 to 70</option>
<option value="70plus">70+</option>
</select>',
			'expectedOutputForEmptyInputElement' => '<select name="dropdownName" id="dropdownName">
<option value="">Select</option>
<option value="8to12">8 to 12</option>
<option value="13to18">13 to 18</option>
<option value="19to30">19 to 30</option>
<option value="31to50">31 to 50</option>
<option value="51to70">51 to 70</option>
<option value="70plus">70+</option>
</select>',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => '19to30',
			'expectedOutputForPopulatedInputElement' => '<select name="dropdownName" id="dropdownName">
<option value="">Select</option>
<option value="8to12">8 to 12</option>
<option value="13to18">13 to 18</option>
<option value="19to30" selected>19 to 30</option>
<option value="31to50">31 to 50</option>
<option value="51to70">51 to 70</option>
<option value="70plus">70+</option>
</select>',
			'expectedOutputForPopulatedDbValue' => '19to30',
			'expectedOutputForPopulatedHtmlValue' => '19 to 30',
			'populateInvalidElementValues' => "THIS TEXT DOESN'T EXIST IN THE DROPDOWN LIST"
		));
	}

	public function testEmailField() {
		$elt = formsy_addEmailField('email', 'Email:', true);
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<input class="required email" type="text" id="email" name="email" />',
			'expectedOutputForEmptyInputElement' => '<input class="email" type="text" id="email" name="email" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => 'test@test.com',
			'expectedOutputForPopulatedInputElement' => '<input class="email" type="text" id="email" name="email" value="test@test.com" />',
			'expectedOutputForPopulatedDbValue' => 'test@test.com',
			'expectedOutputForPopulatedHtmlValue' => 'test@test.com',
			'populateInvalidElementValues' => "THIS ISN'T AN EMAIL ADDRESS"
		));
	}

	public function testPasswordField() {
		$elt = formsy_addPasswordField('pwd', 'Password:', true);
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<input class="required" type="password" id="pwd" name="pwd" />',
			'expectedOutputForEmptyInputElement' => '<input type="password" id="pwd" name="pwd" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => 'asdf4321',
			'expectedOutputForPopulatedInputElement' => '<input type="password" id="pwd" name="pwd" value="asdf4321" />',
			'expectedOutputForPopulatedDbValue' => 'asdf4321',
			'expectedOutputForPopulatedHtmlValue' => 'asdf4321',
			'populateInvalidElementValues' => null
		));
	}

	public function testTextFieldWithQuotes() {
		$elt = formsy_addTextField('freetext', 'Test Field:', true);
		$this->doInputElementTest($elt, array(
			'expectedElementHead' => '',
			'expectedOutputForMandatoryInputElement' => '<input class="required" type="text" id="freetext" name="freetext" />',
			'expectedOutputForEmptyInputElement' => '<input type="text" id="freetext" name="freetext" />',
			'expectedOutputForEmptyDbValue' => null,
			'expectedOutputForEmptyHtmlValue' => '',
			'populateValidElementValue' => '"Chris\' Test & Friends"',
			'expectedOutputForPopulatedInputElement' => '<input type="text" id="freetext" name="freetext" value="&quot;Chris\' Test &amp; Friends&quot;" />',
			'expectedOutputForPopulatedDbValue' => '"Chris\' Test & Friends"',
			'expectedOutputForPopulatedHtmlValue' => '&quot;Chris\' Test &amp; Friends&quot;',
			'populateInvalidElementValues' => null
		));
	}

	public function testBuildClassAttribute() {
		$elt = formsy_addTextField('name', 'Name:', true);
		$result = formsy_buildClassAttribute($elt, array('digits'));
		$this->assertEquals($result, ' class="required digits"');
	}

	public function testIsSubmitted() {
		$_POST['blah'] = 123;
		$result = formsy_isSubmitted($_POST);
		$this->assertTrue($result);
	}

	public function testIsNotSubmitted() {
		$result = formsy_isSubmitted($_POST);
		$this->assertFalse($result);
	}

	public function testParseDropDownItemString() {
		$result = formsy_parseDropDownItem('dropdownItem');
		$this->assertEquals($result, array('dropdownItem', 'dropdownItem'));
	}

	public function testParseDropDownItemArray() {
		$result = formsy_parseDropDownItem(array(
			'value',
			'display'
		));
		$this->assertEquals($result, array('value', 'display'));
	}

	public function testRenderHeadDevValidate() {
		$result = formsy_renderHead(array(
			'name' => 'formName',
			'useDevFiles' => true,
			'jQueryValidateOnLoad' => true,
			'elements' => array()
		));
		$this->assertEquals($result, '<link rel="stylesheet" href="includes/bootstrap-1.4.0/bootstrap.css" />
<script src="includes/jquery-1.7.1/jquery-1.7.1.js"></script>
<script src="includes/jquery-validate-1.9.0/jquery.validate.js"></script>
<script src="includes/formsy.js"></script>
<link rel="stylesheet" href="includes/formsy.css" />
<script>
  (function($) {
    $(function() {
      var submitHandlers = [];
      $(\'form#formName\').validate({
        submitHandler: function(form) {
          for (var h in submitHandlers) {
            if (submitHandlers[h]() == false) return;
          }
          form.submit();
        }
      });
      $(\'form#formName\').valid();
    });
  })(jQuery);
</script>');
	}

	public function testRenderHeadProdNoValidate() {
		$result = formsy_renderHead(array(
			'name' => 'formName',
			'jQueryValidateOnLoad' => false,
			'elements' => array()
		));
		$this->assertEquals($result, '<link rel="stylesheet" href="includes/bootstrap-1.4.0/bootstrap.min.css" />
<script src="includes/jquery-1.7.1/jquery-1.7.1.min.js"></script>
<script src="includes/jquery-validate-1.9.0/jquery.validate.min.js"></script>
<script src="includes/formsy.js"></script>
<link rel="stylesheet" href="includes/formsy.css" />
<script>
  (function($) {
    $(function() {
      var submitHandlers = [];
      $(\'form#formName\').validate({
        submitHandler: function(form) {
          for (var h in submitHandlers) {
            if (submitHandlers[h]() == false) return;
          }
          form.submit();
        }
      });
    });
  })(jQuery);
</script>');
	}

	public function testRenderInputForm() {
		$elts = array(
			'digitName' => formsy_addDigitField('digitName', 'A Digit:', false),
			'textField' => formsy_addTextField('textField', 'Text Field:', true)
		);
		$result = formsy_renderInputForm(array(
			'name' => 'aFormName',
			'actionUrl' => 'respond.php',
			'method' => 'POST',
			'submitButtonText' => 'Submit',
			'elements' => $elts,
			'values' => array()
		));

		$this->assertEquals($result, '<form action="respond.php" method="POST" id="aFormName" name="aFormName">
<div class="clearfix">
<label for="digitName">A Digit:</label>
<div class="input">
<input class="digits" type="text" id="digitName" name="digitName" />
<label for="digitName" generated="true" class="error padLeft"></label>
</div>
</div>
<div class="clearfix">
<label for="textField">Text Field:</label>
<div class="input">
<input class="required" type="text" id="textField" name="textField" />
<label for="textField" generated="true" class="error padLeft"></label>
</div>
</div>
<div class="actions"><input type="submit" class="btn primary" value="Submit" /></div>
</form>');
	}

	public function testRenderHtmlValueTable() {
		$elts = array(
			formsy_addTextField('name', 'Name:', true),
			formsy_addDropdown('ageGroup', 'Age Group:', true, array(
				array('', 'Select'),
				array('8to12', '8 to 12'),
				array('13to18', '13 to 18'),
				array('19to30', '19 to 30'),
				array('31to50', '31 to 50'),
				array('51to70', '51 to 70'),
				array('70plus', '70+')
			)),
			formsy_addCheckBox('display', 'Display:', false)
		);
		$values = array(
			'name' => 'Rocky\'s Road',
			'ageGroup' => '51to70'
		);

		$result = formsy_renderHtmlValueTable($elts, $values);
		$this->assertEquals($result, "<table>
<tr><td>Name:</td><td>Rocky's Road</td></tr>
<tr><td>Age Group:</td><td>51 to 70</td></tr>
<tr><td>Display:</td><td>Unticked</td></tr>
</table>");
	}

	private function doInputElementTest($elt, $options) {
		/*
		$options = array(
			'expectedOutputForMandatoryInputElement' => '<input class="required" ... />',
			'expectedOutputForEmptyInputElement' => '<input ... />',
			'expectedOutputForEmptyDbValue' => 'value',
			'expectedOutputForEmptyHtmlValue' => 'value',
			'populateValidElementValue' => '...',
			'expectedOutputForPopulatedInputElement' => '<input ... />',
			'expectedOutputForPopulatedHtmlValue' => 'value',
			'populateInvalidElementValues' => 'invalidValue'
		);
		*/

		$values = array();

		$renderElementHeadOptions = array(
			'name' => 'formName',
			'useDevFiles' => false,
			'jQueryValidateOnLoad' => true,
			'elements' => array()
		);

		// test element head
		$result = formsy_renderElementHead($renderElementHeadOptions, $elt);
		$this->assertEquals($result, $options['expectedElementHead']);

		// test mandatory empty field
		$result = formsy_renderInputElement($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForMandatoryInputElement']);

		$this->assertFalse(formsy_validateField($elt, $values));

		// test non-mandatory empty field
		$elt['mandatory'] = false;
		$result = formsy_renderInputElement($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForEmptyInputElement']);

		$result = formsy_renderDbValue($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForEmptyDbValue']);

		$result = formsy_renderHtmlValue($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForEmptyHtmlValue']);

		$this->assertTrue(formsy_validateField($elt, $values));

		// test valid field value
		$name = $elt['name'];

		$values[$name] = $options['populateValidElementValue'];
		$this->assertTrue(formsy_validateField($elt, $values));

		$result = formsy_renderInputElement($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForPopulatedInputElement']);

		$result = formsy_renderDbValue($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForPopulatedDbValue']);

		$result = formsy_renderHtmlValue($elt, $values);
		$this->assertEquals($result, $options['expectedOutputForPopulatedHtmlValue']);

		// validate invalid field value
		$invalidValues = $options['populateInvalidElementValues'];
		if ($invalidValues !== null) {
			if (gettype($invalidValues) === 'array') {
				foreach($invalidValues as $invalidValue) {
					$values[$name] = $invalidValue;
					$this->assertFalse(formsy_validateField($elt, $values));
				}
			} else {
				$values[$name] = $invalidValues;
				$this->assertFalse(formsy_validateField($elt, $values));
			}
		}
	}
}
?>