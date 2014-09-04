<ul class="list-menu">
        <li>
                <?php
                        echo $this->Form->create('Coversheet', array(
				'url' => array_merge(
					array('action' => 'index'),
					$this->params['pass']
				),
				'class' => 'form-search form-inline',
				'novalidate' => true,
			));
                ?>

                        <?php
			echo $this->Form->input('search', array('div' => false, 'label' => false));
                        ?>
                    <?php echo $this->Form->input('user_id',array('options' => array($users), 'default' => $this->Session->read('application.user_id'), 'empty' => 'Show All', 'style' => 'width: 75%;')); ?>

                    <?php
                    echo $this->Form->input('app_status', array('label' => 'Application Status', 'options' => array(
                        'saved'=>'saved',
                        'validate'=>'validate',
                        'completed'=>'completed',
                        'pending'=>'pending',
                        'signed'=>'signed'
                    ), 'default' => $this->Session->read('application.status'),'empty' => 'Show All', 'style' => 'width: 75%;')); 
                    ?>
                    
                    <?php echo $this->Form->input('coversheet_status', array('label' => 'Coversheet Status', 'options' => array(
                        'saved'=>'saved',
                        'validated'=>'validated',
                        'sent'=>'sent'
                        ), 'default' => $this->Session->read('coversheet.status'),'empty' => 'Show All', 'style' => 'width: 75%;')); ?>
                        <?php
                                echo $this->Form->submit(__('Search'), array('div' => false, 'name' => 'search'));
		    		echo $this->Form->end();
                        ?>
        </li>
</ul>
