;(function($){

	"use strict";

	$(function(){

		window.RelatedPosts = {
			Posts: {}
		};

		RelatedPosts.init = function () {
			var ajax_get_posts = RelatedPosts.getAllPosts();
			ajax_get_posts.done(function (data) {
				RelatedPosts.Posts = JSON.parse(data);
				$('#search-title').autocomplete({
					source: RelatedPosts.Posts,
					focus: function( event, ui ) {
						$('#search-title').val( ui.item.label );
						return false;
					},
					select: function( event, ui ) {
						console.log(ui.item.value);
						return false;
					}
				});
			});
		};

		RelatedPosts.getAllPosts = function () {
			var ajax_result = $.post( ajax_url, {
				action: 'get_all_posts'
			}, 'json' );
			return ajax_result;
		}

		RelatedPosts.toggleTabs = function (active) {
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

		RelatedPosts.init();


		// RelatedPosts.getSimilarPosts = function () {
		// 	var post_title    = $('div#titlewrap #title').val();
		// 	var ajax_response = $.post( ajax_url, {
		// 		post_title: post_title,
		// 		action: 'get_similar_posts'
		// 	}, 'json' );

		// 	return ajax_response;
		// };

		// RelatedPosts.getSimilarTitles = function (search) {
		// 	var ajax_response = $.post( ajax_url, {
		// 		search: search,
		// 		action: 'get_similar_titles'
		// 	}, 'json' );

		// 	return ajax_response;
		// };


		$('ul#related a').on('click', function (e) {
			e.preventDefault();
			// if( $(this).attr('href') === '#posts-titles' ){

			// 	$('#spinner_related').show();
			// 	var response = RelatedPosts.getSimilarPosts();

			// 	response.done(function (data) {
			// 		$('#spinner_related').hide();
			// 		console.log(data);
			// 	});
			// }
			RelatedPosts.toggleTabs(this).toggleContent(this);
		});



		$('#search-title').on('keydown', function (e) {
			// var search = $(this).val();
			// var ajax_result = RelatedPosts.getSimilarTitles( search );

			// ajax_result.done(function (data) {
			// 	console.log(data);
			// });
		});



	});

})(jQuery);