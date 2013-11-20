<?php
foreach($applications as $i => $application) {
    $apllication['Application']['url'] = $this->Html->url(array(
        'action'=>'view',
        $post['Application']['id']
    ), true);
    $applications[$i] = $application;
}
echo json_encode($applications);
?>