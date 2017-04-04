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

				echo "<table cellpadding='0' cellspacing='0' border='1'>";

				echo "<tr>";
				echo "<td colspan='4'>";
				echo $this->Form->input('check_all',
					array(
						'label' => 'CHECK ALL',
						'type' => 'checkbox',
						'id' => 'check_all',
						'onclick' => 'checkAll();'
					)
				);
				echo "</td>";
				echo "</tr>";

				echo "<th style='text-align:center'>Page/Section/Field</th>";
				echo "<th style='text-align:center'>Rep Only</th>";
				echo "<th style='text-align:center'>Required</th>";
				echo "<th style='text-align:center'>Default Value(s)</th>";

				foreach ($template['TemplatePages'] as $page) {
					echo "<tr>";
						echo "<td>";
							echo $this->Form->input(
								"template_page_id_".$page['id'],
								array(
									'label' => $page['name'],
									'type' => 'checkbox',
									'id' => 'template_page_id_'.$page['id']
								)
							);
						echo "</td>";

						$repOnly = $page['rep_only'] ? 'true' : 'false';

						echo "<td>";
							echo $this->Form->input(
								"rep_only_template_page_id_".$page['id'],
								array(
									'type' => 'radio',
									'legend' => false,
									'options' => array('true' => 'Yes ', 'false' => 'No'),
									'default' => $repOnly
								)
							);
						echo "</td>";
						echo "<td></td>";
						echo "<td></td>";
					echo "</tr>";

					foreach ($page['TemplateSections'] as $section) {
						echo "<tr>";
							echo "<td style='padding-left: 3em;'>";
								echo $this->Form->input(
									"template_page_id_".$page['id']."_section_id_".$section['id'],
									array(
										'label' => $section['name'],
										'type' => 'checkbox',
										'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'],
										'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");'
									)
								);
							echo "</td>";

							$repOnly = $section['rep_only'] ? 'true' : 'false';

							echo "<td>";
								echo $this->Form->input(
									"rep_only_template_page_id_".$page['id']."_section_id_".$section['id'],
									array(
										'type' => 'radio',
										'legend' => false,
										'options' => array('true' => 'Yes ', 'false' => 'No'),
										'default' => $repOnly
									)
								);
							echo "</td>";
							echo "<td></td>";
							echo "<td></td>";
						echo "</tr>";

						foreach ($section['TemplateFields'] as $field) {
							echo "<tr>";
								echo "<td style='padding-left: 6em;'>";
									echo $this->Form->input(
										"template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array(
											'label' => $field['name'],
											'type' => 'checkbox',
											'id' => 'template_page_id_'.$page['id'].'_section_id_'.$section['id'].'_field_id_'.$field['id'],
											'onclick' => 'checkCheckbox("template_page_id_'.$page['id'].'");checkCheckbox("template_page_id_'.$page['id'].'_section_id_'.$section['id'].'");'
										)
									);
								echo "</td>";

								$repOnly = $field['rep_only'] ? 'true' : 'false';

								echo "<td>";
									echo $this->Form->input(
										"rep_only_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array(
											'type' => 'radio',
											'legend' => false,
											'options' => array('true' => 'Yes ', 'false' => 'No'),
											'default' => $repOnly
										)
									);
								echo "</td>";

								$required = $field['required'] ? 'true' : 'false';

								echo "<td>";
									echo $this->Form->input(
										"required_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array(
											'type' => 'radio',
											'legend' => false,
											'options' => array('true' => 'Yes ', 'false' => 'No'),
											'default' => $required
										)
									);
								echo "</td>";

								echo "<td>";
									echo $this->Form->input(
										"default_template_page_id_".$page['id']."_section_id_".$section['id']."_field_id_".$field['id'],
										array(
											'type' => 'textarea',
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
		
			echo $this->Form->end(array('label' =>'Submit', 'onClick' => "$(this).hide();$('<img src=\'/img/refreshing.gif\'/>').appendTo( '#templateBuilderMainForm')"));
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