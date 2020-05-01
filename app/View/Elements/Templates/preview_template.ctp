<div class="panel panel-primary">
	<div class="media">
		<div class="media-left media-middle">
			<img class="media-object" src="<?php echo $templateData['thumbnail_url']; ?>" style="width: 74px; height: 74px;">
		</div>
		<div class="media-body">
			<h4 class="media-heading text-info text-center"><?php echo $templateData['name']; ?></h4>
			<?php 
				echo '<div><strong>Template ID: </strong>'. $templateData['id'] . '</div>';
				echo '<div><strong>File name: </strong>'. $templateData['filename'] . '</div>';
				echo '<div><strong>Signer order enabled: </strong>'. $templateData['signer_sequencing'] . '</div>';
				echo '<div><strong>Roles: </strong>'. implode(', ', Hash::extract($templateData, 'roles.{n}.name')) . '</div>';
				echo '<div><strong>Created: </strong>'. date('m/d/Y h:i:s a', strtotime($templateData['created_at']));
				echo ' | <strong>Updated: </strong>'. date('m/d/Y h:i:s a', strtotime($templateData['updated_at'])) . '</div>';
			?>
		</div>
	</div>
	<hr />
	<div class="media">
		<div class="col col-sm-12 col-xs-12 col-md-12 col-md-12 bg-info" style="max-height: 500px;overflow: auto;">
			<?php foreach ($templateData['page_image_urls'] as $pImgUrl):?>
            <img style="width: inherit;margin-top:2px;" src="<?php echo $pImgUrl; ?>">
            <?php endforeach; ?>
        </div>
	</div>
</div>