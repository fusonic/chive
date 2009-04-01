<ul class="tabMenu">
	<li <%= $this->getAction()->getId() == 'browse' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/browse" class="icon">
			<com:Icon size="16" name="browse" text="database.browse" />
			<span><%= Yii::t('database','browse') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'structure' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/structure" class="icon">
			<com:Icon size="16" name="structure" text="database.structure" />
			<span><%= Yii::t('database','structure') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'sql' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/sql" class="icon">
			<com:Icon size="16" name="sql" text="database.sql" />
			<span><%= Yii::t('database','sql') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'insert' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','insert') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','operations') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','truncate') %></span>
		</a>
	</li>
	<li <%= $this->getAction()->getId() == 'xxx' ? 'class="active"' : '' %>>
		<a href="<?php echo Yii::app()->baseUrl; ?>/database/project_affiliate2date#tables/ctd1_cmm_commissionmodel/insert" class="icon">
			<com:Icon size="16" name="insert" text="database.sql" />
			<span><%= Yii::t('database','drop') %></span>
		</a>
	</li>
</ul>
<div style="clear: both;"></div>

<div id="content">
	<?php echo $content; ?>
</div>