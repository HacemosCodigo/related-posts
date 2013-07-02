;(function($){

	"use strict";

	$(function(){

		window.RelatedPosts = {};

		RelatedPosts.toggleTabs = function (active){
			$('ul.related-posts-tabs li').removeClass('tabs');
			$(active).parent().addClass('tabs');
			return active;
		}

		RelatedPosts.toggleContent = function (active){
			var content = $(active).attr('href');
			$('#related-container .tabs-panel').hide();
			$(content).show();
		}

		$('ul.related-posts-tabs a').on('click', function (e) {
			e.preventDefault();
			RelatedPosts.toggleTabs( this );
			RelatedPosts.toggleContent( this );
		});


	});

})(jQuery);