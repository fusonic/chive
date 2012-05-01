<table class="list" style="width: 50%; float: left;">
	<colgroup>
		<col style="width: 300px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2"><?php echo Yii::t('core', 'softwareInformation'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="padding-left: 15px;">
				<a href="http://www.chive-project.com">
					<img src="images/logo-big.png" alt="Chive Logo" title="Chive - Web based MySQL database management" />
				</a>
			</td>
			<td>
				<b><?php echo Yii::app()->name . ' ' . Yii::app()->params->version; ?></b><br/>
				<i>Web based MySQL database management</i><br/><br/><br/>
				Released under the <a href="http://www.gnu.org/copyleft/gpl.html">GPL License</a><br/>
				<a href="http://www.chive-project.com">http://www.chive-project.com</a><br/>
			</td>
		</tr>
	</tbody>
</table>
<table class="list" style="width: 49%; float: right;">
	<colgroup>
		<col style="width: 300px;"></col>
		<col></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="2"><?php echo Yii::t('core', 'maintainer'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="padding-left: 15px;">
				<a href="http://www.fusonic.net">
					<img src="images/fusonic.png" alt="Fusonic GmbH" title="Fusonic GmbH" />
				</a>
			</td>
			<td>
				<b>Fusonic GmbH</b><br/>
				Vorarlberger Wirtschaftspark 2<br/>
				6840 Götzis<br/>
				Austria<br/>
				<br/>
				<a href="http://www.fusonic.net">http://www.fusonic.net</a><br/>
			</td>
		</tr>
	</tbody>
</table>

<div class="clear"></div>
<br/>

<table class="list">
	<colgroup>
		<col style="width: 200px;"></col>
		<col></col>
		<col style="width: 150px;"></col>
	</colgroup>
	<thead>
		<tr>
			<th colspan="3"><?php echo Yii::t('core', 'usedLibraries'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="even">
			<td>
				<a href="http://www.yiiframework.com" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>Yii 1.1.1</span>
				</a>
			</td>
			<td>Yii is a high-performance component-based PHP framework best for developing large-scale Web applications.</td>
			<td>BSD</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://ace.ajax.org" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>Ace - Ajax.org Cloud9 Editor</span>
				</a>
			</td>
			<td>Ace is a standalone code editor written in JavaScript. Our goal is to create a web based code editor that matches and extends the features, usability and performance of existing native editors such as TextMate, Vim or Eclipse. It can be easily embedded in any web page and JavaScript application. Ace is developed as the primary editor for Cloud9 IDE and the successor of the Mozilla Skywriter (Bespin) Project.</td>
			<td>MPL, LGPL, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://jquery.com" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery 1.4.1</span>
				</a>
			</td>
			<td>jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://jqueryui.com" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery UI 1.7.2</span>
				</a>
			</td>
			<td>jQuery UI provides abstractions for low-level interaction and animation, advanced effects and high-level, themeable widgets, built on top of the jQuery JavaScript Library, that you can use to build highly interactive web applications.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://layout.jquery-dev.net/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery UI.Layout 1.2.0</span>
				</a>
			</td>
			<td>The UI.Layout plug-in can create any UI look you want - from simple headers or sidebars, to a complex application with toolbars, menus, help-panels, status bars, sub-forms, etc.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://malsup.com/jquery/form/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery Form 2.36</span>
				</a>
			</td>
			<td>The jQuery Form Plugin allows you to easily and unobtrusively upgrade HTML forms to use AJAX. The main methods, ajaxForm and ajaxSubmit, gather information from the form element to determine how to manage the submit process. Both of these methods support numerous options which allows you to have full control over how the data is submitted. Submitting a form with AJAX doesn't get any easier than this!</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://malsup.com/jquery/block/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery BlockUI 2.31</span>
				</a>
			</td>
			<td>The jQuery BlockUI Plugin lets you simulate synchronous behavior when using AJAX, without locking the browser. When activated, it will prevent user activity with the page (or part of the page) until it is deactivated. BlockUI adds elements to the DOM  to give it both the appearance and behavior of blocking user interaction.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://code.google.com/p/js-hotkeys/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery Hotkeys 0.7.8</span>
				</a>
			</td>
			<td>jQuery Hotkeys plugin lets you easily add and remove handlers for keyboard events anywhere in your code supporting almost any key combination. It takes one line of code to bind/unbind a hot key combination. </td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://code.google.com/p/jquery-purr/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery Purr 0.1.0</span>
				</a>
			</td>
			<td>Purr is a jQuery plugin for dynamically displaying unobtrusive messages in the browser. It is designed to behave much as the Mac OS X program "Growl".</td>
			<td>MIT</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://bassistance.de/jquery-plugins/jquery-plugin-autocomplete/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery Autocomplete 1.1</span>
				</a>
			</td>
			<td>Autocomplete an input field to enable users quickly finding and selecting some value, leveraging searching and filtering.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://www.appelsiini.net/projects/jeditable" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>Jeditable 1.7.1</span>
				</a>
			</td>
			<td>Edit in place plugin for jQuery.</td>
			<td>MIT</td>
		</tr>
		<tr class="odd">
			<td>
				<a href="http://www.texotela.co.uk/code/jquery/select/" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>jQuery Select 2.2.4</span>
				</a>
			</td>
			<td>Select box manipulation plugin for jQuery.</td>
			<td>MIT, GPL</td>
		</tr>
		<tr class="even">
			<td>
				<a href="http://www.phpmyadmin.net" class="icon">
					<?php echo Html::icon('globe'); ?>
					<span>PMA Query Parser</span>
				</a>
			</td>
			<td>Chive uses the query parsing algorithm of phpMyAdmin to analyze user-defined sql statements.</td>
			<td>GPL</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
	informationGeneral.setup();
	breadCrumb.set([
		{
			icon: 'info',
			href: 'javascript:chive.goto(\'information/about\')',
			text: '<?php echo Yii::t('core', 'about'); ?>'
		}
	]);
</script>