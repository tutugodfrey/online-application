<?php

$logoPosAttr = array(
	'options' => $logoPositionTypes,
	'empty' => __('(choose one)'),
);

if ($this->name !== 'TemplateBuilder') {
	$logoPosAttr['wrapInput'] = false;
	$logoPosAttr['label'] = array('class' => 'col-md-8 control-label');
}

$logoPosInputHtml = $this->Form->input('logo_position', $logoPosAttr);
if (isset($cobrand['Cobrand'])) {
	//On Error, display no-image.png instead of a broken image!
	$imgAttr = array('onError' => "this.onerror=null;this.src='/img/no-image.png';", 'height' => '75px');
	$thumbImage1 = $this->Html->image($cobrand['Cobrand']['cobrand_logo_url'], $imgAttr);
	$thumbImage2 = $this->Html->image($cobrand['Cobrand']['brand_logo_url'] , $imgAttr);
	$thumbCaption = $logoPosInputHtml;
	$logoThumbNailsHtml = '
	<div class="row">
		<div class="col-sm-offset-2 col-sm-6 col-md-3">
			<div class="thumbnail">
				<div class="caption text-center">' .
					$thumbCaption .
				'</div>' .
				$thumbImage1 .
			'</div>
			<div class="thumbnail">
				<div class="caption text-center">
				<strong>Brand Logo</strong>
				</div>' .
				$thumbImage2 .
			'</div>
		</div>
	</div>';
} 
if ($this->name === "TemplateBuilder") {
	echo $this->Html->tag('div', '<h4>New Template:</h4>', array('class' => 'col-md-offset-3 col-md-12'));
}
echo $this->Form->input('name', array('class' => 'form-control', 'wrapInput' => 'col col-md-4'));

if (!isset($logoThumbNailsHtml)) {
	echo $logoPosInputHtml;
} else {
	echo $logoThumbNailsHtml;
}
echo $this->Form->input('include_brand_logo', array('class' => false, 'label' => array('class' => 'col-md-offset-7'), 'type' => 'checkbox'));

$requestData = Hash::extract($this->request->data, '{s}.requires_coversheet');
$rCoverSheetAttr = array();
if (empty($requestData)) {
	//default checked state for new data
	$rCoverSheetAttr['checked'] = true;
}
$rCoverSheetAttr = array_merge($rCoverSheetAttr, array('class' => false, 'label' => array('class' => 'col-md-offset-7'), 'type' => 'checkbox'));
echo $this->Form->input('requires_coversheet', $rCoverSheetAttr);

echo $this->Form->input('email_app_pdf', array('class' => false, 'label' => array('text' => 'Email Signed PDF to Rep', 'class' => 'col-md-offset-7'), 'type' => 'checkbox'));

echo $this->Form->input('description', array('class' => 'form-control', 'wrapInput' => 'col col-md-4'));

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

echo $this->Form->input('owner_equity_threshold', array('class' => 'form-control', 'wrapInput' => 'col col-md-4'));