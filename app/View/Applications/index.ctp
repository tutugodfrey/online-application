<?php
    $incomplete = $waiting = $completed = 0;
?>

<h4>Welcome, <?php echo $email ?></h4>

Below you will find a list of the applications that you have saved, are awaiting signature, or are signed and completed. You may at any time refer to these documents by clicking to view it. If you have any questions about your applications, please feel free to contact your rep.

<h4>Incomplete Applications</h4>
<?php
    foreach ($applications as $key => $val) {
        if (in_array($val['status'], array('saved', 'validate', 'pending'))) {
            if ($val['dba_business_name']) $name = $val['dba_business_name'];
            elseif ($val['legal_business_name']) $name = $val['legal_business_name'];
            else $name = 'Unnamed Application';
            
            echo $this->Html->link($name, array('controller' => 'applications', 'action' => 'add', 1, $key, $hash)) . " &nbsp; {$val['created']}<br />";
            unset($applications[$key]);
            $incomplete = 1;
        }
    }
    
    if (!$incomplete) echo '<div>No saved applications found.</div>';
?>

<h4>Applications Waiting for Signature</h4>
<?php
    foreach ($applications as $key => $val) {
        if ($val['status'] == 'completed') {
            if ($val['dba_business_name']) $name = $val['dba_business_name'];
            elseif ($val['legal_business_name']) $name = $val['legal_business_name'];
            else $name = 'Unnamed Application';
            
            echo $this->Html->link($name, array('controller' => 'applications', 'action' => 'add', 1, $key, $hash)) . " &nbsp; {$val['created']}<br />";
            unset($applications[$key]);
            $waiting = 1;
        }
    }
    
    if (!$waiting) echo '<div>No pending applications found.</div>';
?>

<h4>Signed and Completed Applications</h4>
<?php
    foreach ($applications as $key => $val) {
        if ($val['status'] == 'signed') {
            if ($val['dba_business_name']) $name = $val['dba_business_name'];
            elseif ($val['legal_business_name']) $name = $val['legal_business_name'];
            else $name = 'Unnamed Application';
            
            echo $this->Html->link($name, array('controller' => 'applications', 'action' => 'add', 1, $key, $hash)) . " &nbsp; {$val['created']}<br />";
            unset($applications[$key]);
            $completed = 1;
        }
    }
    
    if (!$completed) echo '<div>No completed applications found.</div>';
?>


