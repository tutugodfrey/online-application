<?php
echo $this->Form->create();
echo $this->Form->inputs(array(
'legal_business_name',
'mailinig_address',
'mailing_city',
'mailing_state',
'mailing_zip',
'dba_business_name'
));
echo $this->Form->end('Save');
?>