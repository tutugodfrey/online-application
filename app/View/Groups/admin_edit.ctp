<div class="container-fluid">
  <div class="row">
    <?php
    	$navItems = array('List Groups' => '/admin/groups/index');
        echo $this->Element('actionsNav', array('navLinks' => $navItems));
    ?>
    <div class="col-sm-9 col-lg-10">
    <!-- view page content -->
       <?php echo $this->Element('Groups/editFields'); ?>
    </div>
  </div>
</div>