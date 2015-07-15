<?php

	$emailList = array();

	if ($valuesMap['Owner1Email'] != '') {
		$emailList[$valuesMap['Owner1Email']] = $valuesMap['Owner1Email'];
	}

	if ($valuesMap['Owner2Email'] != '') {
		$emailList[$valuesMap['Owner2Email']] = $valuesMap['Owner2Email'];
	}

	if ($valuesMap['EMail'] != '') {
		$emailList[$valuesMap['EMail']] = $valuesMap['EMail'];
	}

	if ($valuesMap['LocEMail'] != '') {
		$emailList[$valuesMap['LocEMail']] = $valuesMap['LocEMail'];
	}

	echo "
		<!-- Modal -->
			<div class='modal fade' id='myModal_" . $cobranded_application_id . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  			<div class='modal-dialog'>
    			<div class='modal-content'>
      			<div class='modal-header'>
        			<h4 class='modal-title' id='myModalLabel'>Email For Field Completion</h4>
      			</div>
      			<div class='modal-body'>";

				echo $this->Form->create('CobrandedApplication',
					array(
						'action' => 'retrieve',
						'novalidate' => true
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
        					<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        					<button type='submit' class='btn btn-default' id='myFormSubmit_" . $cobranded_application_id . "'>Send</button>
      					</div>";

							echo $this->Form->end();

	echo "</div>
		</div>
		</div>
		</div>
	";

echo "
  <script type='text/javascript'>
    $(document).ready(function() {
      $('#myFormSubmit_" . $cobranded_application_id . "').click(function(e){
        e.preventDefault();

        $.ajax({
          method: 'post',
          url: '/cobranded_applications/retrieve/',
          data: $(this).closest('form').serialize(),
          success: function(response){
            $('#myModal').modal('hide');
            $('body').html(response);
          },
          error: function(xmlhttp, textStatus) {
            alert('request failed');
          },
          cache: false
        })
      });
    });
  </script>
";




