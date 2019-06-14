                <?php
			echo $this->Form->create('Coversheet', 
				array(
					'url' => array_merge(
						array(
							'action' => 'admin_index'),
							$this->params['pass']
					),
					'inputDefaults' => array(
						'div' => 'form-group',
						'label' => false,
						'wrapInput' => false,
						'class' => 'form-control'
					),
					'class' => 'well well-sm form-inline col-md-12',
					'novalidate' => true,
				)
			);
			echo $this->Form->input('search', 
				array('placeholder' => 'Search Coversheets')
			);
             //set default only for non-admin users
			$defaultUsrSelect = (in_array($this->Session->read('Auth.User.group'), array('admin')))? null : $user_id;
			echo $this->Form->input('user_id', 
				array(
					'options' => array($users), 
					'default' => $defaultUsrSelect,
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
