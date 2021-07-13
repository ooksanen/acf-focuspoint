<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acffp_acf_field_focuspoint') ) :


class acffp_acf_field_focuspoint extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'focuspoint';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('FocusPoint', 'acffp');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'jquery';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'preview_size'	=>	'large',
			'library'		=> 'all',
			'mime_types'	=> '',
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('focuspoint', 'error');
		*/
		
		$this->l10n = array();
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		

		// do not delete!
    	parent::__construct();


	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		
		// clear numeric settings
		
		// clear numeric settings
		$clear = array(
			'min_width',
			'min_height',
			'min_size',
			'max_width',
			'max_height',
			'max_size'
		);
		
		foreach( $clear as $k ) {
			if( empty($field[$k]) ) {
				$field[$k] = '';
			}
		}
		
		// Preview size select
		acf_render_field_setting( $field, array(
			'label'			=> __('Preview Size','acf-focuspoint'),
			'instructions'	=> __('Image used to create a FocusPoint. Should be around the same image ratio as Image Size','acf-focuspoint'),
			'type'			=> 'select',
			'name'			=> 'preview_size',
			'choices'		=>	acf_get_image_sizes()
		));
		
		
		// library
		acf_render_field_setting( $field, array(
			'label'			=> __('Library','acf'),
			'instructions'	=> __('Limit the media library choice','acf'),
			'type'			=> 'radio',
			'name'			=> 'library',
			'layout'		=> 'horizontal',
			'choices' 		=> array(
				'all'			=> __('All', 'acf'),
				'uploadedTo'	=> __('Uploaded to post', 'acf')
			)
		));
		
		
		// Min sizes
		acf_render_field_setting( $field, array(
			'label'			=> __('Minimum', 'acf-focuspoint'),
			'instructions'	=> __('Restrict which images can be uploaded. Note: this is not working yet!', 'acf-focuspoint'),
			'type'			=> 'text',
			'name'			=> 'min_width',
			'prepend'		=> __('Width', 'acf-focuspoint'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_height',
			'prepend'		=> __('Height', 'acf-focuspoint'),
			'append'		=> 'px',
			'_append' 		=> 'min_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_size',
			'prepend'		=> __('File size', 'acf-focuspoint'),
			'append'		=> 'MB',
			'_append' 		=> 'min_width'
		));	
		
		
		// Max sizes
		acf_render_field_setting( $field, array(
			'label'			=> __('Maximum', 'acf-focuspoint'),
			'instructions'	=> __('Restrict which images can be uploaded. Note: this is not working yet!', 'acf-focuspoint'),
			'type'			=> 'text',
			'name'			=> 'max_width',
			'prepend'		=> __('Width', 'acf-focuspoint'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_height',
			'prepend'		=> __('Height', 'acf-focuspoint'),
			'append'		=> 'px',
			'_append' 		=> 'max_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_size',
			'prepend'		=> __('File size', 'acf-focuspoint'),
			'append'		=> 'MB',
			'_append' 		=> 'max_width'
		));	
		
		
		// allowed type
		acf_render_field_setting( $field, array(
			'label'			=> __('Allowed file types','acf'),
			'instructions'	=> __('Comma separated list. Leave blank for all types','acf'),
			'type'			=> 'text',
			'name'			=> 'mime_types',
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {

		// Merge defaults
		$field = array_merge($this->defaults, $field);
		
		// Get set image id
		$id = (isset($field['value']['id'])) ? $field['value']['id'] : '';

		// data vars
		$data = array(
			'top'		=>	isset($field['value']['top']) ? $field['value']['top'] : '',
			'left'		=>	isset($field['value']['left']) ? $field['value']['left'] : '',
		);
		
		// If we already have an image set...
		if ($id) {
			
			// Get image by ID, in size set via options
			$img = wp_get_attachment_image_src($id, $field['preview_size']);
						
		}
			
		// If image found...
		// Set to hide add image button / show canvas
		$is_active 	= ($id) ? 'active' : '';

		// And set src
		$url = ($id) ? $img[0] : '';
		
		// create Field HTML
		?>

		<div class="acf-focuspoint acf-image-uploader <?php echo $is_active; ?>" data-preview_size="<?php echo $field['preview_size']; ?>" data-library="<?php echo $field['library']; ?>" data-mime_types="<?php echo $field['mime_types']; ?>">

			<input data-name="acf-focuspoint-img-id" type="hidden" name="<?php echo $field['name']; ?>[id]" value="<?php echo $id; ?>" />

			<?php foreach ($data as $k => $d): ?>
				<input data-name="acf-focuspoint-<?php echo $k ?>" type="hidden" name="<?php echo $field['name']; ?>[<?php echo $k ?>]" value="<?php echo $d ?>" />
			<?php endforeach ?>

			<div class="focuspoint-image <?php echo $id && wp_attachment_is_image( $id ) ? 'has-image' : 'no-image' ?>">
				<img data-name="acf-focuspoint-img" src="<?php echo $url; ?>">
				<img class="focal-point-picker" src="<?php echo $this->settings['url']; ?>assets/images/focal-point-picker.svg" style="top: <?php echo $data['top']; ?>%; left: <?php echo $data['left']; ?>%;">
				<div class="focuspoint-selection-layer"></div>
				<a class="acf-button-delete acf-icon -cancel acf-icon-cancel dark" data-name="remove"></a>
			</div>
			
			<div class="view hide-if-value">
			    <p><?php _e('No image selected','acf'); ?> <a data-name="add" class="acf-button button" href="#"><?php _e('Add Image','acf'); ?></a></p>
			</div>

		</div>

		<?php

	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script('acffp', "{$url}assets/js/input.min.js", array('jquery', 'acf-input'), $version );
		wp_enqueue_script('acffp');
    		wp_enqueue_media();
		
		
		// register & include CSS
		wp_register_style('acffp', "{$url}assets/css/input.min.css", array('acf-input'), $version );
		wp_enqueue_style('acffp');
		
	}
	
	
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	function update_value( $value, $post_id, $field ) {
		
		if( empty( $value['id'] ) ){
			return false;
		}
		if( empty( $value['left'] ) && empty( $value['top'] ) ){
			$value['left'] = 50;
			$value['top'] = 50;
		}

		return $value;
		
	}
	
	
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	
	
	function validate_value( $valid, $value, $field, $input ){

		// vd( $valid );
		// vdd( empty($value['id']) );

		// Bail early if field not required and value not set
		if( $field['required'] === 0 && !$value['id'] ) return true;
		
		// bail early if field required and id empty		
		if( $field['required'] !== 0 && empty($value['id']) ) return false;
		
		// bail ealry if id not numeric
		if( !is_numeric($value['id']) ) return false;

		$image = wp_get_attachment_image_src( $value['id'], 'full' );
		$image_size_kb = filesize( get_attached_file( $value['id'] ) );
		
		if( !empty($field['min_width']) && $image[1] < $field['min_width'] ) {
			$valid = sprintf( __('Image width must be at least %dpx.', 'acf-focuspoint'), $field['min_width'], $image[1], $image[2] );
		}
		elseif( !empty($field['min_height']) && $image[2] < $field['min_height'] ) {
			$valid = sprintf( __('Image height must be at least %dpx.', 'acf-focuspoint'), $field['min_height'], $image[1], $image[2] );
		}
		elseif( !empty($field['min_size']) && $image_size_kb < $field['min_size'] * (1024 * 1024) ) {
			$valid = sprintf( __('File size must be at least %d&nbsp;KB.', 'acf-focuspoint'), size_format( $field['min_size'] * (1024 * 1024), 2), size_format( $image_size_kb, 2 ) );
		}
		elseif( !empty($field['max_width']) && $image[1] > $field['max_width'] ) {
			$valid = sprintf( __('Image width must not exceed %dpx.', 'acf-focuspoint'), $field['max_width'], $image[1], $image[2] );
		}
		elseif( !empty($field['max_height']) && $image[2] > $field['max_height'] ) {
			$valid = sprintf( __('Image height must not exceed %dpx.', 'acf-focuspoint'), $field['max_height'], $image[1], $image[2] );
		}
		elseif( !empty($field['max_size']) && $image_size_kb > $field['max_size'] * (1024 * 1024) ) {
			$valid = sprintf( __('File size must not exceed %d&nbsp;KB.', 'acf-focuspoint'), size_format( $field['max_size'] * (1024 * 1024), 2), size_format( $image_size_kb, 2 ) );
		}
		else{
			$valid = true;
		}
		
		// return
		return $valid;

	}
	
	
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// initialize
new acffp_acf_field_focuspoint( $this->settings );


// class_exists check
endif;

?>
