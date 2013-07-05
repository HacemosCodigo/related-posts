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


		public function save_related_posts_meta($post_id){
			if( !current_user_can('edit_page', $post_id)){
				return $post_id;
			}
			if(defined('DOING_AUTOSAVE') and DOING_AUTOSAVE){
				return $post_id;
			}
			if ( isset($_POST['related_posts']) ){
				update_post_meta( $post_id, 'related_posts', $_POST['related_posts'] );
			}else{
				delete_post_meta( $post_id, 'related_posts' );
			}

			if ( isset($_POST['related_posts_titles']) ){
				update_post_meta( $post_id, 'related_posts_titles', $_POST['related_posts_titles'] );
			}else{
				delete_post_meta( $post_id, 'related_posts_titles' );
			}
		}


		/**
		 * add_related_posts_metabox callback function: muestra el metabox
		 * @param $post WP_Post object
		 */
		public function display_related_posts_metabox($post)
		{
			require_once('metabox-related-posts.php');
		}

	}