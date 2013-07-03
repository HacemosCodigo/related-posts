;(function($){

	"use strict";

	$(function(){


		window.template = function (id) {
			return $.trim( $(id).html() );
		}

		window.RelatedPosts = {
			Posts: {},
			AutoComplete: {},
			Template: {}
		};

		RelatedPosts.init = function () {

			this.Template = template('#template-post-related');

			var ajax_get_posts = RelatedPosts.getAllPosts(); // query todos los posts

			// getAllPosts ajax callback function
			ajax_get_posts.done(function (data) {

				RelatedPosts.Posts = JSON.parse(data);

				// jQuery UI autocomplete init method
				RelatedPosts.AutoComplete = $('#search-title').autocomplete({
					source: RelatedPosts.Posts,
					focus: function (event, ui) {
						$('#search-title').val( ui.item.label );
						RelatedPosts.AutoComplete.focused = { id: ui.item.value, title: ui.item.label };
						return false;
					},
					select: function (event, ui) {
						RelatedPosts.render({ id: ui.item.value, title: ui.item.label });
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
		};


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


		RelatedPosts.render = function (data) {

			var element = this.Template
							.replace(/{{id}}/, data.id)
							.replace(/{{title}}/, data.title)

			$('#tagchecklist_related').append( element );
			$('#search-title').val('');
		};


		RelatedPosts.renderCheckbox = function (data) {
			var checkboxTemplate = template('#template-post-checkbox');
			var element = checkboxTemplate
							.replace(/{{id}}/, data.id)
							.replace(/{{title}}/, data.title)
							.replace(/{{checked}}/, data.checked);
			$('#related-posts-ul').append( element );
		}


		RelatedPosts.filterDuplicatedCheckboxes  = function (posts) {
			var exclude = this.get_items_to_exclude();
			var unique  = posts.filter(function (post) {
				return ( $.inArray(post.value, exclude) === -1 );
			});
			return unique;
		};


		RelatedPosts.getSimilarPosts = function () {
			var post_title = $('div#titlewrap #title').val(); // tomar el titulo del post
			if( post_title === ''){ return false; }

				post_title = post_title.replace(/\b[a-zA-Z0-9]{1,3}\b/g, ''); // quitar palabras de 3 letras o menos
			var words = post_title.split(' ');                                // poner cada palabra en el array
				words = words.filter( function (word) { return word } );      // quitar elementos vacios del array

			var posts = RelatedPosts.Posts.filter(function (post) {
				var include = false;
				$.each(words, function (index, word) {
					if ( new RegExp(word,'i').test(post.label) ){ include = true; }
				});
				return include;
			});

			return RelatedPosts.filterDuplicatedCheckboxes(posts);
		};

		RelatedPosts.get_items_to_exclude = function () {
			var items = [];
			$('input.post-related-title').each(function (index, value) {
				items[index] = $(value).val();
			});
			return items;
		}


		RelatedPosts.checkboxError = function (string) {
			$('<p></p>',{
				class: 'howto',
				text: string
			}).appendTo('#related-posts-ul');
		};


		RelatedPosts.loadSimilarPostTitles = function(){

			var posts = this.getSimilarPosts();

			if ( ! posts ) {
				RelatedPosts.checkboxError('Ingresa el titulo del post');
			}else{
				$.each(posts, function (index, post) {
					RelatedPosts.renderCheckbox({ id: post.value, title: post.label });
				});
			}
		};


		RelatedPosts.init();


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
		$('.delete-related-post').live('click', function (e) {
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
			console.log('clicked!');
			console.log($(this));
		});

	});

})(jQuery);