<?php

if (!empty($response['errors'])) {
	echo $this->Html->tag('div',implode('<br/>', $response['errors']),
		array('class' => 'alert alert-danger'));
	echo "<script type='text/javascript'>$('body').scrollTop(0);</script>";
}
?>
<?php
	echo "<div class='panel panel-default'>";
				echo $this->Form->create('TemplateBuilder',
					array(
						'default' => false,//prevent default submit this form is ajax only
						'id' => 'templateBuilderMainForm',
						'inputDefaults' => array(
							'div' => 'form-group col-md-12',
							'label' => array('class' => 'col-md-2 control-label'),
							'wrapInput' => 'col-md-3',
							'class' => 'form-control input-sm',
						),
						'class' => 'form-horizontal',
						'url' => '/admin/template_builder/add_template',
					)
				);
				$checkAttr = array('wrapInput' => 'col-md-12', 'label'=> array('class' => 'control-label') ,'type' => 'checkbox', 'class' => null);
				$radioAttr = array(
									'wrapInput' => false,
									'class' => 'input-sm',
									'type' => 'radio',
									'legend' => false,
									'options' => array('true' => 'Yes ', 'false' => 'No'),
								);
				echo $this->Form->hidden('TemplateBuilder.mainBuilderForm', array('value' => true));
				echo $this->Form->hidden('TemplateBuilder.selected_template_id');
				echo $this->Form->input(
					'new_template_cobrand_id',
					array(
						'options' => $cobrands,
						'label' => 'Cobrand new template belongs to:',
						'type' => 'select'
					)
				);
				echo $this->element('Templates/templateFields');     

				echo '<table class="table table-bordered table-condensed table-hover">';

				echo "<tr>";
				echo "<td colspan='4'>";
				$checkAttr['label']['text'] = 'CHECK ALL';
				echo $this->Form->input('check_all', array_merge(
					$checkAttr, 
					array(						
						'id' => 'check_all',
						'onclick' => 'checkAll();'
					))
				);
				echo "</td>";
				echo "</tr>";

				echo "<th class='text-center'>
				<h4><span class='label label-info'>Page</span> / <span class='label label-warning'>Section</span> / Field</h4></th>";
				echo "<th class='text-center'>Rep Only</th>";
				echo "<th class='text-center'>Required</th>";
				echo "<th class='text-center'>Default Value(s)</th>";

				foreach ($template['TemplatePages'] as $page) {
					echo "<tr class='info'>";
						echo "<td>";
							$checkAttr['label']['text'] = $page['name'];
							echo $this->Form->input(
								"template_page_id_".$page['id'],
								array_merge($checkAttr, array('id' => 'template_page_id_'.$page['id']))
							);
						echo "</td>";

						$repOnly = $page['rep_only'] ? 'true' : 'false';

						echo "<td>";
							echo $this->Form->input(
								"rep_only_template_page_id_".$page['id'],
								array_merge($radioAttr, array('default' => $repOnly))
							);
						echo "</td>";
						echo "<td></td>";
						echo "<td></td>";
					echo "</tr>";

					foreach ($page['TemplateSections'] as $section) {
						echo "<tr class='warning'>";
							echo "<td style='padding-left: 3em;'>";
								$checkAttr['label']['text'] = $section['name'];
								echo $this->Form->input(
									"template_page_id_".$page['id']."_section_id_".$section['id'],
									array_merge($checkAttr,
									array(
										'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'],
										'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");'
									))
								);
							echo "</td>";

							$repOnly = $section['rep_only'] ? 'true' : 'false';

							echo "<td>";
								echo $this->Form->input(
									"rep_only_template_page_id_".$page['id']."_section_id_".$section['id'],
									array_merge($radioAttr, array('default' => $repOnly))
								);
							echo "</td>";
							echo "<td></td>";
							echo "<td></td>";
						echo "</tr>";

						foreach ($section['TemplateFields'] as $field) {
							echo "<tr>";
								echo "<td style='padding-left: 6em;'>";
									$checkAttr['label']['text'] = $field['name'];
									echo $this->Form->input(
										"template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array_merge($checkAttr,
										array(
											'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'].'_field_id_'.$field['id'],
											'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");checkCheckbox("template_page_id_'.$page['id'].'_section_id_'.$section['id'].'");'
										))
									);
								echo "</td>";

								$repOnly = $field['rep_only'] ? 'true' : 'false';

								echo "<td>";
									echo $this->Form->input(
										"rep_only_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array_merge($radioAttr, array('default' => $repOnly))
									);
								echo "</td>";

								$required = $field['required'] ? 'true' : 'false';

								echo "<td>";
									echo $this->Form->input(
										"required_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array_merge($radioAttr, array('default' => $required))
									);
								echo "</td>";

								echo "<td>";
									echo $this->Form->input(
										"default_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array(
											'type' => 'textarea',
											'wrapInput' => false,
											'label' => false,
											'default' => $field['default_value']
										)
									);
								echo "</td>";

							echo "</tr>";
						}
					}

					echo "<br>";
				}

				echo "</table>";
				echo "<br>";
		
			echo $this->Form->end(array('label' =>'Submit',
				'class' => 'btn btn-success btn-lg center-block',
				'onClick' => "$(this).hide();$('<img src=\'/img/refreshing.gif\'/>').appendTo( '#templateBuilderMainForm')"));
	echo "</div>";


$data = $this->Js->get('#templateBuilderMainForm')->serializeForm(array('isForm' => true, 'inline' => true));
$this->Js->get('#templateBuilderMainForm')->event(
   'submit',
   $this->Js->request(
	array('action' => 'add_template', 'controller' => 'TemplateBuilder'),
	array(
		'update' => '#tmpltBldrContainer',
		'data' => $data,
		'async' => true,    
		'dataExpression'=>true,
		'method' => 'POST'
	)
  )
);
echo $this->Js->writeBuffer();
?>