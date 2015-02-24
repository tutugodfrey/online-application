<?php
							
  $emailList = array();
								
	if ($values_map['Owner1Email'] != '') {
		$emailList[$values_map['Owner1Email']] = $values_map['Owner1Email'];
  }

	if ($values_map['Owner2Email'] != '') {
		$emailList[$values_map['Owner2Email']] = $values_map['Owner2Email'];
	}

	if ($values_map['EMail'] != '') {
		$emailList[$values_map['EMail']] = $values_map['EMail'];
	}

	if ($values_map['LocEMail'] != '') {
		$emailList[$values_map['LocEMail']] = $values_map['LocEMail'];
	}
							
	echo "
		<!-- Modal -->
			<div class='modal fade' id='myModal_".$cobranded_application_id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
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
      											
      				if (count($emailList) > 0) {
      					echo $this->Form->input(
    							'email',
    							array(
        						'options' => $emailList,
        						'label' => 'Select Email Address',
        						'type' => 'select'
    							)
								);
							} else {
								echo $this->Form->input(
    							'email',
    							array(
        						'label' => 'Please enter an email address:',
        						'type' => 'text'
    							)
								);
							}

							echo "
								<div class='modal-footer'>
        					<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        					<button type='submit' class='btn btn-default' id='myFormSubmit_".$cobranded_application_id."'>Send</button>
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
      $('#myFormSubmit_".$cobranded_application_id."').click(function(e){
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




