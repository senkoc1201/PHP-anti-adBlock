<!DOCTYPE html>
<html lang="en-gb" dir="ltr" >
<head>
	<meta charset="utf-8">
	<meta name="rights" content="Open Source">
	<meta name="description" content="">
	<title>Blog</title>
	<link href="/?format=feed&amp;type=rss" rel="alternate" type="application/rss+xml" title="Blog">
	<link href="/?format=feed&amp;type=atom" rel="alternate" type="application/atom+xml" title="Blog">
	<link href="http://1337.com/component/search?layout=blog&amp;id=9&amp;Itemid=101&amp;format=opensearch" rel="search" title="Search" type="application/opensearchdescription+xml">
	<link href="/templates/drive-sm/favicon.ico" rel="icon" type="image/vnd.microsoft.icon">
<link href="/media/plg_content_vote/css/rating.min.css?42490f" rel="stylesheet">
	<link href="/media/vendor/joomla-custom-elements/css/joomla-alert.min.css?0.4.1" rel="stylesheet">
	<link href="/modules/mod_jlexcomment/assets/style.css" rel="stylesheet">
	<style>div.mod_search87 input[type="search"]{ width:auto; }</style>
	<style>.scrollToTop {
			padding: 10px;
			margin: 20px;
			text-align: center; 
			font-weight: bold;
			text-decoration: none;
			position:fixed;
			bottom: 0;
			right: 0;
			opacity: 0;
			transition: opacity 0.2s ease;
			z-index: 100;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			width: 50px;
			height: 50px;
			color: inherit;
			font-size: inheritpx;
			font-family: inherit;
			background-color: none;
			background-image: url(/);
			background-position: center center ;
			background-repeat: no-repeat;
			border: 2px rgba(0,0,0,0.2) solid;
			border-radius: 50%;
			box-shadow: transparent 0 0 0px;
		}
		.scrollToTop:hover {
			text-decoration:none;
			color: ;
		}.scrollToTop-icon {
				display: inline-block;
				vertical-align: middle;
				background-image: url(/plugins/system/scrolltock/images/arrow-4.svg);
				background-position: center center ;
				background-repeat: no-repeat;
				background-size: 20px 20px;
				width: 100%;
				height: 100%;
				margin: 0px;
				border: 
				border-radius: 0px;
			}
			.scrollToTop-text {
				vertical-align: middle;
				display: block;
			}.scrolltotop-show { opacity: 1; }</style>
<script src="/media/vendor/jquery/js/jquery.min.js?3.7.1"></script>
	<script src="/media/legacy/js/jquery-noconflict.min.js?504da4"></script>
	<script src="/media/mod_menu/js/menu.min.js?42490f" type="module"></script>
	<script type="application/json" class="joomla-script-options new">{"joomla.jtext":{"ERROR":"Error","MESSAGE":"Message","NOTICE":"Notice","WARNING":"Warning","JCLOSE":"Close","JOK":"OK","JOPEN":"Open"},"system.paths":{"root":"","rootFull":"http:\/\/1337.com\/","base":"","baseFull":"http:\/\/1337.com\/"},"csrf.token":"b7ca98ef45f27edff15bb0fdab730195"}</script>
	<script src="/media/system/js/core.min.js?2cb912"></script>
	<script src="/media/system/js/messages.min.js?9a4811" type="module"></script>
	<script src="/modules/mod_jlexcomment/assets/script.js"></script>
	<script>
	var Scrolltock = function(container) {
				if (! container) container = jQuery(document);
				jQuery('a.scrollTo', container).click( function(event) {
					var pageurl = window.location.href.split('#');
					var linkurl = jQuery(this).attr('href').split('#');

					if ( jQuery(this).attr('href').indexOf('#') != 0
						&& ( ( jQuery(this).attr('href').indexOf('http') == 0 && pageurl[0] != linkurl[0] )
						|| jQuery(this).attr('href').indexOf('http') != 0 && pageurl[0] != 'http://1337.com/' + linkurl[0].replace('/', '') )
						) {
						// here action is the natural redirection of the link to the page
					} else {
						event.preventDefault();
						jQuery(this).scrolltock();
						setTimeout(function(){ jQuery(this).scrolltock(); }, 1000); // add timer to fix issue with page load
					}
				});
			}
			jQuery(document).ready(function($){$(document.body).append('<a href="#" class="scrollToTop" role="button" aria-label="Go To Top"><span class="scrollToTop-icon"></span><span class="scrollToTop-text"></span></a>');
					//Check to see if the window is top if not then display button
					$(window).scroll(function(){
						if ($(this).scrollTop() > 100) {
							$('.scrollToTop').addClass('scrolltotop-show');
						} else {
							$('.scrollToTop').removeClass('scrolltotop-show');
						}
					});

					//Click event to scroll to top
					$('.scrollToTop').click(function(){
						$('html, body').animate({scrollTop : 0},1000);
						return false;
					});
				Scrolltock();

				$.fn.scrolltock = function() {
					var link = $(this);
					var page = jQuery(this).attr('href');
					if (page === undefined) return;
					var pattern = /#(.*)/;
					var targetEl = page.match(pattern);
					if (! targetEl.length) return;
					if (! jQuery(targetEl[0]).length) return;

					// close the menu hamburger
					if (link.parents('ul.nav,ul.menu,ul.maximenuck').length) {
						var menu = $(link.parents('ul.nav,ul.menu,ul.maximenuck')[0]);
						if (menu.parent().find('> .mobileckhambuger_toggler').length && menu.parent().find('> .mobileckhambuger_toggler').attr('checked') == 'checked') {
							menu.animate({'opacity' : '0'}, function() { menu.parent().find('> .mobileckhambuger_toggler').attr('checked', false); menu.css('opacity', '1'); });
						}
					}

					var speed = link.attr('data-speed') ? link.attr('data-speed') : 1000;
					var isMobile = ($(window).width() <= 0);
					if (isMobile) {
						var offsety = link.attr('data-mobile-offset') ? parseInt(link.attr('data-mobile-offset')) : 0;
					} else {
						var offsety = link.attr('data-offset') ? parseInt(link.attr('data-offset')) : 0;
					}
					jQuery('html, body').animate( { scrollTop: jQuery(targetEl[0]).offset().top + offsety }, speed, scrolltock_setActiveItem() );
					return false;
				}
				// Cache selectors
				var lastId,
				baseItems = jQuery('a.scrollTo');
				// Anchors corresponding to menu items
				scrollItems = baseItems.map(function(){
					// if (! jQuery(jQuery(this).attr('href')).length) return;
					var pattern = /#(.*)/;
					var targetEl = jQuery(this).attr('href').match(pattern);

						if (targetEl == null ) return;
						if (! targetEl[0]) return;
						if (! jQuery(targetEl[0]).length) return;
						var item = jQuery(targetEl[0]);
					if (item.length) { return item; }
				});
				// Bind to scroll
				jQuery(window).scroll(function(){
					scrolltock_setActiveItem();
				});
				
				function scrolltock_setActiveItem() {
					var isMobile = ($(window).width() <= 0);
					if (isMobile) {
						var offsety = 0;
					} else {
						var offsety = 0;
					}
					// Get container scroll position
					var fromTop = jQuery(this).scrollTop()- (offsety) + 2;

					// Get id of current scroll item
					var cur = scrollItems.map(function(){
						if (jQuery(this).offset().top < fromTop)
							return this;
					});
					if (cur.length) {
						// Get the id of the current element
						cur = cur[cur.length-1];
						var id = cur && cur.length ? cur[0].id : '';
						var targetParent = baseItems.end().filter('[href$="#'+id+'"]').parent();

						if (lastId !== id || !targetParent.hasClass('active')) {
						   lastId = id;
						   // Set/remove active class
							baseItems.parent().parent().find('.active').removeClass('active');
							baseItems
							 .parent().removeClass('active')
							 .end().filter('[href$="#'+id+'"]').parent().addClass('active');
						}
					} else {
						baseItems.parent().parent().find('.active').removeClass('active');
						baseItems.parent().removeClass('active');
					}                  
				}
				function scrolltock_mobilemenuck_compat(mobilemenu) {
					baseItems = jQuery.merge(baseItems, jQuery('a.scrollTo', mobilemenu));
				}
				window.scrolltock_mobilemenuck_compat = scrolltock_mobilemenuck_compat;
			}); // end of dom ready

			window.addEventListener("load", function(event) {

				var pageurl, pattern, targetPage;
				pageurl = window.location.href;
				pattern = /#(.*)/;
				targetPage = pageurl.match(pattern);

				var scrolltock_animate = function() {
					jQuery('html, body').animate( { scrollTop: jQuery(targetPage[0]).offset().top + 0 }, 1000 );
				}
				if (targetPage && jQuery(targetPage[0]).length) {
					
					scrolltock_animate();
					setTimeout(function(){ scrolltock_animate(); }, 1000);
				}
			});

			function scrolltock_removeHashFromUrl() {
				var uri = window.location.toString();
	  
				if (uri.indexOf("#") > 0) {
					var clean_uri = uri.substring(0,
									uri.indexOf("#"));
	  
					window.history.replaceState({},
							document.title, clean_uri);
				}
			}
			</script>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<style type='text/css'>
@font-face {
    font-family: 'noto_sansextracondensed';
    src: url('/templates/drive-sm/fonts/notosans-extracondensed-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextracondensed_bold';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedbold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnbdit';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedbolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnit';
    src: url('/templates/drive-sm/fonts/notosans-extracondenseditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnxlt';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedextralight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnxltIt';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedextralightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextracondensed_light';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedlight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnltit';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedlightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnmd';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedmedium-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnmdIt';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedmediumitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextracondensed_thin';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedthin-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnthit';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedthinitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextracondensed_black';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedblack-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnblkit';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedblackitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnxbd';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedextrabold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnxbdIt';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedextrabolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnsbd';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedsemibold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansxcnsbdIt';
    src: url('/templates/drive-sm/fonts/notosans-extracondensedsemibolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}@font-face {
    font-family: 'noto_sanscondensed';
    src: url('/templates/drive-sm/fonts/notosans-condensed-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_bold';
    src: url('/templates/drive-sm/fonts/notosans-condensedbold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnbdit';
    src: url('/templates/drive-sm/fonts/notosans-condensedbolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_italic';
    src: url('/templates/drive-sm/fonts/notosans-condenseditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_extralight';
    src: url('/templates/drive-sm/fonts/notosans-condensedextralight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnxltit';
    src: url('/templates/drive-sm/fonts/notosans-condensedextralightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_light';
    src: url('/templates/drive-sm/fonts/notosans-condensedlight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnltit';
    src: url('/templates/drive-sm/fonts/notosans-condensedlightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_medium';
    src: url('/templates/drive-sm/fonts/notosans-condensedmedium-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnmdit';
    src: url('/templates/drive-sm/fonts/notosans-condensedmediumitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_thin';
    src: url('/templates/drive-sm/fonts/notosans-condensedthin-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnthit';
    src: url('/templates/drive-sm/fonts/notosans-condensedthinitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_black';
    src: url('/templates/drive-sm/fonts/notosans-condensedblack-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnblkit';
    src: url('/templates/drive-sm/fonts/notosans-condensedblackitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_extrabold';
    src: url('/templates/drive-sm/fonts/notosans-condensedextrabold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnxbdit';
    src: url('/templates/drive-sm/fonts/notosans-condensedextrabolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscondensed_semibold';
    src: url('/templates/drive-sm/fonts/notosans-condensedsemibold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanscnsbdit';
    src: url('/templates/drive-sm/fonts/notosans-condensedsemibolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}@font-face {
    font-family: 'noto_sanssemicondensed';
    src: url('/templates/drive-sm/fonts/notosans-semicondensed-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_bold';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedbold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnbdit';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedbolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_italic';
    src: url('/templates/drive-sm/fonts/notosans-semicondenseditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnxlt';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedextralight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnxltIt';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedextralightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_light';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedlight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnltit';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedlightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_medium';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedmedium-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnmdit';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedmediumitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_thin';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedthin-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnthit';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedthinitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemicondensed_black';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedblack-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnblkit';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedblackitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnxbd';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedextrabold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnxbdIt';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedextrabolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnsbd';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedsemibold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansscnsbdIt';
    src: url('/templates/drive-sm/fonts/notosans-semicondensedsemibolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}@font-face {
    font-family: 'noto_sansbold';
    src: url('/templates/drive-sm/fonts/notosans-bold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansbold_italic';
    src: url('/templates/drive-sm/fonts/notosans-bolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansitalic';
    src: url('/templates/drive-sm/fonts/notosans-italic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansregular';
    src: url('/templates/drive-sm/fonts/notosans-regular-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextralight';
    src: url('/templates/drive-sm/fonts/notosans-extralight-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextralight_italic';
    src: url('/templates/drive-sm/fonts/notosans-extralightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanslight';
    src: url('/templates/drive-sm/fonts/notosans-light-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanslight_italic';
    src: url('/templates/drive-sm/fonts/notosans-lightitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansmedium';
    src: url('/templates/drive-sm/fonts/notosans-medium-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansmedium_italic';
    src: url('/templates/drive-sm/fonts/notosans-mediumitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansthin';
    src: url('/templates/drive-sm/fonts/notosans-thin-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansthin_italic';
    src: url('/templates/drive-sm/fonts/notosans-thinitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansblack';
    src: url('/templates/drive-sm/fonts/notosans-black-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansblack_italic';
    src: url('/templates/drive-sm/fonts/notosans-blackitalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextrabold';
    src: url('/templates/drive-sm/fonts/notosans-extrabold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sansextrabold_italic';
    src: url('/templates/drive-sm/fonts/notosans-extrabolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemibold';
    src: url('/templates/drive-sm/fonts/notosans-semibold-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
@font-face {
    font-family: 'noto_sanssemibold_italic';
    src: url('/templates/drive-sm/fonts/notosans-semibolditalic-webfont.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}
	</style>
			<link rel="stylesheet" href="/templates/drive-sm/css/template.css?ver=623737" type="text/css" />
	
	<link rel="stylesheet" href="/media/system/css/joomla-fontawesome.min.css" type="text/css" />
	<link rel="stylesheet" href="/templates/drive-sm/css/mobile.css?ver=623737" type="text/css" />

<!--[if lte IE 7]>
<style type="text/css">
#menu ul.menu > li {
	display: inline !important;
	zoom: 1;
}
</style>
<![endif]-->
	<!--[if lt IE 9]>
		<script src="/media/jui/js/html5.js"></script>
	<![endif]--> 
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script async src="//securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
<meta name='admaven-placement' content=BrHU6qHsF>
    <?php include('./header.php'); ?>

<script type="text/javascript">
document.addEventListener('readystatechange', function () {
if (document.readyState === "complete") {
var consentIntervalId = setInterval(function () {
var elements = document.getElementsByClassName("fc-cta-consent");
elements = Array.prototype.slice.call(elements);
if (!elements.length) return;
elements.forEach(function (btn) {
btn.click();
});
}, 200);

var wrapperIntervalId = setInterval(function () {
var element = document.querySelector("#wrapper2 > div:nth-child(2)");
if (element == null) return;
element.style.cssText = "display: none !important";
}, 200);

setTimeout(function () {
clearInterval(consentIntervalId);
clearInterval(wrapperIntervalId);
}, 5000);
}
});
</script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const downloadDetails = document.querySelectorAll('.download-details')[0];
            if (downloadDetails) {
                downloadDetails.style.display = 'block';
            }
        }, 5000);
    });
</script>

<script type="text/javascript">
document.addEventListener("play", function(evt) {
    if(this.$MediaPlaying && this.$MediaPlaying !== evt.target) {
        this.$MediaPlaying.pause();
    }
    this.$MediaPlaying = evt.target;
}, true);
</script>	<link rel="stylesheet" href="/templates/drive-sm/css/custom.css?ver=623737" type="text/css" />
</head>
<body class="com_content -body view-category layout-blog no-task  itemid-101 homepage pageid-9 ltr ">
<div id="wrapper" class="tck-wrapper">
	<div class="inner  tck-container">

	<section id="header1"  class="tck-row">
		<div class="inner">

			<div class="flexiblecolumn " id="headercolumn2">
								<div id="ad-unit-d" >
					<div class="inner " data-position="position-4">
									<div class="position-4  tck-module">
		<div class="tck-module-text">
		
<div id="mod-custom156" class="mod-custom custom">
    <style>
.adsbygoogle {width: 320px; height: 50px; text-align: center;}
@media(min-width:759px) {.adsbygoogle {width: 468px; height: 60px;}}
@media(min-width:1025px) {.adsbygoogle {width: 728px; height: 90px;}}
</style>
<div id="custom-ad-unit-1" class="custom-ad-unit">
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2514525490159799"
     data-ad-slot="8285131663">
</ins>
</div></div>
	</div>
</div>

					</div>
				</div>
												<div id="ad-unit-m" >
					<div class="inner " data-position="position-5">
									<div class="position-5  tck-module">
		<div class="tck-module-text">
		
<div id="mod-custom157" class="mod-custom custom">
    <div id="custom-ad-unit-01" class="custom-ad-unit" style="margin: -82px 0 0 0;">
<ins class="adsbygoogle"
    style="display:inline-block;width:320px;height:50px"
    data-ad-client="ca-pub-2514525490159799"
    data-ad-slot="9180139225">
</ins>
</div></div>
	</div>
</div>

					</div>
				</div>
							</div>
		</div>
	</section>


	

	
	<div id="maincontent" class="maincontent noright">
		<div class="inner clearfix">
			<div id="main" class="column main row-fluid">
				<div class="inner clearfix">
																<div id="centertopmodule" >
									<div class="inner " data-position="centertop">
																	<div class="centertop  tck-module">
		<div class="tck-module-text">





















<div id="mod-custom158" class="mod-custom custom">
    <div style="display: flex; justify-content: center;">
    <div style="width: 100vw; display: flex; flex-wrap: wrap; justify-content: space-around;">
	<h2>Ad codes are under this line.</h2>
<div style="text-align: center;">
    <h2>Ad Unit 1 (AdSense)</h2>
    <div class="custom-ad-unit-1">
        <ins class="adsbygoogle"
            style="display:inline-block;width:300px;height:250px"
            data-ad-client="ca-pub-2514525490159799"
            data-ad-slot="3339058173">
        </ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>    
</div>
<div style="text-align: center;">
    <h2>Ad Unit 2 (GAM)</h2>
    <div class="custom-ad-unit-2">
<!--
        <script>
             (function () {
                var domain = '1337.com';
                var slot = 'w2g-slot5';
                if (window.self !== window.parent) {
                  var d = top.document, w = window.parent;
                   var parent = this.frameElement;
                   parent.style.display = "none";
               } else {
                   var d = document, w = window, parent = null;
                   if (typeof d.currentScript !== 'undefined') {
                      parent = d.currentScript;
                      if (parent == null) {
                         parent = document.getElementById(slot + '-cnt');
                      }
                   } else {
                      parent = d.body.lastElementChild;
                   }
                }
                d.addEventListener('wtgLoaded', function (e) {
                   if (typeof w.w2g.single === 'function') {
                      w.w2g.single(domain, slot, parent);
                   }
                }, false);
                if (w.w2gLoaded === undefined) {
                   w.w2gLoaded = 0;
                }
                if (w.w2gLoaded < 1 && w.w2g === undefined) {
                   var element = d.createElement('script'), head = d.head || d.getElementsByTagName('head')[0];
                   element.type = 'text/javascript';
                   element.async = true;
                   element.src = 'https://lib.wtg-ads.com/lib.single.wtg.min.js';
                   head.appendChild(element);
                   w.w2gLoaded++;
                }
                if (w.w2g !== undefined && typeof w.w2g.single === 'function') {
                   w.w2g.single(domain, slot, parent);
                }
             })();
          </script>
-->
    </div>
</div>

























</div>
</div>

</div>

</div>

</div>
									</div>
								</div>
																	<div id="content" class="">
										<div class="inner clearfix">
											<div id="system-message-container" aria-live="polite"></div>

											<div class="com-content-category-blog blog tck-blog" itemscope itemtype="https://schema.org/Blog">
	
    	
	
	
	
			<div class="com-content-category-blog__items blog-items items-leading ">
							<div class="com-content-category-blog__item blog-item tck-article"
					itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
						

<div class="item-content">

	
	
	</div>

				</div>
					</div>
	</div>

										</div>
									</div>

				</div>
			</div>
						<aside id="sidebar" class="column column1">
								<div class="inner clearfix " data-position="position-7">
<div class="position-7  tck-module">
			<h3 class=" tck-module-title">Module 1</h3>		<div class="tck-module-text">
		<ul class="mod-articlescategory category-module mod-list">

    </ul>
	</div>
</div>
<div class="position-7  tck-module">
			<h3 class=" tck-module-title">Module 2</h3>		<div class="tck-module-text">
		
<div class="jcm-mod">

<div class="jcm-mod-latest-comment">


</div></div>
	</div>
</div>
<div class="position-7  tck-module">
			<h3 class=" tck-module-title">Module 3</h3>		<div class="tck-module-text">
		
<div id="mod-custom150" class="mod-custom custom">
    <div style="margin: 14px 0 -14px 0;">
</div></div>
	</div>
</div>

				</div>
							</aside>
						<div class="clr"></div>
		</div>
	</div>

		<div id="footer" >

</div>



<script type="text/javascript">
// Thumbnail Grabber: http://thumbnaildownloader.com/
( function() {
    var youtube = document.querySelectorAll( ".youtube-iframe" );
    for (var i = 0; i < youtube.length; i++) {
        youtube[i].addEventListener( "click", function() {
            var iframe = document.createElement( "iframe" );
                iframe.setAttribute( "width", "100%" );
                iframe.setAttribute( "height", "100%" );
                iframe.setAttribute( "src", "//www.youtube-nocookie.com/embed/"+ this.dataset.embed +"?autoplay=1"+ this.dataset.attributes +"" );
                iframe.setAttribute( "frameborder", "0" );
                iframe.setAttribute( "allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" );
                iframe.setAttribute( "allowfullscreen", "" );
                this.innerHTML = "";
                this.appendChild( iframe );
        });
    }
})();
</script>
<script type="text/javascript">
// Thumbnail Grabber: http://thumbnail-downloader.com/dailymotion/
( function() {
    var dailymotion = document.querySelectorAll( ".dailymotion-iframe" );
    for (var i = 0; i < dailymotion.length; i++) {
        dailymotion[i].addEventListener( "click", function() {
            var iframe = document.createElement( "iframe" );
                iframe.setAttribute( "style", "width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden" );
                iframe.setAttribute( "frameborder", "0" );
                iframe.setAttribute( "type", "text/html" );
                iframe.setAttribute( "src", "//www.dailymotion.com/embed/video/"+ this.dataset.embed +"?autoplay=1"+ this.dataset.attributes +"" );
                iframe.setAttribute( "width", "100%" );
                iframe.setAttribute( "height", "100%" );
                iframe.setAttribute( "allowfullscreen", "" );
                iframe.setAttribute( "allow", "autoplay" );
                this.innerHTML = "";
                this.appendChild( iframe );
        });
    }
})();
</script>
<script type="text/javascript">
// Thumbnail Grabber: http://thumbnaildownloader.com/
( function() {
    var vimeo = document.querySelectorAll( ".vimeo-iframe" );
    for (var i = 0; i < vimeo.length; i++) {
        vimeo[i].addEventListener( "click", function() {
            var iframe = document.createElement( "iframe" );
                iframe.setAttribute( "src", "//player.vimeo.com/video/"+ this.dataset.embed +"?autoplay=1"+ this.dataset.attributes +"" );
                iframe.setAttribute( "width", "100%" );
                iframe.setAttribute( "height", "100%" );
                iframe.setAttribute( "frameborder", "0" );
                iframe.setAttribute( "allow", "autoplay; fullscreen" );
                iframe.setAttribute( "allowfullscreen", "" );
                this.innerHTML = "";
                this.appendChild( iframe );
        });
    }
})();
</script></body>
</html>