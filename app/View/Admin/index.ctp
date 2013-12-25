<div>
  <h4>Axia Admin</h4>

  <ul class="list-group">
  <?php foreach ($links as $link): ?>
    <li class="list-group-item">
      <?php echo $this->Html->link($link[0], $link[1]); ?>
    </li>
    <?php endforeach; ?>
  </ul>
  <p><?php echo $this->Html->link('Logout', '/users/logout/'); ?></p>

</div>