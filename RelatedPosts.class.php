<?php

	/**
	 * Class RelatedPosts
	 */
	class RelatedPosts {

		public $related;
		private $wpdb;



		public function __construct()
		{
			global $wpdb;
			$this->wpdb = &$wpdb;

			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
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
			wp_enqueue_script( 'mqrp-admin-script', plugins_url('js/admin.js', __FILE__ ), array('jquery'), false, true );
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
			?><div id="related-container" class="categorydiv">
				<ul class="related-posts-tabs">
					<li class="tabs"><a href="#posts-select">Seleccionar</a></li>
					<li><a href="#posts-titles">Por Titulo</a></li>
				</ul>

				<div id="posts-select" class="tabs-panel">
					<p>
						<input type="text" class="form-input-tip" size="20" autocomplete="off">
						<input type="button" class="button" value="Add">
					</p>
				</div>

				<div id="posts-titles" class="tabs-panel" style="display:none;">
					<ul>
						<li><label class="selectit"><input value="1" type="checkbox" name="post_related[]" checked="checked"> Title 1</label></li>
						<li><label class="selectit"><input value="2" type="checkbox" name="post_related[]"> Title 2</label></li>
						<li><label class="selectit"><input value="3" type="checkbox" name="post_related[]"> Title 3</label></li>
					</ul>
				</div>
			</div><?php
		}

	}