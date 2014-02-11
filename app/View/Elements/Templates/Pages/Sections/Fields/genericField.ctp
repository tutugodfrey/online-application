<?php foreach ($fields as $field):
	echo $this->TemplateField->buildField($field, $requireRequiredFields);
endforeach;
?>