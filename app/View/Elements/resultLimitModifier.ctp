<?php
    $curPage = $this->Paginator->param('page');
    $countDiff = $this->Paginator->param('count') - $this->Paginator->param('start');
    $options = array(
        'controller' => $this->name,
        'action' => $this->action,
        'admin' => true,
        'page:' . $curPage,
        '?' => $this->request->query
      );
    echo "<span class='center-block text-center text-info strong small'>";
    echo 'Per Page: ';
    if ($countDiff > 25 && $this->request->query['limit'] != 25) {
      $options['?']['limit'] =  25;
      echo $this->Html->link('25', $options, array('class' => 'btn-xs btn-info')) . ', ' ;
    } else {
      echo '<span class="text-muted"><i>25</i></span>, ';
    }

    if ($countDiff > 50 && $this->request->query['limit'] != 50) {
      $options['?']['limit'] =  50;
      echo $this->Html->link('50', $options, array('class' => 'btn-xs btn-info')) . ', ' ;
    } else {
      echo '<span class="text-muted"><i>50</i></span>, ';
    }

    if ($countDiff > 100 && $this->request->query['limit'] != 100) {
      $options['?']['limit'] =  100;
      echo $this->Html->link('100', $options, array('class' => 'btn-xs btn-info'));
    } else {
      echo '<span class="text-muted"><i>100</i></span>';
    }
    echo "</span>";