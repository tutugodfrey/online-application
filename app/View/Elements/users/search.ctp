<ul class="list-menu">
        <li>
                <?php
                        echo $this->Form->create('User', array(
                                'action' => 'search',
                                'class' => 'form-search form-inline'));
                ?>
                        <?php
                                echo $this->Form->input('search', array(
                                        'style' => 'height: 24px; font-size: 18px; width: 80%;',
                                        'label' => false,
                                        'type' => 'text',
                                        'value' => $criteria,
                                        'placeholder' => 'Search Users'));
                        ?>            
                        <?php
                                echo $this->Form->submit('Search');
                                echo $this->Form->end();
?>
        </li>
</ul>