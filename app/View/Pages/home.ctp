<p><br/><br/></p>
<div class="jumbotron center">

	<h3 class="text-info">Welcome to Axia's Online Application System</h3>
	<p>&nbsp;</p>
    <?php
    	echo $this->Html->link(
    	$this->Html->image('retrieve_existing_applications.png',
    		array('alt' => 'Retrieve Existing Applications', 'border' => '0')), 
	    	'#', //(anchor url since this is hadled with AJAX modal)
	    	array('escape' => false, 'data-toggle' => 'modal', 'data-target' => '#myModal_')
    	);
    ?>
<p>&nbsp;</p>

<hr />
</div>

<?php echo $this->element('cobranded_applications/email_select_modal', array('cobranded_application_id' => null)); ?>