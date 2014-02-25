<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('TemplateFieldHelper', 'View/Helper');

class TemplateFieldHelperTest extends CakeTestCase {
	public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->TemplateFieldHelper = new TemplateFieldHelper($View);
	}

	public function testBuildField() {
		// expects:
		//     $field = {object}
		//     $requireRequiredFields = <boolean>

		// mock a field object
		$options = array('source' => 0); // 0 ==> api
		$this->assertEmpty($this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), false), 'api fields are not displayed');
		$this->assertEmpty($this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true), 'api fields are not displayed');

		// all other tests will be with api == 1
		$options = array(
			'source' => 1, 'required' => false, 'rep_only' => false, 'type' => 0, 'name' => 'name', 'id' => 'id', 'merge_field_name' => 'merge_field_name', 'width' => 12,
		);
		$this->assertEquals(
			'<div class="col-md-12">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name</label>'.
					'<input name="merge_field_name" id="merge_field_name" data-value-id="id1" value="" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-not required-not rep_only-0-name-id-merg_field_name-12'
		);
		$options['required'] = true;
		$this->assertEquals(
			'<div class="col-md-12">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-not rep_only-0-name-id-merg_field_name-12'
		);
		$options['rep_only'] = true;
		$this->assertEquals(
			'<div class="col-md-12" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-0-name-id-merg_field_name-12'
		);
		$options['rep_only'] = true;
		$this->assertEquals(
			'<div class="col-md-12" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-0-name-id-merg_field_name-12'
		);
		$options['width'] = 1;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-0-name-id-merg_field_name-1'
		);
		// now different types
		$options['type'] = 1;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="date" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-1-name-id-merg_field_name-1'
		);
		$options['type'] = 2;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="time12h" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-2-name-id-merg_field_name-1'
		);
		$options['type'] = 3;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="checkbox">'.
					'<input type="hidden" name="merge_field_name" id="merge_field_name_" value="0"/>'.
					'<input type="checkbox" name="merge_field_name"  label="name*" id="merge_field_name" required="required" data-value-id="id1" value=""/>'.
					'<label for="merge_field_name">name*</label>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-3-name-id-merg_field_name-1'
		);
		$options['type'] = 4;
		$options['default_value'] = 'name1::value1,name2::value2';
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<label>name*</label>'.
				'<ul class="list-inline">'.
					'<li>'.
						'<label>'.
							'<input type="radio" name="name" data-value-id="id1" >name1</input>'.
						'</label>'.
					'</li>'.
					'<li>'.
						'<label>'.
							'<input type="radio" name="name" data-value-id="id2" >name2</input>'.
						'</label>'.
					'</li>'.
				'</ul>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-4-name-id-merg_field_name-1'
		);
		$options['type'] = 5;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<fieldset id="name" class="percent">'.
					'<legend>name <span class="small">(total must equal 100%)</span></legend>'.
					'<div class="input number">'.
						'<label for="name1">name1</label>'.
						'<input name="name1" id="name1" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;percentOptionBlur&quot;, origin: this, totalFieldId: &quot;#name_Total&quot;, &quot;fieldset_id&quot;: &quot;name&quot;});" min="0" max="100" class="col-md-12" data-value-id="id1" value="" type="number"/>'.
					'</div>'.
					'<div class="input number">'.
						'<label for="name2">name2</label>'.
						'<input name="name2" id="name2" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;percentOptionBlur&quot;, origin: this, totalFieldId: &quot;#name_Total&quot;, &quot;fieldset_id&quot;: &quot;name&quot;});" min="0" max="100" class="col-md-12" data-value-id="id2" value="" type="number"/>'.
					'</div>'.
					'<div class="input text">'.
						'<label for="name_Total">Total</label>'.
						'<input name="name_Total" id="name_Total" disabled="disabled" onclick="return false;" class="col-md-1" required="required" type="text"/>'.
					'</div>'.
				'</fieldset>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-5-name-id-merg_field_name-1'
		);
		$options['type'] = 6;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<h4>name</h4>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-6-name-id-merg_field_name-1'
		);
		$options['type'] = 7;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<fieldset id="merge_field_name" class="fees">'.
					'<legend>name</legend>'.
					'<label for="value1">name1</label>'.
					'<div class="input-group col-md-12">'.
						'<span class="input-group-addon col-md-1">$</span>'.
						'<input label="name*" name="name1" id="value1" required="required" type="text" class="col-md-10" data-value-id="id1" value="" data-vtype="money">'.
						'</input>'.
					'</div>'.
					'<label for="value2">name2</label>'.
					'<div class="input-group col-md-12">'.
						'<span class="input-group-addon col-md-1">$</span>'.
						'<input label="name*" name="name2" id="value2" required="required" type="text" class="col-md-10" data-value-id="id2" value="" data-vtype="money">'.
						'</input>'.
					'</div>'.
				'</fieldset>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-7-name-id-merg_field_name-1'
		);
		$options['type'] = 8;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this"><hr></div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-8-name-id-merg_field_name-1'
		);
		$options['type'] = 9;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="phoneUS" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-9-name-id-merg_field_name-1'
		);
		$options['type'] = 10;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<label for="merge_field_name">name*</label>'.
				'<div class="input-group col-md-12">'.
					'<span class="input-group-addon col-md-1">$</span>'.
					'<input label="name*" name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" type="text" data-vtype="money" class="col-md-10">'.
					'</input>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-10-name-id-merg_field_name-1'
		);
		$options['type'] = 11;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input-group col-md-12">'.
					'<label for="merge_field_name">name*</label>'.
					'<input label="name*" name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" type="text" data-vtype="digits" class="col-md-2" min="0" max="100">'.
					'</input>'.
					'<span class="input-group-addon col-md-1">%</span>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-11-name-id-merg_field_name-1'
		);
		$options['type'] = 12;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="ssn" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-12-name-id-merg_field_name-1'
		);
		$options['type'] = 13;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="zipcodeUS" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-13-name-id-merg_field_name-1'
		);
		$options['type'] = 14;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input email">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12" type="email"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-14-name-id-merg_field_name-1'
		);
		$options['type'] = 17;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input url">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" type="url" id="merge_field_name" required="required" data-value-id="id1" value="" class="col-md-12"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-17-name-id-merg_field_name-1'
		);
		$options['type'] = 18;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="number" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-18-name-id-merg_field_name-1'
		);
		$options['type'] = 19;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input text">'.
					'<label for="merge_field_name">name*</label>'.
					'<input name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1" value="" data-vtype="digits" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-19-name-id-merg_field_name-1'
		);
		$options['type'] = 20;
		$options['default_value'] = 'name1::value1,name2::value2';
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="input select">'.
					'<label for="merge_field_name">name*</label>'.
					'<select name="merge_field_name" id="merge_field_name" required="required" data-value-id="id1">'."\n".
						'<option value="">(choose one)</option>'."\n".
						'<option value="value1">name1</option>'."\n".
						'<option value="value2">name2</option>'."\n".
					'</select>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-20-name-id-merg_field_name-1'
		);
		$options['type'] = 21;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<label for="for" merge_field_name="merge_field_name">name</label>'.
				'<textarea name="merge_field_name" label="name*" id="merge_field_name" required="required" data-value-id="id1">'.
				'</textarea>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-20-name-id-merg_field_name-1'
		);
		$options['type'] = -1;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">***** UNRECOGNIZED FIELD TYPE [-1] for field [merge_field_name]*****</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only--1-name-id-merg_field_name-1'
		);
	}

	private function __buildFieldObject($options = array('source' => null, 'required' => null, 'rep_only' => null, 'type' => null, 'name' => null, 'id' => null, 'merge_field_name' => null, 'width' => null, 'default_value' => '')) {
		$textField = array();
		foreach ($options as $key => $value) {
			$textField = Hash::insert($textField, $key, $options[$key]);
		}
		// mock the CobrandedApplicationValues relationship
		$textField = Hash::insert(
			$textField,
			'CobrandedApplicationValues',
			array(
				array('name' => 'name1', 'id'=>'id1', 'value' => ''),
				array('name' => 'name2', 'id'=>'id2', 'value' => '')
			)
		);
		return $textField;
	}
}
