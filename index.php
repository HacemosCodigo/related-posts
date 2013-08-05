<?php
/**
 * Maquiladores Related Posts
 *
 * Descripción detallada ...
 *
 * @package maquiladores-related-posts
 * @author  Los Maquiladores <info@losmaquiladores.com>
 * @link    http://hacemoscodigo.com
 *
 * @wordpress-plugin
 * Plugin Name: WordPress Related Posts
 * Plugin URI:  http://hacemoscodigo.github.io/related-posts/
 * Description: Se pueden relacionar posts por título o seleccionar a mano.
 * Version:     1.0
 * Author:      Los Maquiladores
 * Author URI:  http://hacemoscodigo.com
 */

	require_once('RelatedPosts.class.php');


	add_action('admin_menu', function() {
		RelatedPosts::add_related_posts_metabox();
	});


	add_action('save_post', function ($post_id){
		RelatedPosts::save_related_posts_meta($post_id);
	});


	add_action('admin_init', function(){
		$related_posts = new RelatedPosts();
	});


	add_action('plugins_loaded', function(){
		RelatedPosts::load_plugin_languages();
	});


	function mq_get_all_posts(){
		global $wpdb;
		$results = $wpdb->get_results(
			"SELECT ID as value, post_title as label FROM wp_posts
				WHERE post_status = 'publish'
					AND post_type != 'attachment';", OBJECT
		);
		echo json_encode($results);
		exit;
	}
	add_action('wp_ajax_mq_get_all_posts', 'mq_get_all_posts');
	add_action('wp_ajax_nopriv_mq_get_all_posts', 'mq_get_all_posts');



	function get_related_posts_by_term($post_id){
		global $wpdb;
		return $wpdb->get_col(
			"SELECT ID FROM wp_posts
				INNER JOIN wp_term_relationships AS tr ON object_id = ID
				INNER JOIN wp_term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
				INNER JOIN wp_terms AS t ON tt.term_id = t.term_id
					WHERE t.term_id IN (
						SELECT t.term_id FROM wp_posts
							INNER JOIN wp_term_relationships AS tr ON object_id = ID
							INNER JOIN wp_term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
							INNER JOIN wp_terms AS t ON tt.term_id = t.term_id
								WHERE ID = '$post_id'
					)
					AND post_status = 'publish'
						ORDER BY RAND()
							LIMIT 3;");
	}


	function mq_get_related_posts($post_id){
		$related_posts  = get_post_meta( $post_id, 'related_posts', true );
		$related_titles = get_post_meta( $post_id, 'related_posts_titles', true );

		if ( !$related_posts and !$related_titles ){
			return get_related_posts_by_term($post_id);
		}else{
			$related_posts  = $related_posts  ? $related_posts  : array();
			$related_titles = $related_titles ? $related_titles : array();
			return array_merge( $related_posts, $related_titles );
		}
	}
