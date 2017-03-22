<script type="text/javascript">
	<?php
		echo "var map = {};";
		foreach ($templates as $key => $val) {
			echo "map['".$key."'] = '".$val."';";
		}
	?>
</script>
<script type="text/javascript" src="/js/templateBuilder.js"></script>
<br/>

<div class="row col-md-offset-2">
	<div class='col-md-8'>
		<div class='panel panel-info'>
			<div class="panel-heading"><strong>Choose Base Template</strong></div>
		  	<div class="panel-body">
<?php
	echo $this->Form->create('TemplateBuilder', array(
			'inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => false,
				'class' => 'form-control'
			),
			'class' => 'form-inline'
		));
	echo $this->Form->input(
		'base_cobrand',
		array(
			'options' => $cobrands,
			'label' => false,
			'type' => 'select'
		)
	);
	echo $this->Form->input(
		'base_template',
		array(
			'options' => $templates,
			'label' => false,
			'type' => 'select'
		)
	);
	echo $this->Html->tag('div', 
		$this->Form->button('Submit', array('type' => 'button', 'class' => 'btn btn-success', 'onClick' => 'getTemplateForm()'))
		, array('class' => 'form-group')
	);
	echo $this->Form->end();
	echo $this->Html->tag('div', '&nbsp', array('id' => 'slectorFrmErrCont'));
?>
			</div>
		</div>
	</div>
</div>
<?php
echo $this->Html->tag('div', '&nbsp', array('id' => 'tmpltBldrContainer'));
?>