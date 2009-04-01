<ul>
<?php foreach($items as $item): ?>
<li>
	<?php echo CHtml::openTag('a', $item['htmlOptions']); ?>
		<?php if($item['icon']) { ?><com:Icon size="16" name="{$item['icon']}" /><?php } ?>
		<span><?php echo $item['label']; ?></span>
	<?php echo CHtml::closeTag('a'); ?>
</li>
<?php endforeach; ?>
</ul>
