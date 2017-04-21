<?php 
/** 
*
* This navigation element should only be used for existing controller actions and corresponding views.
* The view should position the navbar using the standard Bootstrap grid system in a .container-fluid.
* The nav item that corresponds to the page currently in view will be automagically added 
* (if not in $navLinks array) and activated.
*
* @var array $navLinks containing nav link descriptions and corresponding urls e.g. array('Link Label' => 'url')
* @var array $htmlContent to render anything other than a link set the well formed HTML content in this numerically indexed varible.
*			HTML content will be displayed contiguously starting at the top of the nav.
*/
 ?>
<?php $attributes = array('class' => "list-group-item");?>
<div class="col-sm-3 col-lg-2">
  <nav class="navbar navbar-default navbar-fixed-side list-group panel-info">
	<!-- normal collapsible navbar markup -->
		<li class="list-group-item panel-heading">
			<h4 class="panel-title"><strong>Actions</strong></h4>
		</li>
		<?php 
		if (!in_array($this->here, $navLinks)) {
			$viewName = Inflector::humanize(Inflector::tableize($this->name));
			if ($this->action === 'admin_index') {
				$viewName .= ' List';
			} else {
				$viewName .= ' ' . Inflector::humanize($this->action);
			}
			/*Remove the word admin as it might confuse some users*/
			$viewName = str_replace('Admin', '', $viewName);
			$navLinks = array_merge(array($viewName => $this->here), $navLinks);
		}

		if (isset($htmlContent)) {
			foreach ($htmlContent as $navItem) {
				echo $this->Html->tag('li', $navItem, $attributes);
			}
		}
		if (isset($navLinks)) {
			foreach ($navLinks as $desc => $url) {
				if ($url === $this->here) {
					$activeNavAttr['class'] = $attributes['class'] . ' list-group-item-success';
					echo $this->Html->tag('li', __($desc), $activeNavAttr);
				} else {
					echo $this->Html->link(__($desc), $url, $attributes);
				}
			}
		}
		?>
	
  </nav>
</div>