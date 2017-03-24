<?php
	$emailList = array();
	if (!empty($valuesMap)) {
		$emailList = array_combine(Hash::extract($valuesMap, "{s}"), Hash::extract($valuesMap, "{s}"));
		$emailList = Hash::filter($emailList);
	}
	echo "
		<!-- Modal -->
			<div class='modal fade' id='myModal_" . $cobranded_application_id . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  			<div class='modal-dialog'>
    			<div class='modal-content'>
      			<div class='modal-header'>
        			<h4 class='modal-title panel-title' id='myModalLabel'>
        				<strong>Email For Field Completion</strong>
        			</h4>
      			</div>
      			<div class='modal-body' id='sendEmailModalDialog_" . $cobranded_application_id . "'>
      			<div id='ajaxEmailResponse" . $cobranded_application_id . "' class='text-center'><!--Ajax Response will render here--></div>";
      			$thisFormId = 'emailToComplFrm_' . $cobranded_application_id;
				echo $this->Form->create('CobrandedApplication',
					array(
						'inputDefaults' => array(
							'div' => 'form-group',
							'wrapInput' => false,
							'class' => 'form-control'
						),
						'novalidate' => true,
						'default' => false,//prevent default submit this form is ajax only
                    	'id' => $thisFormId,
                    	'class' => 'form-inline'
					)
				);

				echo $this->Form->input('id',
					array(
						'type' => 'hidden',
						'default' => $cobranded_application_id
					)
				);

				$emailListDiv;
				$emailTextDiv;

				if (count($emailList) > 0) {
					$emailListDiv = array('id' => 'emailListDiv', 'style' => 'display:inline-block;');
					$emailTextDiv = array('id' => 'emailTextDiv', 'style' => 'display:none;');
				} else {
					$emailListDiv = array('id' => 'emailListDiv', 'style' => 'display:none;');
					$emailTextDiv = array('id' => 'emailTextDiv', 'style' => 'display:inline-block;');
				}

				echo $this->Form->input(
					'emailList',
					array(
						'options' => $emailList,
						'label' => 'Select Email Address',
						'type' => 'select',
						'div' => $emailListDiv
					)
				);

				echo $this->Form->input(
					'emailText',
					array(
						'label' => 'Please enter an email address:',
						'type' => 'text',
						'div' => $emailTextDiv
					)
				);

				echo "
					<div class='modal-footer'>
    					<button class='btn btn-default btn-sm' data-dismiss='modal'>Close</button>";

					echo $this->Form->end(array('label' =>'Send', 'div' => false, 'class' => 'btn btn-default btn-sm', 
						'onClick' => "$('<img src=\'/img/refreshing.gif\'/>').appendTo( '#ajaxEmailResponse" . $cobranded_application_id . "')")
					);
				echo "</div>";

	echo "</div>
		</div>
		</div>
		</div>
	";

$data = $this->Js->get('#' . $thisFormId)->serializeForm(array('isForm' => true, 'inline' => true));
$this->Js->get('#' . $thisFormId)->event(
   'submit',
   $this->Js->request(
    '/cobranded_applications/retrieve',
    array(
        'update' => '#ajaxEmailResponse' . $cobranded_application_id,
        'data' => $data,
        'async' => true,    
        'dataExpression'=>true,
        'method' => 'POST'
    )
  )
);
echo $this->Js->writeBuffer();


