<ul class="tabMenu">
	<?php foreach($items AS $item) {?>

		<?php echo CHtml::openTag('li', $item['htmlOptions']); ?>
			<?php echo Html::ajaxLink($item['a']['href'], $item['a']['htmlOptions']); ?>
				<?php if($item['icon']) { ?>
					<?php echo Html::icon($item['icon']); ?>
				<?php } ?>
				<span><?php echo $item['label']; ?></span>
			<?php echo CHtml::closeTag('a'); ?>
		<?php echo CHtml::closeTag('li'); ?>

	<?php } ?>
</ul>
<div class="clear"></div>