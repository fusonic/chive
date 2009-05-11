<ul class="dropDown">
<?php foreach($items as $key=>$item) { ?>
	<li>
		<?php if($item['url']) { ?>
			<?php echo CHtml::openTag('a', array('href'=>$item['url'], 'class'=>'icon')); ?>
		<?php } ?>
		<?php if($item['icon']) { ?><img src="<?php echo Yii::app()->baseUrl . '/' . $item['icon'] ?>" alt="<?php echo $item['label'] ?>" title="" /><?php } ?>
		<span><?php echo $item['label']; ?><span>
		<?php if($item['url']) { ?>
			<?php echo CHtml::closetag('a'); ?>
		<?php } ?>
	</li>
<?php } ?>
</ul>
