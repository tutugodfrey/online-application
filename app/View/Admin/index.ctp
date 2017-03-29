<br/><br/><br/><br/><br/>
<div class='col-md-6 col-md-offset-3'>
<div class='panel panel-primary'>
  <div class="panel-heading"><h4 class="panel-title"><?php echo __('Axia Admin'); ?></h4></div>
  <ul class="list-group">
  <?php foreach ($links as $link): ?>
    <li class="list-group-item">
      <?php echo $this->Html->link($link[0], $link[1]); ?>
    </li>
    <?php endforeach; ?>
  	<li class="list-group-item"><strong><?php echo $this->Html->link('Logout', '/users/logout/'); ?></strong></li>
  </ul>
</div>
</div>