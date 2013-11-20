<?php
echo $this->Form->create();
echo $this->Form->inputs(array(
//    'corporate_email',
//    'legal_business_name',
//    'mailinig_address',
//    'mailing_city',
//    'mailing_state',
//    'mailing_zip',
//    'mailing_phone',
//	'ownership_type',
	'legal_business_name',
	'mailing_address',
	'mailing_city',
	'mailing_state',
	'mailing_zip',
	'mailing_phone',
	'federal_taxid',
	'corp_contact_name',
	'corp_contact_name_title',
	'corporate_email', 
	'dba_business_name',
        'monthly_volume',
        'average_ticket',
        'bank_name',
        'depository_routing_number',
        'depository_account_number',
    
    
));
echo $this->Form->end('Save');
//echo json_encode($errMsg);
?>