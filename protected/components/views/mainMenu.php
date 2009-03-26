<ul>
<?php foreach($items as $item): ?>
<li><?php echo CHtml::link($item['label'],$item['url'],	$item['htmlOptions']); ?></li>
<?php endforeach; ?>
</ul>
