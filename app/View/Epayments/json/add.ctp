<?php
echo $this->Form->create();
echo $this->Form->inputs(array(
'corporate_email',
'legal_business_name',
'mailinig_address',
'mailing_city',
'mailing_state',
'mailing_zip'
));
echo $this->Form->end('Save');
echo json_encode($errMsg);
?>