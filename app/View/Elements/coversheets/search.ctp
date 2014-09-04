<ul class="list-menu">
        <li>
                <?php
			echo $this->Form->create('Coversheet', 
				array(
					'url' => array_merge(
						array('action' => 'admin_search'),
						$this->params['pass']
					),
					'class' => 'form-search form-inline',
					'novalidate' => true,
				)
			);

			echo $this->Form->input('search', 
				array(
					'div' => false, 
					'label' => false)
			);
                        
			echo $this->Form->input('user_id', 
				array(
					'options' => array($users), 
					'default' => $user_id,
					'empty' => 'Show All',
					'style' => 'width: 75%;')
				); 

			echo $this->Form->input('app_status', 
				array(
					'label' => 'Application Status', 
					'options' => array(
						'saved'=>'saved',
						'validate'=>'validate',
						'completed'=>'completed',
						'pending'=>'pending',
						'signed'=>'signed'
				),
				'empty' => 'Show All',
				'style' => 'width: 75%;')
			); 
                    
			echo $this->Form->input('coversheet_status', 
				array(
					'label' => 'Coversheet Status', 
					'options' => array(
						'saved'=>'saved',
						'validated'=>'validated',
						'sent'=>'sent'
					),
					'empty' => 'Show All', 
					'style' => 'width: 75%;'
				)
			);

		    echo $this->Form->submit(__('Search'), array('div' => false, 'name' => 'Search'));
		    		echo $this->Form->end();
                        ?>
        </li>
</ul>
