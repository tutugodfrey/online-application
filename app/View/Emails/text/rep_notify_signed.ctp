 Dear <?php echo $rep; ?>,

<?php echo $merchant; ?> Has signed their Application and the Application has been sent to all the appropriate parties.
<?php echo $description; ?> 

<?php echo $link; ?> 

<?php 
if (!empty($appPdfUrl)) {
	echo "To review the signed sales agreement use the following link: \n";
	echo $appPdfUrl;
}
?>

Thank you,

Axia Online Application
