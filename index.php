<?php
/**
 * WordPress Related Posts
 *
 * Descripción detallada ...
 *
 * @package   related-posts
 * @author    Los Maquiladores <info@losmaquiladores.com>
 * @link      http://hacemoscodigo.com
 *
 * @wordpress-plugin
 * Plugin Name: related-posts
 * Plugin URI:  git@github.com:HacemosCodigo/related-posts.git
 * Description: Se pueden relacionar posts por título o seleccionar a mano.
 * Version:     1.0
 * Author:      Los Maquiladores
 * Author URI:  http://hacemoscodigo.com
 */


	require_once('RelatedPosts.class.php');


	add_action('admin_menu', function() {
		RelatedPosts::add_related_posts_metabox(
			array('post', 'videos', 'resenas')
		);
	});


	add_action('admin_init', function(){
		$related_posts = new RelatedPosts();
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



	function mq_save_post_meta(){
		$post_id    = isset($_POST['post_id'])    ? $_POST['post_id']    : '';
		$meta_key   = isset($_POST['meta_key'])   ? $_POST['meta_key']   : '';
		$meta_value = isset($_POST['meta_value']) ? $_POST['meta_value'] : '';
		$result     = update_post_meta( $post_id, $meta_key, $meta_value );
		echo json_encode($result);
		exit;
	}
	add_action('wp_ajax_mq_save_post_meta', 'mq_save_post_meta');
	add_action('wp_ajax_nopriv_mq_save_post_meta', 'mq_save_post_meta');



	function mq_get_related_posts($post_id){
		$related_posts  = get_post_meta( $post_id, 'related-posts', true );
		$related_titles = get_post_meta( $post_id, 'related-posts-titles', true );
		return array_merge( $related_posts, $related_titles );
	}