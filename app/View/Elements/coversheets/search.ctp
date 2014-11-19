                <?php
			echo $this->Form->create('Coversheet', 
				array(
					'url' => array_merge(
						array('action' => 'admin_search'),
						$this->params['pass']
					),
					'inputDefaults' => array(
						'div' => 'form-group',
						'label' => false,
						'wrapInput' => false,
						'class' => 'form-control'
					),
					'class' => 'well form-inline',
					'novalidate' => true,
				)
			);
// @todo create mechanism for creating coversheets from coversheets view
// this will require, application_id and user_id			
/*			echo $this->Html->link('New Coversheet',
          			array(
                       			'controller' => 'coversheets',
                        		'action' => 'add',
					'admin' => false
				),
				array(
					'class' => 'btn btn-primary pull-right',
					'title' => 'New Coversheet'
				)
			);
*/
			echo $this->Form->input('search', 
				array('placeholder' => 'Search Coversheets')
			);
                        
			echo $this->Form->input('user_id', 
				array(
					'options' => array($users), 
					'default' => $user_id,
					'empty' => 'Users - All',
				)
			); 

			echo $this->Form->input('app_status', 
				array(
					'options' => array(
						'saved'=>'saved',
						'validate'=>'validate',
						'completed'=>'completed',
						'pending'=>'pending',
						'signed'=>'signed'
					),
					'empty' => 'App Status - All',
				)
			); 
                    
			echo $this->Form->input('coversheet_status', 
				array(
					'options' => array(
						'saved'=>'saved',
						'validated'=>'validated',
						'sent'=>'sent'
					),
					'empty' => 'CS Status - All', 
				)
			);

			echo $this->Form->button($this->Html->tag('span', '',
				array('class' => 'glyphicon glyphicon-search')
				),
				array(
					'div' => 'form-group',
					'class' => 'btn btn-success',
					'name' => 'Search',
					'type' => 'submit'
				)
			);
		    		echo $this->Form->end();
                        ?>
