<div class="container-fluid">
  <div class="row">
    <?php
    $elVars['htmlContent'] = array(
        $this->Element('users/search')
    );
        echo $this->Element('actionsNav', $elVars);
    ?>
    <div class="col-sm-9 col-lg-10">
    <!-- view page content -->
        <div class="panel panel-default">
            <?php $thisViewTitle = __('Add User: '); ?>
            <div class="panel-heading"><strong><?php echo $thisViewTitle ?></strong></div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <?php 
                        echo $this->element('users/addEditForm');
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
  </div>
</div>