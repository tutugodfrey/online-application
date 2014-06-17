<ul class="list-menu">
        <li>
                <?php
                        echo $this->Form->create('CobrandedApplication', array(
                                'url' => array_merge(
									array('action' => 'index'),
									$this->params['pass']
									),
                                'class' => 'form-search form-inline'));
                ?>
                        <?php
						echo $this->Form->input('search', array('div' => false, 'label' => false));
//                                echo $this->Form->input('search', array(
//                                        'style' => 'height: 24px; font-size: 18px; width: 70%;',
//                                        'label' => false,
//                                        'type' => 'text',
//                                        'value' => $criteria,
//                                        'placeholder' => 'Search'));
                        ?>
                    <?php echo $this->Form->input('Select User',array('options' => array($users), 'default' => $this->Session->read('application.user_id'), 'empty' => 'Show All', 'style' => 'width: 75%;')); ?>

                    <?php echo $this->Form->input('Status', array('options' => array(
                        'saved'=>'saved',
                        'validate'=>'validate',
                        'completed'=>'completed',
                        'pending'=>'pending',
                        'signed'=>'signed'
                    ), 'default' => $this->Session->read('application.status'),'empty' => 'Show All', 'style' => 'width: 75%;')); ?>
                        <?php
                                echo $this->Form->submit(__('Search'), array('div' => false));
								echo $this->Form->end();
                        ?>


        </li>
</ul>