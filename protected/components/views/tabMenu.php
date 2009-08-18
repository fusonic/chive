<ul class="tabMenu">
	<?php foreach($items AS $item) {?>

		<?php echo CHtml::openTag('li', $item['htmlOptions']); ?>
			<?php echo CHtml::openTag('a', $item['a']['htmlOptions']); ?>
				<?php if($item['icon']) { ?><com:Icon size="16" name="{$item['icon']}" /><?php } ?>
				<span><?php echo $item['label']; ?></span>
			<?php echo CHtml::closeTag('a'); ?>
		<?php echo CHtml::closeTag('li'); ?>

	<?php } ?>
</ul>
<div class="clear"></div>