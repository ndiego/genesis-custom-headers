<?php

defined( 'WPINC' ) or die;

add_action( 'add_meta_boxes', 'gch_create' );
/**
 * Create meta box to for all custom header settings
 */
function gch_create( $postType ) {

	// Get certain plugin settings
	$metabox_title = genesis_get_option( 'metabox_title', 'genesis-custom-header' ) ;
	
	// If custom metabox title not set, display default
	if ( $metabox_title == '' ) {
		$metabox_title = __( 'Genesis Custom Headers', 'genesis-custom-header' );
	}
	
	// Create an array of all available post types including all public custom post types
	$available_post_array = array_merge( array('page', 'post'), get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and' ) );
	
	// Create an empty array for our activated post types
	$activated_posts = array();
	
	// Fill our array with [post_type => activated]
	foreach ( $available_post_array as $available_post ) {
		$activated_posts[$available_post] = genesis_get_option( 'enable_' . $available_post, 'genesis-custom-header' ) == 1 ? 1: 0;
	}
	
	// Get the post type of the current page
	$type = get_post_type( get_the_ID() );
	
	// Check to see if the custom headers metabox should be shown on this page
	foreach ( $activated_posts as $activated_post => $activated ) {
		if ( $type == $activated_post && $activated == 1 ) {
			add_meta_box( 'gch-metabox', $metabox_title, 'gch_metabox_function', $postType, 'normal', 'high' );
		} 
	}
	
}


/**
 * Prints custom header metabox
 */
function gch_metabox_function( $post ) {

	// Get the post type of the current page
	$type = get_post_type( get_the_ID() );

	// Get our global plugin settings
	$gch_global_enable_header_image 	= genesis_get_option( 'enable_header_image', 'genesis-custom-header' ) ;
	$gch_global_enable_header_slideshow = genesis_get_option( 'enable_header_slideshow', 'genesis-custom-header' ) ;
	$gch_global_enable_header_content 	= genesis_get_option( 'enable_header_content', 'genesis-custom-header' ) ;
	$gch_global_enable_header_raw 		= genesis_get_option( 'enable_header_raw', 'genesis-custom-header' ) ;
	$gch_force_header_position 	  		= genesis_get_option( 'force_header_position', 'genesis-custom-header' ) ;

	$custom = get_post_custom( $post->ID );

	// Get page/post meta data
	$gch_enable_header			  = (isset($custom[ '_gch_enable_header' ][0]) ? $custom[ '_gch_enable_header' ][0] : 0);
	$gch_enable_custom_position   = (isset($custom[ '_gch_enable_custom_position' ][0]) ? $custom[ '_gch_enable_custom_position' ][0] : 0);
	$gch_custom_header_position   = (isset($custom[ '_gch_custom_header_position' ][0]) ? $custom[ '_gch_custom_header_position' ][0] : 'genesis_after_header');
	$gch_custom_position_priority = (isset($custom[ '_gch_custom_position_priority' ][0]) ? $custom[ '_gch_custom_position_priority' ][0] : '1');
	$gch_enable_image			  = (isset($custom[ '_gch_enable_image' ][0]) ? $custom[ '_gch_enable_image' ][0] : 0);
	$gch_image_type				  = (isset($custom[ '_gch_image_type' ][0]) ? $custom[ '_gch_image_type' ][0] : 'custom');
	$gch_custom_image  			  = (isset($custom[ '_gch_custom_image' ][0]) ? $custom[ '_gch_custom_image' ][0] : '');
	$gch_custom_image_alt		  = (isset($custom[ '_gch_custom_image_alt' ][0]) ? $custom[ '_gch_custom_image_alt' ][0] : '');
	$gch_image_caption			  = (isset($custom[ '_gch_image_caption' ][0]) ? $custom[ '_gch_image_caption' ][0] : '');
	$gch_background_image		  = (isset($custom[ '_gch_background_image' ][0]) ? $custom[ '_gch_background_image' ][0] : 0);
	$gch_enable_slideshow  		  = (isset($custom[ '_gch_enable_slideshow' ][0]) ? $custom[ '_gch_enable_slideshow' ][0] : 0);
	$gch_slider_shortcode		  = (isset($custom[ '_gch_slider_shortcode' ][0]) ? $custom[ '_gch_slider_shortcode' ][0] : '');
	$gch_soliloquy_slider		  = (isset($custom[ '_gch_soliloquy_slider' ][0]) ? $custom[ '_gch_soliloquy_slider' ][0] : 'none');
	$gch_revolution_slider  	  = (isset($custom[ '_gch_revolution_slider' ][0]) ? $custom[ '_gch_revolution_slider' ][0] : 'none');
	$gch_meta_slider			  = (isset($custom[ '_gch_meta_slider' ][0]) ? $custom[ '_gch_meta_slider' ][0] : 'none');
	$gch_sliderpro_slider		  = (isset($custom[ '_gch_sliderpro_slider' ][0]) ? $custom[ '_gch_sliderpro_slider' ][0] : 'none');	
	$gch_enable_custom_content	  = (isset($custom[ '_gch_enable_custom_content' ][0]) ? $custom[ '_gch_enable_custom_content' ][0] : 0);
	$gch_custom_content  		  = (isset($custom[ '_gch_custom_content' ][0]) ? $custom[ '_gch_custom_content' ][0] : '');
	$gch_enable_header_raw    	  = (isset($custom[ '_gch_enable_header_raw' ][0]) ? $custom[ '_gch_enable_header_raw' ][0] : 0);
	$gch_header_raw  		      = (isset($custom[ '_gch_header_raw' ][0]) ? $custom[ '_gch_header_raw' ][0] : '');

	wp_nonce_field( 'gch_header_nonce', 'gch_add_edit_header_noncename' );
	
	$disable_notices = genesis_get_option( 'disable_marketing_notices', 'genesis-custom-header' );
    
	if ( ! $disable_notices ) {
		?>
		<div class="gch-alert gch-alert-warning">
			<?php echo sprintf( __( 'Enjoying %1$sGenesis Custom Headers%2$s but wishing you could add headers to any page on your website? Or perhaps add content to places other than just header areas? Or maybe multiple content blocks on one page? Then you should consider %3$supgrading%4$s to %1$sBlox Lite%2$s. It is completely free and available in the Wordpress.org repository. Happy with this plugin? Then you might as well turn off these notifications in the plugin %5$ssettings%4$s!', 'genesis-custom-headers' ), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/blox-lite/" target="_blank">', '</a>', '<a href="' . admin_url( 'themes.php?page=genesis-custom-header' ) . '">' ); ?>
		</div>
		<?php
	}

?>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><strong><?php _e( 'Enable Header', 'genesis-custom-header' ); ?></strong></th>
				<td>						
					<label for="gch_enable_header"><input type="checkbox" name="gch_enable_header" id="gch_enable_header" value="1" <?php checked( $gch_enable_header ); ?> /> <?php _e( 'Check to enable and view options', 'genesis-custom-header' ); ?></label>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php if ( $gch_force_header_position != '1' ) { ?>
	
	<div class="gch-meta-separator <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled"></div>
	
	<table class="form-table <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled"">
		<tbody>
			<tr>
				<th scope="row"><strong><?php _e( 'Custom Positioning', 'genesis-custom-header' ); ?></strong></th>
				<td>						
					<label for="gch_enable_custom_position"><input type="checkbox" name="gch_enable_custom_position" id="gch_enable_custom_position" class="gch_toggles" value="1" <?php checked( $gch_enable_custom_position ); ?> /> <?php _e( 'Check to enable', 'genesis-custom-header' ); ?></label>
					<div class="gch-description">
						<?php if ( current_user_can( 'manage_options' ) ) echo sprintf( __( 'Custom header positioning will override the global header positioning found on the %1$sSettings Page%2$s.', 'genesis-custom-header' ), '<a href="' . admin_url( 'themes.php?page=genesis-custom-header' ) . '">', '</a>' ); ?>
					</div>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_custom_position != '1' ) echo ('hidden'); ?> gch-custom-position-enabled">
				<th scope="row"><label for="gch_custom_header_position"><?php _e( 'Position', 'genesis-custom-header' ); ?></label> </th>
				<td>
					<select name="gch_custom_header_position" id="gch_custom_header_position">
						<optgroup label="Standard Hooks">
							<option value="genesis_before_header" <?php selected( $gch_custom_header_position, 'genesis_before_header' ); ?>>genesis_before_header</option>
							<option value="genesis_after_header" <?php selected( $gch_custom_header_position, 'genesis_after_header' ); ?>>genesis_after_header</option>
							<option value="genesis_before_content" <?php selected( $gch_custom_header_position, 'genesis_before_content' ); ?>>genesis_before_content</option>
						</optgroup>
						<optgroup label="Advanced Hooks">
							<option value="genesis_before" <?php selected( $gch_custom_header_position, 'genesis_before' ); ?>>genesis_before</option>
							<option value="genesis_header" <?php selected( $gch_custom_header_position, 'genesis_header' ); ?>>genesis_header</option>
							<option value="genesis_before_content_sidebar_wrap" <?php selected( $gch_custom_header_position, 'genesis_before_content_sidebar_wrap' ); ?>>genesis_before_content_sidebar_wrap</option>
							<option value="genesis_before_loop" <?php selected( $gch_custom_header_position, 'genesis_before_loop' ); ?>>genesis_before_loop</option>
							<option value="genesis_before_entry" <?php selected( $gch_custom_header_position, 'genesis_before_entry' ); ?>>genesis_before_entry</option>
							<option value="genesis_entry_header" <?php selected( $gch_custom_header_position, 'genesis_entry_header' ); ?>>genesis_entry_header</option>
							<option value="genesis_entry_content" <?php selected( $gch_custom_header_position, 'genesis_entry_content' ); ?>>genesis_entry_content</option>
						</optgroup>
					</select>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_custom_position != '1' ) echo ('hidden'); ?> gch-custom-position-enabled">
				<th scope="row"><?php _e( 'Priority', 'genesis-custom-header' ); ?></th>
				<td class="gch-radio">
					<label for="gch_custom_position_priority_high">
						<input type="radio" name="gch_custom_position_priority" id="gch_custom_position_priority_high" value="1" <?php checked( $gch_custom_position_priority, '1' ); ?>>
						<?php _e( 'High', 'genesis-custom-header' ); ?>
					</label>
					<br/>
					<label for="gch_custom_position_priority_medium">
						<input type="radio" name="gch_custom_position_priority" id="gch_custom_position_priority_medium" value="10" <?php checked( $gch_custom_position_priority, '10' ); ?>>
						<?php _e( 'Medium', 'genesis-custom-header' ); ?>
					</label>
					<br/>
					<label for="gch_custom_position_priority_low" class="last">
						<input type="radio" name="gch_custom_position_priority" id="gch_custom_position_priority_low" value="100" <?php checked( $gch_custom_position_priority, '100' ); ?>>
						<?php _e( 'Low', 'genesis-custom-header' ); ?>
					</label>
					<div class="gch-description">
						<?php  _e( 'Other plugins and themes can use Genesis Hooks to add content to the page. A High priority tells Wordpress to try and add your custom header before all other content using the same Genesis Hook. Medium and Low priority settings will add the custom header later in the queue. (Developer Reference: High = 1, Medium = 10, Low = 100)', 'genesis-custom-header' ); ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php } ?>
	
	<div class="gch-meta-separator <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled"></div>
	
	<?php if ( $gch_global_enable_header_image == '1' ) { ?>
	
	<table class="form-table <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled">
		<tbody>
			<tr id="gch-header-image">
				<th scope="row"><strong><?php _e( 'Header Image', 'genesis-custom-header' ); ?></strong></th>
				<td>
					<label for="gch_enable_image"><input type="checkbox" name="gch_enable_image" id="gch_enable_image" class="gch_toggles" value="1" <?php checked( $gch_enable_image ); ?> /> <?php _e( 'Check to enable and view options', 'genesis-custom-header' ); ?></label>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_image != '1' ) echo ('hidden'); ?> gch-image-enabled">
				<th scope="row"><strong><?php _e( 'Image Type', 'genesis-custom-header' ); ?></strong></th>
				<td class="gch-radio">
					<div class="gch-show-image">
						<label for="gch_image_type_featured">
							<input type="radio" name="gch_image_type" id="gch_image_type_featured" value="featured" <?php if ( post_type_supports( $type, 'thumbnail' ) == false ){ echo ( 'disabled' ); }?> <?php checked( $gch_image_type, 'featured' ); ?>>
							<?php _e( 'Display Featured Image', 'genesis-custom-header' ); ?>
						</label>
						<?php 
						if ( post_type_supports( $type, 'thumbnail' ) == false ) { 
							echo ('<div class="gch-error">' . __( 'This post type does not support Featured Images, please use a Custom Image', 'genesis-custom-header' ) . '</div>');
						} ?>
						<br/>
						<label for="gch_image_type_custom" class="last">
							<input type="radio" name="gch_image_type" id="gch_image_type_custom" value="custom" <?php checked( $gch_image_type, 'custom' ); ?>>
							<?php _e( 'Display Custom Image', 'genesis-custom-header' ); ?>
						</label>
					</div>
				</td>
			</tr>
			<tr id="gch-image-uploader" class="<?php if ( $gch_image_type != 'custom' || $gch_enable_image != '1' ) echo ('hidden'); ?> gch-image-enabled">
				<th scope="row"><label for="gch_custom_image"><strong><?php _e( 'Custom Image', 'genesis-custom-header' ); ?></strong></label></th>
				<td>
					<input type="text" class="gch_force_hidden" name="gch_custom_image" id="gch_custom_image" value="<?php echo esc_attr( $gch_custom_image ); ?>" />
					<input type="text" class="gch_force_hidden" name="gch_custom_image_alt" id="gch_custom_image_alt" value="<?php echo esc_attr( $gch_custom_image_alt ); ?>" />
					<input type="submit" class="button button-primary" name="gch_upload_button" id="gch_upload_button" value="Select an Image" onclick="gch_imageUpload.uploader(); return false;" /> &nbsp;
					<a class="button" name="gch_remove_button" id="gch_remove_button"><?php _e( 'Remove Image', 'genesis-custom-header' ); ?></a><br/>
			
					<div class="gch-image-preview-wrapper">
						<div class="gch-image-preview-inner">
							<img class="gch-image-default <?php if ( !empty( $gch_custom_image ) ) echo 'hidden'; ?>" src="<?php echo plugin_dir_url( __FILE__ ) ?>../images/default.jpg" />
							<img name="gch_image_preview" id="gch_image_preview" class="<?php if ( empty( $gch_custom_image ) ) echo 'hidden'; ?>" src="<?php echo esc_attr( $gch_custom_image ); ?>" />
						</div>
					</div>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_image != '1' ) echo ('hidden'); ?> gch-image-enabled">
				<th scope="row"><label for="gch_image_caption"><strong><?php _e( 'Image Caption', 'genesis-custom-header' ); ?></strong></label></th>
				<td>
					<textarea class="gch-code-textbox" name="gch_image_caption" id="gch_image_caption" rows="3" ><?php echo esc_attr( $gch_image_caption ); ?></textarea>	
					<div class="gch-description">
						<?php _e( 'Only basic HTML is accepted.', 'genesis-custom-header' ); ?>
					</div>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_image != '1' ) echo ('hidden'); ?> gch-image-enabled">
				<th scope="row"><label for="gch_image_caption"><strong><?php _e( 'Set As Background', 'genesis-custom-header' ); ?></strong></label></th>
				<td>
					<label for="gch_background_image"><input type="checkbox" name="gch_background_image" id="gch_background_image" value="1" <?php checked( $gch_background_image ); ?> /> <?php _e( 'Check to enable', 'genesis-custom-header' ); ?></label>
					<div class="gch-description">
						<?php _e( 'Sets image as background image on gch-header-image-inner. Useful for setting up parallax headers', 'genesis-custom-header' ); ?>
					</div>
				</td>
			</tr>	
		</tbody>
	</table>
		
	<?php } ?>
	
	<?php if ( $gch_global_enable_header_slideshow == '1' ) { ?>
	
	<div class="gch-meta-separator <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled"></div>
	
	<table class="form-table <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled">
		<tbody>
			<tr id="gch-header-slideshow">
				<th scope="row"><strong><?php _e( 'Header Slideshow', 'genesis-custom-header' ); ?></strong></th>
				<td>
					<label for="gch_enable_slideshow"><input type="checkbox" name="gch_enable_slideshow" id="gch_enable_slideshow" class="gch_toggles" value="1" <?php checked( $gch_enable_slideshow ); ?> /> <?php _e( 'Check to enable and view options', 'genesis-custom-header' ); ?></label>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_slideshow != '1' ) echo ('hidden'); ?> gch-slideshow-enabled">
				<?php 
				if ( is_plugin_active( 'soliloquy/soliloquy.php' ) ) { 
					gch_get_soliloquy_sliders( $gch_soliloquy_slider );
				}
				?>
			</tr>
			<tr class="<?php if ( $gch_enable_slideshow != '1' ) echo ('hidden'); ?> gch-slideshow-enabled"> 
				<?php
				if ( is_plugin_active( 'revslider/revslider.php' ) ) { 
					gch_get_revolution_sliders( $gch_revolution_slider );
				} 
				?>
			</tr>
			<tr class="<?php if ( $gch_enable_slideshow != '1' ) echo ('hidden'); ?> gch-slideshow-enabled"> 
				<?php
				if ( is_plugin_active( 'ml-slider/ml-slider.php' ) ) { 
					gch_get_metaslider_sliders( $gch_meta_slider );
				}
				?>
			</tr>
			<tr class="<?php if ( $gch_enable_slideshow != '1' ) echo ('hidden'); ?> gch-slideshow-enabled"> 
				<?php				
				if ( is_plugin_active( 'slider-pro/slider-pro.php' ) ) {
					gch_get_sliderpro_sliders( $gch_sliderpro_slider );
				}
				?>
			</tr>
			<tr class="<?php if ( $gch_enable_slideshow != '1' ) echo ('hidden'); ?> gch-slideshow-enabled">
				<th scope="row"><strong><?php _e( 'Slider Shortcode', 'genesis-custom-header' ); ?></strong></th>	
 				<td>
					<label for="gch_slider_shortcode"><input type="text" name="gch_slider_shortcode" id="gch_slider_shortcode" value="<?php echo esc_attr( $gch_slider_shortcode ) ; ?>" /><?php _e( ' &nbsp;Paste slider shortcode here', 'genesis-custom-header' ); ?></label>
					<?php 
					if ( ! is_plugin_active( 'soliloquy/soliloquy.php' ) && ! is_plugin_active( 'revslider/revslider.php' ) && ! is_plugin_active( 'ml-slider/ml-slider.php' ) && ! is_plugin_active( 'slider-pro/slider-pro.php' ) ) {
						echo '<div class="gch-description">';
							_e( 'This plugin natively supports a growing list of slider plugins including Soliloquy, Revolution Slider, Meta Slider, and Slider Pro. If your favorite slider plugin is not natively supported, but includes a slider shortcode, simply paste it adove.', 'genesis-custom-header' );
						echo '</div>';
					} else {
						echo '<div class="gch-description">';
							_e( 'This serves as an alternate method for adding a slider to the header, but is not necessary if you are using the dropdown(s) above.', 'genesis-custom-header' );
						echo '</div>';
					}
					?>	
				</td>		
			</tr> 
		</tbody>
	</table>
	
	<?php } ?>
	
	<?php if ( $gch_global_enable_header_content == '1' ) { ?>
	
	<div class="gch-meta-separator <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled"></div>

	<table class="form-table <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled">
		<tbody>
			<tr id="gch-header-full-content">
				<th scope="row"><strong><?php _e( 'Header Custom Content', 'genesis-custom-header' ); ?></strong></th>
				<td>
					<label for="gch_enable_custom_content"><input type="checkbox" name="gch_enable_custom_content" id="gch_enable_custom_content" class="gch_toggles" value="1" <?php checked( $gch_enable_custom_content ); ?> /> <?php _e( 'Check to enable', 'genesis-custom-header' ); ?></label>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_custom_content != '1' ) echo ('hidden'); ?> gch-custom-content-enabled">
				<th scope="row"></th>
				<td>
					<div>
						<?php 
						$gch_editor_settings = array( 
							'media_buttons' => true, 
							'quicktags'     => true,
							'teeny'         => false, 
							'textarea_rows' => get_option('default_post_edit_rows', 6)
						); 
						wp_editor( $gch_custom_content, 'gch_custom_content', $gch_editor_settings );
						?>
						<div class="gch-description">
							<?php echo current_user_can( 'manage_options' ) ? sprintf( __( 'The Header Custom Content box will not accept any scripts, iframes, or unsafe HTML. Use the Header Raw Content box for these items. If you do not see the Header Raw Content box, it can be activated from the plugin %1$sSettings Page%2$s.', 'genesis-custom-header' ), '<a href="' . admin_url( 'themes.php?page=genesis-custom-header' ) . '">', '</a>' ) : __( 'The Header Custom Content box will not accept any scripts, iframes, or unsafe HTML.', 'genesis-custom-header' ); ?>
						</div>
					</div>
				<td>
			</tr> 
		</tbody>
	</table>
	
	<?php } ?>
	
	<?php if ( $gch_global_enable_header_raw == 1 ) { ?>
	
	<div class="gch-meta-separator <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled "></div>
	
	<table class="form-table <?php if ( $gch_enable_header != '1' ) echo ('hidden'); ?> gch-enabled">
		<tbody>
			<tr id="gch-header-scripts">
				<th scope="row"><strong><?php _e( 'Header Raw Content', 'genesis-custom-header' ); ?></strong></th>
				<td>
					<label for="gch_enable_header_raw"><input type="checkbox" name="gch_enable_header_raw" id="gch_enable_header_raw" class="gch_toggles" value="1" <?php checked( $gch_enable_header_raw ); ?> /> <?php _e( 'Check to enable', 'genesis-custom-header' ); ?></label>
				</td>
			</tr>
			<tr class="<?php if ( $gch_enable_header_raw != '1' ) echo ('hidden'); ?> gch-header-raw-enabled">
				<th scope="row"></th>
				<td>
					<textarea class="gch-code-textbox" name="gch_header_raw" id="gch_header_raw" rows="6" ><?php echo esc_attr( $gch_header_raw ); ?></textarea>	
					<div class="gch-description">
						<?php _e( 'The Header Raw Content box will accept practically anything except PHP. No data sanitation is preformed, so use with caution.', 'genesis-custom-header' ); ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php } 
	
}


add_action( 'save_post', 'gch_save_meta' );
/**
 * Save all data for staff_member custom post type
 */
function gch_save_meta( $post_id ) {

	// Get our global plugin settings
	$gch_global_enable_header_image 	= genesis_get_option( 'enable_header_image', 'genesis-custom-header' ) ;
	$gch_global_enable_header_slideshow = genesis_get_option( 'enable_header_slideshow', 'genesis-custom-header' ) ;
	$gch_global_enable_header_content 	= genesis_get_option( 'enable_header_content', 'genesis-custom-header' ) ;
	$gch_global_enable_header_raw 		= genesis_get_option( 'enable_header_raw', 'genesis-custom-header' ) ;
	$gch_force_header_position 	  		= genesis_get_option( 'force_header_position', 'genesis-custom-header' ) ;

	if ( !isset( $_POST['gch_add_edit_header_noncename'] ) || !wp_verify_nonce( $_POST['gch_add_edit_header_noncename'], 'gch_header_nonce' ) ) {
			return;
	}
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;
	
	
	// Save custom header enabled meta
	if ( isset( $_POST[ 'gch_enable_header' ] ) ) {
		update_post_meta( $post_id, '_gch_enable_header', $_POST['gch_enable_header'] );
	} else {
		delete_post_meta( $post_id, '_gch_enable_header' );
	}
	
	if ( $gch_force_header_position != '1' ) { 
		// Save custom header positioning enabled meta
		if ( isset( $_POST[ 'gch_enable_custom_position' ] ) ) {
			update_post_meta( $post_id, '_gch_enable_custom_position', $_POST['gch_enable_custom_position'] );
		} else {
			delete_post_meta( $post_id, '_gch_enable_custom_position' );
		}
		if ( isset( $_POST[ 'gch_custom_header_position' ] ) ) {
			update_post_meta( $post_id, '_gch_custom_header_position', $_POST['gch_custom_header_position'] );
		}
		if ( isset( $_POST[ 'gch_custom_position_priority' ] ) ) {
			update_post_meta( $post_id, '_gch_custom_position_priority', $_POST['gch_custom_position_priority'] );
		}
	}
	
	if ( $gch_global_enable_header_image == '1' ) { 
		// Save header image meta
		if ( isset( $_POST[ 'gch_enable_image' ] ) ) {
			update_post_meta( $post_id, '_gch_enable_image', $_POST['gch_enable_image'] );
		} else {
			delete_post_meta( $post_id, '_gch_enable_image' );
		}
		if ( isset( $_POST[ 'gch_image_type' ] ) ) {
			update_post_meta( $post_id, '_gch_image_type', $_POST['gch_image_type'] );
		}
		update_post_meta( $post_id, '_gch_custom_image', $_POST['gch_custom_image'] );
		update_post_meta( $post_id, '_gch_custom_image_alt', $_POST['gch_custom_image_alt'] );
		update_post_meta( $post_id, '_gch_image_caption', $_POST['gch_image_caption'] );
		if ( isset( $_POST[ 'gch_background_image' ] ) ) {
			update_post_meta( $post_id, '_gch_background_image', $_POST['gch_background_image'] );
		} else {
			delete_post_meta( $post_id, '_gch_background_image' );
		}
	}
	
	if ( $gch_global_enable_header_slideshow == '1' ) {
		// Save header slideshow meta
		if ( isset( $_POST[ 'gch_enable_slideshow' ] ) ) {
			update_post_meta( $post_id, '_gch_enable_slideshow', $_POST['gch_enable_slideshow'] );
		} else {
			delete_post_meta( $post_id, '_gch_enable_slideshow' );
		}
		if ( preg_match("/(^[\[]).*([\]]$)/", $_POST['gch_slider_shortcode'] ) == 1 ){   // Ensure that string begins with [ and ends with ]
			update_post_meta( $post_id, '_gch_slider_shortcode', $_POST['gch_slider_shortcode'] );
		} else {
			delete_post_meta( $post_id, '_gch_slider_shortcode' );
		}
		if ( isset( $_POST[ 'gch_soliloquy_slider' ] ) ) {
			update_post_meta( $post_id, '_gch_soliloquy_slider', $_POST['gch_soliloquy_slider'] );
		}	
		if ( isset( $_POST[ 'gch_revolution_slider' ] ) ) {
			update_post_meta( $post_id, '_gch_revolution_slider', $_POST['gch_revolution_slider'] );
		}
		if ( isset( $_POST[ 'gch_meta_slider' ] ) ) {
			update_post_meta( $post_id, '_gch_meta_slider', $_POST['gch_meta_slider'] );
		}
		if ( isset( $_POST[ 'gch_sliderpro_slider' ] ) ) {
			update_post_meta( $post_id, '_gch_sliderpro_slider', $_POST['gch_sliderpro_slider'] );
		}
	}

	if ( $gch_global_enable_header_content == '1' ) {
		// Save header custom content meta
		if ( isset( $_POST[ 'gch_enable_custom_content' ] ) ) {	
			update_post_meta( $post_id, '_gch_enable_custom_content', $_POST['gch_enable_custom_content'] );
		} else {
			delete_post_meta( $post_id, '_gch_enable_custom_content' );
		}
		update_post_meta( $post_id, '_gch_custom_content', wpautop( $_POST['gch_custom_content'] ) );
	}
	
	if ( $gch_global_enable_header_raw == '1' ) {
		// Save header raw meta
		if ( isset( $_POST[ 'gch_enable_header_raw' ] ) ) {	
			update_post_meta( $post_id, '_gch_enable_header_raw', $_POST['gch_enable_header_raw'] );
		} else {
			delete_post_meta( $post_id, '_gch_enable_header_raw' );
		}
		if ( isset( $_POST[ 'gch_header_raw' ] ) ) {	
			update_post_meta( $post_id, '_gch_header_raw', $_POST['gch_header_raw'] );
		}
	}

}

?>