
<div class='container'>
	<div class="row">
		<section id="wizard">
			<form id="onlineapp" method="get" action="">
				<div id="rootwizard">
					<ul>
						<?php
						$numberOfPages = count($templatePages);
						for ($index = 0; $index < $numberOfPages; $index ++) {
							$templatePage = $templatePages[$index];
							$pageDescription = $templatePage['description'];
							if (strlen($pageDescription) == 0) {
								$pageDescription = $templatePage['name'];
							}
							$displayIndex = 1 + $index;
							$displayText = $templatePage['name'];
						?>
							<li title="<?php echo $pageDescription ?>">
								<a href="#tab<?php echo $displayIndex ?>" data-toggle="tab">
									<span class="badge"><?php echo $displayIndex; ?></span>
									<div class="connecting-line"></div>
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<?php echo $this->Element('Templates/Pages/wizardPager') ?>

					<div class="tab-content">

					<?php echo $this->Element('Templates/Pages/templatePage', array('numberOfPages' => $numberOfPages, 'templatePage' => $templatePage, 'requireRequiredFields' => $requireRequiredFields)) ?>

					</div>
					<div>Fields marked with * are required.</div>
					<?php echo $this->Element('Templates/Pages/wizardPager') ?>
				</div>
			</form>
		</section>
	</div>
</div>

<script type="text/javascript" src="/js/jquery-validate.1.11.11.js"></script>
<script type="text/javascript" src="/js/jquery-validate-additional-methods.js"></script>
<script type="text/javascript" src="/js/jquery.bootstrap.wizard.js"></script>
<script type="text/javascript" src="/js/cobrandedApplication.js"></script>

<link rel="stylesheet" type="text/css" href="/css/cobrandedApplication.css">
