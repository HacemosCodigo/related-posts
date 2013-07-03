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
			?><div id="related-container" class="categorydiv">
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
						<!--
						<span>
							<a id="post_tag-check-num-0" class="ntdelbutton delete-related-post">X</a>
							&nbsp;La cabeza de Xbox se va a Zynga
						</span>
						<span>
							<a id="post_tag-check-num-1" class="ntdelbutton delete-related-post">X</a>
							&nbsp;¿Cómo sacarle el mayor provecho a la batería de tu celular?
						</span>
						<span>
							<a id="post_tag-check-num-2" class="ntdelbutton delete-related-post">X</a>
							&nbsp;Científicos crean un traje capaz de aumentar la fuerza humana
						</span>
						-->
					</div><!-- tagchecklist -->
				</div>

				<div id="posts-titles" class="tabs-panel" style="display:none;">
					<ul id="related-posts-ul" class="categorychecklist form-no-clear">
					<!-- 	<li class="popular-category"><label class="selectit"><input value="1" type="checkbox" name="post_related[]" checked="checked"> Title 1</label></li>
						<li class="popular-category"><label class="selectit"><input value="2" type="checkbox" name="post_related[]"> Title 2</label></li>
						<li class="popular-category"><label class="selectit"><input value="3" type="checkbox" name="post_related[]"> Title 3</label></li> -->
					</ul>
				</div>
			</div>
			<script id="template-post-related" type="text/template">
				<span>
					<a id="post_tag-check-num-{{id}}" class="ntdelbutton delete-related-post">X</a>
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