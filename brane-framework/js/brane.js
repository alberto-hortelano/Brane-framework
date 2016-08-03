(function($) {
	"use strict";
	
	var devicepixelratio = ( $.isNumeric( window.devicepixelratio ) )? window.devicepixelratio: 1 ;

	var reload_images_block = false;

	$(document).ready(function(){
		$('#page_nope').on('click', 'a', function( e ){
			e.stopPropagation();
			e.preventDefault();
			var link = $(this).attr("href");
			$.ajax({
		        url: UpdateQueryString('brane_ajax','true',link),
		        data: {
		            'action': 'brane_ajax',
		            'url' : link
		        },
		        success:function(data) {
		        	$( '#page' ).html(data);
		        	window.history.pushState({"html":data.html,"pageTitle":data.pageTitle},"", link);
		        },
		        error: function( errorThrown ){
		            console.log(errorThrown);
		        }
		    });
		});
		var scripts = [];
		$.each( document.scripts, function(n,script){
			scripts.push(script.outerHTML);
		});
		
		if( $('#map').length ){
			var map = new google.maps.Map(document.getElementById('map'), {
			    center: {lat: -34.397, lng: 150.644},
			    scrollwheel: false,
			    styles:  [{
					featureType: "all",
					stylers: [
						{ saturation: -80 }
					]
				},{
					featureType: "road.arterial",
					elementType: "geometry",
					stylers: [
						{ hue: "#00ffee" },
						{ saturation: 50 }
					]
				},{
					featureType: "poi.business",
					elementType: "labels",
					stylers: [
						{ visibility: "off" }
					]
				}],
			    zoom: 8
			});
		}
	});

	function UpdateQueryString(key, value, url) {
	    if (!url) url = window.location.href;
	    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
	        hash;

	    if (re.test(url)) {
	        if (typeof value !== 'undefined' && value !== null)
	            return url.replace(re, '$1' + key + "=" + value + '$2$3');
	        else {
	            hash = url.split('#');
	            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
	            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
	                url += '#' + hash[1];
	            return url;
	        }
	    }
	    else {
	        if (typeof value !== 'undefined' && value !== null) {
	            var separator = url.indexOf('?') !== -1 ? '&' : '?';
	            hash = url.split('#');
	            url = hash[0] + separator + key + '=' + value;
	            if (typeof hash[1] !== 'undefined' && hash[1] !== null) 
	                url += '#' + hash[1];
	            return url;
	        }
	        else
	            return url;
	    }
	}

	function reload_images() {

		if( reload_images_block ) {

			reload_images_block = false;

		}

		reload_images_block = true;

		$('img').not('.brane_done').each( function(){
			
			$this = $(this);

			var real_width = $this.width() * devicepixelratio; 

			if( isScrolledIntoView( this ) ){
				
				if( $this.hasClass( 'brane_preprocessed' ) ){ 

					var id = $this.data( 'brane_id' );

					$.each( brane.img.idsizes[ id ], function ( width, height_url ){
					
						if( width >= real_width ){

							$.each( height_url, function( height, url ){

								$this.attr( 'src', brane.img.uploadurl + url + '&brane__' ).attr( 'srcset', '' );

								return false;
							});

							return false;
						}
					});

				}else if( $this.attr( 'src' ).indexOf( brane.img.uploadurl ) < 0 ){ // Not uploaded
					
					$this.addClass( 'brane_done' );

				}else{ // Preprocess

					var src = $this.attr( 'src' ).replace( brane.img.uploadurl, '' );

					if( brane.img.urlid.hasOwnProperty( src ) ){

						var id = brane.img.urlid[src];

						$this.addClass( 'brane_preprocessed' ).data( 'brane_id', id );

						$.each( brane.img.idsizes[ id ], function ( width, height_url ){
						
							if( width >= real_width ){

								$.each( height_url, function( height, url ){

									$this.attr( 'src', brane.img.uploadurl + url + '&brane__' ).attr( 'srcset', '' );

									return false;
								});

								return false;
							}
						});

					}else{

						$this.addClass( 'brane_done' ).attr( 'src', $this.attr( 'src' ) + '&brane__' );
					}
				}
			}
		});
	}

	// http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
	function isScrolledIntoView( el ){

		var height = $(el).height();
	    var rect = el.getBoundingClientRect();

	    return (
	        rect.top >= -height &&
	        rect.bottom <= $(window).height() + height
	    );
	}

	// http://stackoverflow.com/questions/18177174/how-to-limit-handling-of-event-to-once-per-x-seconds-with-jquery-javascript
	function debounce(func, interval) {
	    var lastCall = -1;
	    return function () {
	        clearTimeout(lastCall);
	        var args = arguments;
	        lastCall = setTimeout(function () {
	            func.apply(this, args);
	        }, interval);
	    };
	}
}( jQuery ));
