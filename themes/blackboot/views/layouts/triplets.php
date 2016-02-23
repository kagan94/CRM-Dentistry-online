<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-4">
		<p>
			<h2>Sidebar 1</h2>
			Sidebar content here
		</p>
	</div>
	<div id="content" class="span-14">
		<?php echo $content; ?>
	</div><!-- content -->
	<div class="span-4">
		<p>
			<h2>Sidebar 2</h2>
			Sidebar content here
		</p>
	</div>
</div>
<?php $this->endContent(); ?>
