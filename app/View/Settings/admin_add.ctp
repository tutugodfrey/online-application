<div class="settings form">
    <?php echo $this->Form->create('Setting');?>
    <fieldset>
        <legend>Add Setting</legend>
        <?php
        echo $this->Form->input('key', array('type' => 'text', 'required' => true));
        echo $this->Form->input('value', array('type' => 'text', 'required' => true));
        echo $this->Form->input('description');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>