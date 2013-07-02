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



	function get_similar_posts(){
		global $wpdb;
		$results = $wpdb->get_results(
			"FALTA ESTE QUERY", OBJECT
		);
		echo json_encode($results);
		exit;
	}
	add_action('wp_ajax_get_similar_posts', 'get_similar_posts');
	add_action('wp_ajax_nopriv_get_similar_posts', 'get_similar_posts');



	function get_all_posts(){
		global $wpdb;
		$results = $wpdb->get_results(
			"SELECT ID as value, post_title as label FROM wp_posts
				WHERE post_status = 'publish'
					AND post_type != 'attachment';", OBJECT
		);
		echo json_encode($results);
		exit;
	}
	add_action('wp_ajax_get_all_posts', 'get_all_posts');
	add_action('wp_ajax_nopriv_get_all_posts', 'get_all_posts');



	function get_similar_titles(){
		global $wpdb;
		$search = isset($_POST['search']) ? $_POST['search'] : '';
		$results = $wpdb->get_results(
			"SELECT ID, post_title FROM wp_posts
				WHERE post_status = 'publish'
					AND post_type != 'attachment'
					AND post_title REGEXP '$search'
					GROUP BY ID
						ORDER BY CASE
							WHEN post_title LIKE '$search%'      THEN 0
							WHEN post_title LIKE '% %$search% %' THEN 1
							WHEN post_title LIKE '%$search'      THEN 2
							ELSE 3
						END, post_title LIMIT 10;", OBJECT
		);
		echo json_encode($results);
		exit;
	}
	add_action('wp_ajax_get_similar_titles', 'get_similar_titles');
	add_action('wp_ajax_nopriv_get_similar_titles', 'get_similar_titles');