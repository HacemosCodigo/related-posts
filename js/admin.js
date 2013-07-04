;(function($){

	"use strict";

	$(function(){


		/**
		 * Releted posts tabs
		 */
		$('ul#related a').on('click', function (e) {
			e.preventDefault();

			if( $(this).attr('href') === '#posts-titles' ){
				RelatedPosts.loadSimilarPostTitles();
			}

			RelatedPosts.toggleTabs(this).toggleContent(this);
		});


		/**
		 * Related posts remove button
		 */
		$('.related-post').live('click', function (e) {
			e.preventDefault();
			RelatedPosts.destroy(this);
		});


		/**
		 * Trigger related checkboxes
		 */
		$('div#titlewrap #title').on('keyup', function(){
			$('ul#related a').trigger('click');
		});


		/**
		 * Guardar metadata
		 */
		$('input#publish').on('click', function (e) {
			e.preventDefault();

			var post_id = RelatedPosts.getCurrentPostID();

			// savePostMeta( post_id, meta_key, meta_value )
			var selected = RelatedPosts.savePostMeta(
				post_id,
				'related-posts',
				RelatedPosts.getSeleccionadosData()
			);

			var titles = RelatedPosts.savePostMeta(
				post_id,
				'related-posts-titles',
				RelatedPosts.getSimilarTitleData()
			);

			selected.done(function (data){
				//console.log(data);
			});

			titles.done(function (data){
				//console.log(data);
			});

		});


	});

})(jQuery);