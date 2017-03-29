<?php

$logoPosAttr = array(
	 'wrapInput' => 'col col-md-12',
	'options' => $logoPositionTypes,
	'empty' => __('(choose one)'),
);

if (isset($cobrand['Cobrand'])) {
	$brandImage = $this->Html->image($cobrand['Cobrand']['brand_logo_url'], array('height' => '50px'));
	$brLogoAttributes = array(
		'before' => $brandImage
	);
	$cobrandImage = $this->Html->image($cobrand['Cobrand']['cobrand_logo_url'], array('height' => '50px'));
	$logoPosAttr['between'] = $cobrandImage;

} else {
	$brLogoAttributes = array('type' => 'checkbox');
}
if ($this->name === "TemplateBuilder") {
	echo $this->Html->tag('div', '<strong>New Template</strong>');
}
echo $this->Form->input('name', array('class' => 'form-control', 'wrapInput' => 'col col-md-4', 'div' => array('class' => 'row')));
echo $this->Form->input('logo_position', $logoPosAttr);
$brLogoAttributes['wrapInput'] = 'col col-md-12';
echo $this->Form->input('include_brand_logo', $brLogoAttributes);
$rCoverSheetAttr =  array('type' => 'checkbox', 'wrapInput' => 'col col-md-12');
$requestData = Hash::extract($this->request->data, '{s}.requires_coversheet')
if (empty($requestData)) {
	//default checked state for new data
	$rCoverSheetAttr['checked'] = true;
}
echo $this->Form->input('requires_coversheet', $rCoverSheetAttr);

echo $this->Form->input('description', array('class' => 'form-control', 'wrapInput' => 'col col-md-4', 'div' => array('class' => 'row')));

echo $this->Form->input('rightsignature_template_guid',
		array(
			'type' => 'select',
			'label' => 'Rightsignature Template Guid',
				'options' => $templateList,
		)
);

echo $this->Form->input('rightsignature_install_template_guid',
		array(
			'type' => 'select',
			'label' => 'Rightsignature Install Template Guid',
				'options' => $installTemplateList,
		)
);

echo $this->Form->input('owner_equity_threshold', array('class' => 'form-control', 'wrapInput' => 'col col-md-4', 'div' => array('class' => 'row')));