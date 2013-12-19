<h4>Axia Admin</h4>
<ul>
    <?php if ($this->Session->read('Auth.User.Group.name') == 'admin'): ?>
    <li><?php echo $this->Html->link('Settings', '/admin/settings/'); ?></li>
    <li><?php echo $this->Html->link('Users', '/admin/users/'); ?></li>
    <li><?php echo $this->Html->link('Groups', '/admin/groups/'); ?></li>
    <li><?php echo $this->Html->link('Cobrands', '/admin/Cobrands/'); ?></li>
    <li><?php echo $this->Html->link('Multipass', '/admin/multipasses/'); ?></li>
    <li><?php echo $this->Html->link('API IP restrictions', '/admin/apips/'); ?></li>
    <li><?php echo $this->Html->link('API Logs', '/admin/apiLogs/'); ?></li>
    <li><?php echo $this->Html->link('USAePay Merchants', '/admin/epayments/'); ?></li>
    <li><?php echo $this->Html->link('Email Timelines', '/admin/emailTimelines/'); ?></li>
    <?php endif; ?>
    <li><?php echo $this->Html->link('Applications', '/admin/applications/'); ?></li>
    <li><?php echo $this->Html->link('Coversheets', '/admin/coversheets/'); ?></li>
</ul>

<p><?php echo $this->Html->link('Logout', '/users/logout/'); ?></p>

