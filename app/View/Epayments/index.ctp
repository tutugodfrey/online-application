<?php
/*foreach($epayments as $i => $epayment) {
    $epayment['Epayment']['url'] = $this->Html->url(array(
        'action'=>'view',
        $epayment['Epayment']['id']
    ), true);
    $epayments[$i] = $epayment;
}
 * 
 */
echo json_encode($epayments);
//echo json_encode($var);
?>