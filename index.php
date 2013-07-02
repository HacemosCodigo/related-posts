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