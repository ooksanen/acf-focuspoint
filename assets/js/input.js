(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {

		console.log( $field );
		
		// Cache jquery selectors
		// Values to get/set
		var $id 	= $field.find('.acf-focuspoint-id'),
			$top 	= $field.find('.acf-focuspoint-top'),
			$left 	= $field.find('.acf-focuspoint-left'),

			// Elements to get/set 
			$fp = $field.find('.acf-focuspoint'),
			$img = $field.find('.acf-focuspoint-img'),

			// Buttons to trigger events
			$add = $field.find('.add-image'),
			$del = $field.find('.acf-button-delete');

		// Hold/get our values
		var values = {
			id: 	$id.val(),
			top: 	$top.val(),
			left: 	$left.val(),
			size: 	$fp.data('preview_size')
		};

		// DOM elements
		var img = $img.get(0)

		// To hold WP media frame
		var file_frame;

	    // When we click the add image button...
		$add.on('click', function(){

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: 'Select Image',
				button: { text: 'Select' }
			});

			// When an image is selected..
			file_frame.on('select', function() {

				// Get selected image objects
				var attachment 	= file_frame.state().get('selection').first().toJSON(),
					src 		= attachment.sizes[values.size];

				// Make UI active (hide add image button, show canvas)
	        	$fp.addClass('active');

	        	if (src === undefined) {
	        		src = attachment;
	        	}

	        	// Set image to new src, triggering on load
				$img.attr('src', src.url);

				// Update our post values and values obj
				$id.val(attachment.id);

				values.id = attachment.id;

			});

			// Finally, open the modal
			file_frame.open();
		});

		// When we click the delete image button...
		$del.on('click', function(){
	    	
	    	// Reset DOM image attributes
	    	$img.removeAttr('src');

			// Hide canvas and show add image button
			$fp.removeClass('active');

		});

		$img.on('click', function (event) {
			var iw = $(this).outerWidth();
			var ih = $(this).outerHeight();
			var px = event.offsetX;
			var py = event.offsetY;
			var y_percentage = Math.round(((py / ih * 100) + Number.EPSILON) * 100) / 100;
			var x_percentage = Math.round(((px / iw * 100) + Number.EPSILON) * 100) / 100;
			$(this).siblings('.focal-point-picker').css({
				top: y_percentage + '%',
				left: x_percentage + '%'
			});
			$top.val(y_percentage);
			$left.val(x_percentage);
		});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'focal_point'
			acf.get_fields({ type : 'focuspoint'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="focuspoint"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}
	
	// Initialize dynamic block preview (editor).
	if( window.acf ) {
		window.acf.addAction( 'render_block_preview/type=focuspoint', initialize_field );
	}

})(jQuery);
