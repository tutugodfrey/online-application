<?php
$this->Html->addCrumb(__('Cobrands'), '/admin/cobrands');
$this->Html->addCrumb(
  __('Templates'),
  String::insert(
    '/admin/cobrands/:cobrand_id/templates',
    array('cobrand_id' => $cobrand['id'])
  )
);
$this->Html->addCrumb(
  __('Pages'),
  String::insert(
    '/admin/templates/:template_id/templatepages',
    array('template_id' => $template['id'])
  )
);
?>
<div class="templateSections form">
<?php echo $this->Form->create('TemplateSection'); ?>
  <fieldset>
    <legend><?php echo String::insert(__('Edit Template Section for ":template_page_name" '), array("template_page_name" => $templatePage['name'])); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('width', array('min' => 1, 'max' => 12));
    echo $this->Form->input('description');
    echo $this->Form->input('order', array('min' => 0));
    echo $this->Form->hidden('page_id');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('Cancel'), $list_url); ?></li>
  </ul>
</div>
