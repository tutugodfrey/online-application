<?php
if (!isset($class)) {
  $class = false;
}
if (!isset($close)) {
  $close = true;
}

?>
<div class="alert<?php echo ($class) ? ' ' . $class : null; ?>">
<?php if ($close): ?>
  <a class="close" data-dismiss="alert" href="#">×</a>
<?php endif; ?>
  <strong><?php echo $message; ?></strong>
  <br/>Please contact <a href="mailto:support@axiapayments.com?subject=Onlineapp <?php echo $appId . ' ' . $subjectSubString;?>">support@axiapayments.com</a>
</div>


