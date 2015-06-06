<ul class="list-menu">
        <li>
                <?php
						echo $this->Form->create('User', array(
								'url' => array_merge(
									array(
										'action' => 'admin_index'
									),
									$this->params['pass']
								)
						));
				?>
                        <?php
							echo $this->Form->input('search', array(
								'style' => 'height: 24px; font-size: 18px; width: 80%;',
								'label' => false,
								'type' => 'text',
								'placeholder' => 'Search Users')
							);
						?>
                        <?php
							echo $this->Form->submit('Search');
							echo $this->Form->end();
						?>
        </li>
</ul>
