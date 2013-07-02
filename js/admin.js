;(function($){

	"use strict";

	$(function(){

		window.RelatedPosts = {};


		RelatedPosts.toggleTabs = function (active){
			$('ul#related li').removeClass('tabs');
			$(active).parent().addClass('tabs');
			return this;
		};

		RelatedPosts.toggleContent = function (active) {
			var content_id = $(active).attr('href');
			$('#related-container .tabs-panel').hide();
			$(content_id).show();
			return this;
		};

		RelatedPosts.getSimilarPosts = function (post_title) {
			var ajax_response = $.post( ajax_url, {
				post_title: post_title,
				action: 'get_similar_posts'
			}, 'json' );

			return ajax_response;
		};

		$('ul#related a').on('click', function (e) {
			e.preventDefault();
			if( $(this).attr('href') === '#posts-titles' ){
				var post_title = $('div#titlewrap #title').val();
				var posts = RelatedPosts.getSimilarPosts(post_title);
				console.log(posts);
			}

			RelatedPosts.toggleTabs(this).toggleContent(this);
		});


	});

})(jQuery);