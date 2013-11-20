Dear <?php echo $ownerName; ?>,</br>
</br>
Your company has recently signed up for a Hooza e-Commerce website, as Hooza's e-Commerce provider, Axia has provided
our online merchant application to a member of your staff.  They have taken the liberty to fill out your company's application;
now that this is complete we need you quickly review and sign as directed by clicking the link below.
</br>
</br>
<?php echo $url  ?></br>
</br>
</br>
Thank you,</br>
</br>
Your Team at Axia</br>
<?php echo "<a href=\"mailto:hooza@axiapayments.com?Subject=" . $merchant . "%20Hooza%20Application\">hooza@axiapayments.com</a>" ?>
</br>
877.94.HOOZA (46692)
</br>
<?php echo $this->Html->image("https://" . $_SERVER['SERVER_NAME'] . '/img/axia_logo.png', array('alt'=> __('Axia'), 'border' => '0', 'url' => 'http://www.axiapayments.com')); ?>
	&nbsp;	&nbsp;	&nbsp; 	&nbsp;	&nbsp;	&nbsp;
<?php echo $this->Html->image("https://" . $_SERVER['SERVER_NAME'] . '/img/hooza_logo.jpg', array('alt'=> __('Axia'), 'border' => '0', 'url' => 'http://www.hooza.co')); ?>



