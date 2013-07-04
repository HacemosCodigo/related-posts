<?php

	/**
	 * Class RelatedPosts
	 */
	class RelatedPosts {

		public $related, $post_types;
		private $wpdb;


		public function __construct()
		{
			global $wpdb;
			$this->wpdb = &$wpdb;

			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'localize_admin_scripts' ) );
		}


		/**
		 * Register and enqueue admin styles.
		 */
		public function enqueue_admin_styles()
		{
			wp_enqueue_style( 'mqrp-admin-styles', plugins_url('css/admin.css', __FILE__ ) );
		}


		/**
		 * Register and enqueue admin scripts.
		 */
		public function enqueue_admin_scripts()
		{
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'mqrp-admin-script', plugins_url('js/admin.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'), false, true );
		}


		/**
		 * Localize admin scripts.
		 */
		public function localize_admin_scripts()
		{
			wp_localize_script('mqrp-admin-script', 'ajax_url',  get_bloginfo('wpurl').'/wp-admin/admin-ajax.php');
		}




		/**
		 * Registrar los metaboxes para cada post type
		 * @param $post_types Array
		 */
		public function add_related_posts_metabox($post_types)
		{
			foreach ($post_types as $post_type) {
				add_meta_box(
					'related-posts',
					'Posts Relacionados',
					array('RelatedPosts', 'display_related_posts_metabox'), //callback
					$post_type,
					'side'
				);
			}
		}


		/**
		 * add_related_posts_metabox callback function: muestra el metabox
		 * @param $post WP_Post object
		 */
		public function display_related_posts_metabox($post)
		{
			$selected = get_post_meta($post->ID, 'related-posts', true);
			$titles   = get_post_meta($post->ID, 'related-posts-titles', true);

			$selected = $selected ? $selected : array();
			$titles   = $titles   ? $titles   : array();

			?><div id="related-container" class="categorydiv">


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
							</span>
						<?php } ?>
						<!--
						<span>
							<a id="post_tag-check-num-0" class="ntdelbutton delete-related-post">X</a>
							&nbsp;La cabeza de Xbox se va a Zynga
						</span>
						-->
					</div><!-- tagchecklist -->
				</div>

				<div id="posts-titles" class="tabs-panel" style="display:none;">
					<ul id="related-posts-ul" class="categorychecklist form-no-clear">
					<?php foreach ($titles as $id) {
						$entrada = get_post($id, OBJECT); ?>
						<li class="popular-category">
							<label class="selectit">
								<input value="<?php echo $entrada->ID; ?>" type="checkbox" name="post_related[]" class="post-related-title" checked="checked">
								<?php echo $entrada->post_title; ?>
							</label>
						</li>
					<?php } ?>
						<!-- <li class="popular-category"><label class="selectit"><input value="1" type="checkbox" name="post_related[]" checked="checked"> Title 1</label></li> -->
					</ul>
				</div>
			</div>
			<script id="template-post-related" type="text/template">
				<span>
					<a id="post-relacionado-{{id}}" class="ntdelbutton related-post" data-id="{{id}}">X</a>
					&nbsp;{{title}}
				</span>
			</script>
			<script id="template-post-checkbox" type="text/template">
				<li class="popular-category">
					<label class="selectit">
						<input value="{{id}}" type="checkbox" name="post_related[]" class="post-related-title" {{checked}}>
						{{title}}
					</label>
				</li>
			</script>
			<?php
		}

	}