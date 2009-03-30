<div>
	<% if($htmlOptions && count($htmlOptions) > 0) { %>
		<% foreach($htmlOptions AS $key=>$value) { %>
			<%= $key . "=\"" . $value . "\" "; %>
		<% } %>
	<% } %>
	<ul class="select">
	<?php foreach($items as $key=>$item): ?>
		<li>
			<% if($item['url']) { %>
				<%= CHtml::openTag('a', array('href'=>$item['url'], 'class'=>'icon')); %>
			<% } %>
			<% if($item['icon']) { %><img src="<%= $item['icon'] %>" alt="<%= $icon['label'] %>" title="" /><% } %>
			<span><?php echo $item['label']; ?><span>
			<% if($item['url']) { %>
				<%= CHtml::closetag('a'); %>
			<% } %>
		</li>
	<?php endforeach; ?>
	</ul>
</div>