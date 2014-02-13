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
					'<input name="merge_field_name" id="merge_field_name" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" class="col-md-12" type="text"/>'.
				'</div>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-0-name-id-merg_field_name-1'
		);
		// now different types
		$options['type'] = 1;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this"><div class="input date"><label for="merge_field_name">name*</label><select name="merge_field_name" id="merge_field_nameMonth" required="required">'."\n".
						'<option value=""></option>'."\n".
						'<option value="01">January</option>'."\n".
						'<option value="02">February</option>'."\n".
						'<option value="03">March</option>'."\n".
						'<option value="04">April</option>'."\n".
						'<option value="05">May</option>'."\n".
						'<option value="06">June</option>'."\n".
						'<option value="07">July</option>'."\n".
						'<option value="08">August</option>'."\n".
						'<option value="09">September</option>'."\n".
						'<option value="10">October</option>'."\n".
						'<option value="11">November</option>'."\n".
						'<option value="12">December</option>'."\n".
					'</select>-<select name="merge_field_name" id="merge_field_nameDay" required="required">'."\n".
						'<option value=""></option>'."\n".
						'<option value="01">1</option>'."\n".
						'<option value="02">2</option>'."\n".
						'<option value="03">3</option>'."\n".
						'<option value="04">4</option>'."\n".
						'<option value="05">5</option>'."\n".
						'<option value="06">6</option>'."\n".
						'<option value="07">7</option>'."\n".
						'<option value="08">8</option>'."\n".
						'<option value="09">9</option>'."\n".
						'<option value="10">10</option>'."\n".
						'<option value="11">11</option>'."\n".
						'<option value="12">12</option>'."\n".
						'<option value="13">13</option>'."\n".
						'<option value="14">14</option>'."\n".
						'<option value="15">15</option>'."\n".
						'<option value="16">16</option>'."\n".
						'<option value="17">17</option>'."\n".
						'<option value="18">18</option>'."\n".
						'<option value="19">19</option>'."\n".
						'<option value="20">20</option>'."\n".
						'<option value="21">21</option>'."\n".
						'<option value="22">22</option>'."\n".
						'<option value="23">23</option>'."\n".
						'<option value="24">24</option>'."\n".
						'<option value="25">25</option>'."\n".
						'<option value="26">26</option>'."\n".
						'<option value="27">27</option>'."\n".
						'<option value="28">28</option>'."\n".
						'<option value="29">29</option>'."\n".
						'<option value="30">30</option>'."\n".
						'<option value="31">31</option>'."\n".
					'</select>-<select name="merge_field_name" id="merge_field_nameYear" required="required">'."\n".
						'<option value=""></option>'."\n".
						'<option value="2034">2034</option>'."\n".
						'<option value="2033">2033</option>'."\n".
						'<option value="2032">2032</option>'."\n".
						'<option value="2031">2031</option>'."\n".
						'<option value="2030">2030</option>'."\n".
						'<option value="2029">2029</option>'."\n".
						'<option value="2028">2028</option>'."\n".
						'<option value="2027">2027</option>'."\n".
						'<option value="2026">2026</option>'."\n".
						'<option value="2025">2025</option>'."\n".
						'<option value="2024">2024</option>'."\n".
						'<option value="2023">2023</option>'."\n".
						'<option value="2022">2022</option>'."\n".
						'<option value="2021">2021</option>'."\n".
						'<option value="2020">2020</option>'."\n".
						'<option value="2019">2019</option>'."\n".
						'<option value="2018">2018</option>'."\n".
						'<option value="2017">2017</option>'."\n".
						'<option value="2016">2016</option>'."\n".
						'<option value="2015">2015</option>'."\n".
						'<option value="2014">2014</option>'."\n".
						'<option value="2013">2013</option>'."\n".
						'<option value="2012">2012</option>'."\n".
						'<option value="2011">2011</option>'."\n".
						'<option value="2010">2010</option>'."\n".
						'<option value="2009">2009</option>'."\n".
						'<option value="2008">2008</option>'."\n".
						'<option value="2007">2007</option>'."\n".
						'<option value="2006">2006</option>'."\n".
						'<option value="2005">2005</option>'."\n".
						'<option value="2004">2004</option>'."\n".
						'<option value="2003">2003</option>'."\n".
						'<option value="2002">2002</option>'."\n".
						'<option value="2001">2001</option>'."\n".
						'<option value="2000">2000</option>'."\n".
						'<option value="1999">1999</option>'."\n".
						'<option value="1998">1998</option>'."\n".
						'<option value="1997">1997</option>'."\n".
						'<option value="1996">1996</option>'."\n".
						'<option value="1995">1995</option>'."\n".
						'<option value="1994">1994</option>'."\n".
					'</select></div></div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-1-name-id-merg_field_name-1'
		);
		$options['type'] = 2;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this"><div class="input time"><label for="merge_field_name">name*</label><select name="merge_field_name" id="merge_field_nameHour" required="required">'."\n".
				'<option value=""></option>'."\n".
				'<option value="01">1</option>'."\n".
				'<option value="02">2</option>'."\n".
				'<option value="03">3</option>'."\n".
				'<option value="04">4</option>'."\n".
				'<option value="05">5</option>'."\n".
				'<option value="06">6</option>'."\n".
				'<option value="07">7</option>'."\n".
				'<option value="08">8</option>'."\n".
				'<option value="09">9</option>'."\n".
				'<option value="10">10</option>'."\n".
				'<option value="11">11</option>'."\n".
				'<option value="12">12</option>'."\n".
			'</select>:<select name="merge_field_name" id="merge_field_nameMinute" required="required">'."\n".
				'<option value=""></option>'."\n".
				'<option value="00">00</option>'."\n".
				'<option value="01">01</option>'."\n".
				'<option value="02">02</option>'."\n".
				'<option value="03">03</option>'."\n".
				'<option value="04">04</option>'."\n".
				'<option value="05">05</option>'."\n".
				'<option value="06">06</option>'."\n".
				'<option value="07">07</option>'."\n".
				'<option value="08">08</option>'."\n".
				'<option value="09">09</option>'."\n".
				'<option value="10">10</option>'."\n".
				'<option value="11">11</option>'."\n".
				'<option value="12">12</option>'."\n".
				'<option value="13">13</option>'."\n".
				'<option value="14">14</option>'."\n".
				'<option value="15">15</option>'."\n".
				'<option value="16">16</option>'."\n".
				'<option value="17">17</option>'."\n".
				'<option value="18">18</option>'."\n".
				'<option value="19">19</option>'."\n".
				'<option value="20">20</option>'."\n".
				'<option value="21">21</option>'."\n".
				'<option value="22">22</option>'."\n".
				'<option value="23">23</option>'."\n".
				'<option value="24">24</option>'."\n".
				'<option value="25">25</option>'."\n".
				'<option value="26">26</option>'."\n".
				'<option value="27">27</option>'."\n".
				'<option value="28">28</option>'."\n".
				'<option value="29">29</option>'."\n".
				'<option value="30">30</option>'."\n".
				'<option value="31">31</option>'."\n".
				'<option value="32">32</option>'."\n".
				'<option value="33">33</option>'."\n".
				'<option value="34">34</option>'."\n".
				'<option value="35">35</option>'."\n".
				'<option value="36">36</option>'."\n".
				'<option value="37">37</option>'."\n".
				'<option value="38">38</option>'."\n".
				'<option value="39">39</option>'."\n".
				'<option value="40">40</option>'."\n".
				'<option value="41">41</option>'."\n".
				'<option value="42">42</option>'."\n".
				'<option value="43">43</option>'."\n".
				'<option value="44">44</option>'."\n".
				'<option value="45">45</option>'."\n".
				'<option value="46">46</option>'."\n".
				'<option value="47">47</option>'."\n".
				'<option value="48">48</option>'."\n".
				'<option value="49">49</option>'."\n".
				'<option value="50">50</option>'."\n".
				'<option value="51">51</option>'."\n".
				'<option value="52">52</option>'."\n".
				'<option value="53">53</option>'."\n".
				'<option value="54">54</option>'."\n".
				'<option value="55">55</option>'."\n".
				'<option value="56">56</option>'."\n".
				'<option value="57">57</option>'."\n".
				'<option value="58">58</option>'."\n".
				'<option value="59">59</option>'."\n".
				'</select> <select name="merge_field_name" id="merge_field_nameMeridian" required="required">'."\n".
				'<option value=""></option>'."\n".
				'<option value="am">am</option>'."\n".
				'<option value="pm">pm</option>'."\n".
			'</select></div></div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-2-name-id-merg_field_name-1'
		);
		$options['type'] = 3;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<div class="checkbox">'.
					'<input type="hidden" name="merge_field_name" id="merge_field_name_" value="0"/>'.
					'<input type="checkbox" name="merge_field_name"  label="name*" id="merge_field_name" required="required" value="1"/>'.
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
						'<label><input type="radio" name="merge_field_name" id="merge_field_name" value="value1"> name1</label>'.
					'</li>'.
					'<li>'.
						'<label><input type="radio" name="merge_field_name" id="merge_field_name" value="value2"> name2</label>'.
					'</li>'.
				'</ul>'.
			'</div>',
			$this->TemplateFieldHelper->buildField($this->__buildFieldObject($options), true),
			'user-required-rep_only-4-name-id-merg_field_name-1'
		);
		$options['type'] = 5;
		$this->assertEquals(
			'<div class="col-md-1" title="only the rep will see this">'.
				'<fieldset id="merge_field_name" class="percent">'.
					'<legend>name</legend>'.
					'<div class="input number">'.
						'<label for="merge_field_name_name1">value1</label>'.
						'<input name="merge_field_name_name1" id="merge_field_name_name1" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;percentOptionBlur&quot;, origin: this, totalFieldId: &quot;#merge_field_name_Total&quot;, &quot;fieldset_id&quot;: &quot;merge_field_name&quot;});" min="0" max="100" class="col-md-12" type="number"/>'.
					'</div>'.
					'<div class="input number">'.
						'<label for="merge_field_name_name2">value2</label>'.
						'<input name="merge_field_name_name2" id="merge_field_name_name2" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;percentOptionBlur&quot;, origin: this, totalFieldId: &quot;#merge_field_name_Total&quot;, &quot;fieldset_id&quot;: &quot;merge_field_name&quot;});" min="0" max="100" class="col-md-12" type="number"/>'.
					'</div>'.
					'<div class="input text">'.
						'<label for="merge_field_name_Total">Total</label>'.
						'<input name="merge_field_name_Total" id="merge_field_name_Total" disabled="disabled" onclick="return false;" class="col-md-1" required="required" type="text"/>'.
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
					'<div class="input text">'.
						'<label for="merge_field_name_name1">name1</label>'.
						'<input name="merge_field_name_name1" id="merge_field_name_name1" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;feeOptionBlur&quot;, origin: this, totalFieldId: &quot;#merge_field_name_Total&quot;, &quot;fieldset_id&quot;: &quot;merge_field_name&quot;});" class="col-md-12" value="value1" type="text"/>'.
					'</div>'.
					'<div class="input text">'.
						'<label for="merge_field_name_name2">name2</label>'.
						'<input name="merge_field_name_name2" id="merge_field_name_name2" required="required" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" onblur="$.event.trigger({type: &quot;feeOptionBlur&quot;, origin: this, totalFieldId: &quot;#merge_field_name_Total&quot;, &quot;fieldset_id&quot;: &quot;merge_field_name&quot;});" class="col-md-12" value="value2" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" data-vtype="phoneUS" class="col-md-12" type="text"/>'.
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
					'<input label="name*" name="merge_field_name" id="merge_field_name" required="required" type="text" data-vtype="money" class="col-md-11">'.
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
					'<input label="name*" name="merge_field_name" id="merge_field_name" required="required" type="text" data-vtype="digits" class="col-md-11" min="0" max="100">'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" data-vtype="ssn" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" data-vtype="zipcodeUS" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" class="col-md-12" type="email"/>'.
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
					'<input name="merge_field_name" type="url" id="merge_field_name" required="required" class="col-md-12"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" data-vtype="number" class="col-md-12" type="text"/>'.
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
					'<input name="merge_field_name" id="merge_field_name" required="required" data-vtype="digits" class="col-md-12" type="text"/>'.
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
					'<select name="merge_field_name" id="merge_field_name" required="required">'."\n".
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
				'<textarea name="merge_field_name" label="name*" id="merge_field_name" required="required">'.
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
		return $textField;
	}
}
