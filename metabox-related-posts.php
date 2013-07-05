<?php
	$selected = get_post_meta($post->ID, 'related_posts', true);
	$titles   = get_post_meta($post->ID, 'related_posts_titles', true);

	$selected = $selected ? $selected : array();
	$titles   = $titles   ? $titles   : array(); ?>

	<div id="related-container" class="categorydiv">

		<input type="hidden" id="current-post-id" value="<?php echo $post->ID; ?>">

		<ul id="related" class="category-tabs">
			<li class="tabs"><a href="#posts-select">Seleccionar</a></li>
			<li><a href="#posts-titles">Por Titulo  <span id="spinner_related" class="spinner"></span></a></li>
		</ul>

		<div id="posts-select" class="tabs-panel">
			<p>
				<input type="text" id="search-title" class="form-input-tip" size="28" autocomplete="off">
				<input type="hidden" id="search-title-id" />
			</p>

			<div id="tagchecklist_related" class="tagchecklist">
				<?php foreach ($selected as $id) {
					$entrada = get_post($id, OBJECT); ?>
					<span>
						<a id="post-relacionado-<?php echo $entrada->ID; ?>" class="ntdelbutton related-post" data-id="<?php echo $entrada->ID; ?>">X</a>
						&nbsp;<?php echo $entrada->post_title; ?>
						<input type="hidden" name="related_posts[]" value="<?php echo $entrada->ID ?>">
					</span>
				<?php } ?>
			</div><!-- tagchecklist -->
		</div>

		<div id="posts-titles" class="tabs-panel" style="display:none;">
			<ul id="related-posts-ul" class="categorychecklist form-no-clear">
			<?php foreach ($titles as $id) {
				$entrada = get_post($id, OBJECT); ?>
				<li class="popular-category">
					<label class="selectit">
						<input value="<?php echo $entrada->ID; ?>" type="checkbox" name="related_posts_titles[]" class="post-related-title" checked="checked">
						<?php echo $entrada->post_title; ?>
					</label>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
	<script id="template-post-related" type="text/template">
		<span>
			<a id="post-relacionado-{{id}}" class="ntdelbutton related-post" data-id="{{id}}">X</a>
			&nbsp;{{title}}
			<input type="hidden" name="related_posts[]" value="{{id}}">
		</span>
	</script>
	<script id="template-post-checkbox" type="text/template">
		<li class="popular-category">
			<label class="selectit">
				<input value="{{id}}" type="checkbox" name="related_posts_titles[]" class="post-related-title" {{checked}}>
				{{title}}
			</label>
		</li>
	</script>