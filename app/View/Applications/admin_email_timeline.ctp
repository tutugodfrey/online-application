<h2>Email Timelines</h2>
<table cellpadding="0" cellspacing="0">
 <tr>
 <th>DBA</th>
 <th><?php echo $this->Paginator->sort('date', 'Date Sent'); ?></th>
 <th>Recipient(s)</th>
 <th>Email Type</th>
 </tr>
<?php foreach($applications as $application): ?>
 <?php foreach($application['EmailTimeline'] as $timeline): ?>
    <td><?php echo $application['Application']['dba_business_name'] ; ?></td>
<td><?php echo date('F j, Y g:i A',strtotime($timeline['date'])) ; ?></td>
<td><?php   
                    echo (!empty($timeline['recipient'])) ? $timeline['recipient'] : 'No Address';

                     ?></td>
<td><?php echo $timeline['EmailTimelineSubject']['subject'] ; ?></td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</table>
<?php
    if (in_array($this->Session->read('Auth.User.group'), array('admin', 'rep', 'manager'))) {
        echo $this->Html->link(
            'Return to Applications Admin',
            '/admin/applications/',
            array('style' => 'display: block; float: right;')
        );
    }
?>