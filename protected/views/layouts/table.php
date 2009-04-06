<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/views/table/general.js', CClientScript::POS_HEAD); ?>

<ul class="tabMenu">
	<li <%= $this->getAction()->getId() == 'browse' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/<?php echo $this->schemaName; ?>/tables/<?php echo $this->tableName; ?>/browse" class="icon">
			<com:Icon size="16" name="browse" text="database.browse" />
			<span><%= Yii::t('database','browse') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'structure' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/<?php echo $this->schemaName; ?>#tables/<?php echo $this->tableName; ?>/structure" class="icon">
			<com:Icon size="16" name="structure" text="database.structure" />
			<span><%= Yii::t('database','structure') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'sql' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/<?php echo $this->schemaName; ?>#tables/<?php echo $this->tableName; ?>/sql" class="icon">
			<com:Icon size="16" name="sql" text="database.sql" />
			<span><%= Yii::t('database','sql') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'insert' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/<?php echo $this->schemaName; ?>#tables/<?php echo $this->tableName; ?>/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','insert') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/<?php echo $this->schemaName; ?>#tables/<?php echo $this->tableName; ?>/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','operations') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="javascript:void(0);" onclick="truncateTable('<?php echo $this->schemaName; ?>','<?php echo $this->tableName?>');" class="icon">
			<com:Icon size="16" name="truncate" text="database.truncate" />
			<span><%= Yii::t('database','truncate') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="javascript:void(0);" onclick="dropTable('<?php echo $this->schemaName; ?>','<?php echo $this->tableName?>');" class="icon">
			<com:Icon size="16" name="drop" text="database.drop" />
			<span><%= Yii::t('database','drop') %></span>
		</a>
	</li>
</ul>
<div style="clear: both;"></div>

<div id="truncateTableDialog" title="<?php echo Yii::t('database', 'truncateTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToTruncateTable', array('{table}'=>$this->tableName)); ?>
</div>
<div id="dropTableDialog" title="<?php echo Yii::t('database', 'dropTable'); ?>" style="display: none">
	<?php echo Yii::t('database', 'doYouReallyWantToDropTable', array('{table}'=>$this->tableName)); ?>
</div>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.form.js', CClientScript::POS_HEAD); ?>

<div id="content">
	<?php echo $content; ?>
</div>