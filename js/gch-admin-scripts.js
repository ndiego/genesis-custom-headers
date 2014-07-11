jQuery(document).ready(function($){

	//Image Uploader function                  
	gch_imageUpload = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function() {
			var frame = wp.media({
				title : 'Choose or Upload an Image',
				multiple : false,
				library : { type : 'image' }, //only can upload images
				button : { text : 'Use Selected Image' }
			});

			// Handle results from media manager
			frame.on( 'close', function( ) {
				var attachments = frame.state().get( 'selection' ).toJSON();
				gch_imageUpload.render( attachments[0] );
			});

			frame.open();
			return false;
		},

		// Output Image preview and populate widget form
		render : function( attachment ) {				
			$( '#gch_image_preview' ).attr( 'src', attachment.url );
			$( '#gch_custom_image' ).val( attachment.url );
			$( '#gch_custom_image_alt' ).val( attachment.alt );
			$( '.gch-image-default' ).addClass( 'hidden' );
			$( '#gch_image_preview' ).removeClass( 'hidden' );				
		},

	};
        
    
    /* Removed the image */
  	$( '#gch_remove_button' ).click( function() {
  		var empty = '';
  		
  		$( '#gch_image_preview' ).attr( 'src', empty ).addClass( 'hidden' );
  		$( '.gch-image-default' ).removeClass( 'hidden' );
 	  	$( '#gch_custom_image' ).val( empty );
 	  	$( '#gch_custom_image_alt' ).val( empty );
  	});
    
    /* Shows and hides all header options on selection */
	$( '#gch_enable_header' ).change( function(){
		if ( this.checked ) {
			$( '.gch-enabled').show();
		} else {
			$( '.gch-enabled').hide();
		}
	});
	
	/* Shows and hides custom image uploader on selection */
	$( '#gch-metabox' ).on( 'click', '.gch-show-image input', function() {
		var input_val = $(this).val();        
		   
		if ( input_val == 'custom' ) {
			$( '#gch-image-uploader' ).show();
		} else if ( input_val == 'none' || input_val == 'featured' ) {
			$( '#gch-image-uploader' ).hide();
		}
	});
	
	/* Shows and hides header image on selection */
	$( '#gch_enable_custom_position' ).change( function(){
		if ( this.checked ) {
			$( '.gch-custom-position-enabled').show();
		} else {
			$( '.gch-custom-position-enabled').hide();
		}
	});
	
	/* Shows and hides header image on selection */
	$( '#gch_enable_image' ).change( function(){
		if ( this.checked ) {
			$( '.gch-image-enabled').show();
		} else {
			$( '.gch-image-enabled').hide();
		}
	});
	
	/* Shows and hides header slideshow on selection */
	$( '#gch_enable_slideshow' ).change( function(){
		if ( this.checked ) {
			$( '.gch-slideshow-enabled').show();
		} else {
			$( '.gch-slideshow-enabled').hide();
		}
	});
	
	/* Shows and hides header custom content on selection */
	$( '#gch_enable_custom_content' ).change( function(){
		if ( this.checked ) {
			$( '.gch-custom-content-enabled').show();
		} else {
			$( '.gch-custom-content-enabled').hide();
		}
	});
	
	/* Shows and hides header scripts on selection */
	$( '#gch_enable_header_raw' ).change( function(){
		if ( this.checked ) {
			$( '.gch-header-raw-enabled').show();
		} else {
			$( '.gch-header-raw-enabled').hide();
		}
	});

	 	
	/* Shows and hides Plugin Frontend Markup on Settings page */	
	$( '.gch-html-toggle' ).click( function(){
		$( '.gch-html' ).toggle();
	});
	
	/* Shows and hides Plugin Frontend Styles on Settings page */	
	$( '.gch-css-toggle' ).click( function(){
		$( '.gch-css' ).toggle();
	});
   
});