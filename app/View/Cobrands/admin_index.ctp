<div class="container-fluid">
  <div class="row">
  	<?php
	echo $this->Element('actionsNav', $elVars); ?>
	<div class="col-sm-9 col-lg-10">
	  <!-- view page content -->
		<div class="panel panel-default">
		<div class="panel-heading"><u><strong>Cobrands</strong></u></div>
			<table class="table table-condensed table-striped table-hover">
				<tr>
					<th><?php echo $this->Paginator->sort('id'); ?></th>
					<th><?php echo $this->Paginator->sort('partner_name'); ?></th>
					<th><?php echo $this->Paginator->sort('partner_name_short'); ?></th>
					<th><?php echo $this->Paginator->sort('cobrand_logo_url'); ?></th>
					<th><?php echo $this->Paginator->sort('brand_logo_url'); ?></th>
					<th><?php echo $this->Paginator->sort('description'); ?></th>
					<th><?php echo $this->Paginator->sort('response_url_type'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<?php foreach ($cobrands as $cobrand): ?>
				<tr class="<?php echo $cobrand['Cobrand']['partner_name_short'] ?>">
					<td><?php echo h($cobrand['Cobrand']['id']); ?></td>
					<td><?php echo h($cobrand['Cobrand']['partner_name']); ?></td>
					<td><?php echo h($cobrand['Cobrand']['partner_name_short']); ?></td>
					<td><?php

						$cobrand_logo_url = $cobrand['Cobrand']['cobrand_logo_url'];
						$pos = strpos($cobrand_logo_url, '/');
						if ($pos === false) {
							echo h($cobrand_logo_url);
						} else {
							$popCoBrImgId = 'popCoBrImg' . $cobrand['Cobrand']['id'];							
							// assume it is a url
							echo $this->Html->link($cobrand_logo_url, 'javascript:void(0)',
								array('id' => $popCoBrImgId,
									'data-toggle' => 'popover','data-trigger' => 'focus',
									'onClick' => "$('#popOvrContentForpopCoBrImg" . 
										$cobrand['Cobrand']['id'] . "').html('<img width=\"200\" src=" . $cobrand_logo_url . " onError=\"this.onerror=null; this.src=\'/img/no-image.png\';\">')")
							);
							echo "<script>setElementPopOver('" . $popCoBrImgId . "')</script>";
						}
					?></td>
					<td><?php

						$brand_logo_url = $cobrand['Cobrand']['brand_logo_url'];
						$pos = strpos($brand_logo_url, '/');
						if ($pos === false) {
							echo h($brand_logo_url);
						} else {
							$poprandImgId = 'popBrandImg' . $cobrand['Cobrand']['id'];							
							// assume it is a url
							echo $this->Html->link($brand_logo_url, 'javascript:void(0)',
								array('id' => $poprandImgId,
									'data-toggle' => 'popover','data-trigger' => 'focus',
									'onClick' => "$('#popOvrContentForpopBrandImg" . 
										$cobrand['Cobrand']['id'] . "').html('<img width=\"200\" src=" . $brand_logo_url . " onError=\"this.onerror=null; this.src=\'/img/no-image.png\';\">')")
							);
							echo "<script>setElementPopOver('" . $poprandImgId . "')</script>";
						}
					?></td>
					<td><?php echo h($cobrand['Cobrand']['description']); ?></td>
					<td>
					<?php 
					if (!empty($responseUrlTypes[$cobrand['Cobrand']['response_url_type']])) {
						echo h($responseUrlTypes[$cobrand['Cobrand']['response_url_type']]); 
					}
					?></td>
					<td class="actions">
						<?php echo $this->Html->link(
							$this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-pencil')), 
							array('action' => 'edit', $cobrand['Cobrand']['id']), 
							array('escape' => false)); ?>
						<?php echo $this->Html->link(
							$this->Html->tag('span', '&nbsp', array('class' => 'glyphicon glyphicon-remove text-danger')), 
							array(
								'action' => 'delete', 
								$cobrand['Cobrand']['id']
							),
								 array('escape' => false),
								"Delete " . $cobrand['Cobrand']['partner_name'] . " Cobrand and associated Templates?"
						); ?>
						<?php echo $this->Html->link(__('List Templates'), CakeText::insert('/admin/cobrands/:id/templates', array('id' => $cobrand['Cobrand']['id']))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
			<?php echo $this->Element('paginatorBottomNav'); ?>
		</div>
	</div>
  </div>
</div>