 Dear <?php echo $rep; ?>,

<?php echo $merchant; ?> has signed a Cancellation/Closure form, please proceed with the necessary steps to finish processing this cancellation.
<?php echo $description; ?> 

<?php echo $link; ?> 

<?php 
if (!empty($appPdfUrl)) {
	echo "To review the signed cancellation form use the following link: \n";
	echo $appPdfUrl;
}
?>

Thank you,

Axia Online Application
