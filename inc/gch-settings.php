<?php

defined( 'WPINC' ) or die;

add_action( 'genesis_admin_menu', 'gch_register_admin_settings'  ); 
/**
 * Add all settings for Genesis Custom Header plugin
 */
function gch_register_admin_settings() {

	class GCH_Admin_Settings extends Genesis_Admin_Boxes {
	
		/**
		 * Create an admin menu item and settings
		 */
		function __construct() {
	
			// Specify a unique page ID. 
			$page_id = 'genesis-custom-header';
	
			// Set it as a child of Appearance, and define the menu and page titles
			$menu_ops = array(
				'submenu' => array(
					'parent_slug' => 'themes.php', // http://codex.wordpress.org/Administration_Menus
					'page_title'  => __( 'Genesis Custom Headers', 'genesis-custom-header' ),
					'menu_title'  => __( 'Custom Headers', 'genesis-custom-header' ),
				)
			);
	
			// Set up page options 
			$page_ops = array(
			//	'screen_icon'       => 'options-general',
			//	'save_button_text'  => 'Save Settings',
			//	'reset_button_text' => 'Reset Settings',
			//	'save_notice_text'  => 'Settings saved.',
			//	'reset_notice_text' => 'Settings reset.',
			);		
	
			// Give it a unique settings field 
			$settings_field = 'genesis-custom-header';
	
			// Set the default values
			$default_settings = array(
				'enable_page'			  => 1,
				'enable_post'			  => 0,
				'metabox_title'			  => '',
				'enable_header_image'	  => 1,
				'enable_header_slideshow' => 1,
				'enable_header_content'	  => 1,
				'enable_header_raw' 	  => 0,
				'header_position'		  => 'genesis_after_header',
				'header_priority'		  => '1',
				'force_header_position'	  => 0,
				'disable_header_wrap'	  => 0,
				'enable_header_css'		  => 0,
				'header_css'			  => '',
			);
	
			// Create the Admin Page
			$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );
	
			// Initialize the Sanitization Filter
			add_action( 'genesis_settings_sanitizer_init', array( $this, 'gch_sanitization_filters' ) );
	
		}
	
	
		/** 
		 * Set up Sanitization Filters
		 *
		 * See /lib/classes/sanitization.php for all available filters.
		 */	
		function gch_sanitization_filters() {
			
			// Sanitize the Metabox Title option
			genesis_add_option_filter( 'no_html', $this->settings_field,
				array( 
					'metabox_title',
				) );
			
		}
	
	
		/**
		 * Register metaboxes on settings page
		 *
		 * Note: Cannot change the name "metaboxes", required by Genesis_Admin_Boxes
		 */
		function metaboxes() {
	
			add_meta_box( 'general_settings', __( 'General Settings', 'genesis-custom-header' ), array( $this, 'general_settings' ), $this->pagehook, 'main', 'high' );
			add_meta_box( 'position_settings', __( 'Header Position Settings', 'genesis-custom-header' ), array( $this, 'position_settings' ), $this->pagehook, 'main' );
			add_meta_box( 'style_settings', __( 'Header Style Settings', 'genesis-custom-header' ), array( $this, 'style_settings' ), $this->pagehook, 'main' );

		}
		
		
		/**
		 * Metabox for all backend/admin settings: enabling custom headers, custom metabox title, etc.
		 */
		function general_settings() {
			?>
			
			<h4><?php _e( 'Enable Custom Headers On All...', 'genesis-custom-header' ); ?></h4>
			<p>	
				<?php _e( 'Built-in Post Types:', 'genesis-custom-header' ); ?> &nbsp;
				<label for="<?php echo $this->get_field_id( 'enable_page' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_page' ); ?>" id="<?php echo $this->get_field_id( 'enable_page' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_page' ) ); ?> />
					<?php _e( 'Pages', 'genesis-custom-header' ); ?> &nbsp; 
				</label>
				<label for="<?php echo $this->get_field_id( 'enable_post' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_post' ); ?>" id="<?php echo $this->get_field_id( 'enable_post' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_post' ) ); ?> />
					<?php _e( 'Posts', 'genesis-custom-header' ); ?> &nbsp; 
				</label>
			</p>
			<p>
				<?php _e( 'Custom Post Types:', 'genesis-custom-header' ); ?>&nbsp;
				<?php
				// Get all custom post types in an array by name			
				$custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and' ); 
				
				if ( ! empty($custom_post_types) ) {
					// Display checkbox for all available custom post types
					foreach ( $custom_post_types as $custom_post_type ) {
						// Get the full post object
						$post_name = get_post_type_object( $custom_post_type );
						?>
						<label for="<?php echo $this->get_field_id( 'enable_' . $custom_post_type ); ?>">
							<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_' . $custom_post_type ); ?>" id="<?php echo $this->get_field_id( 'enable_' . $custom_post_type ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_' . $custom_post_type ) ); ?> />
							<?php echo $post_name->labels->name; ?> &nbsp;
						</label>
						<?php
					}
				} else { ?>
					<span class="gch-error"><?php _e( 'No custom post types found', 'genesis-custom-header' ); ?></span>
				<?php } ?>
			</p>
			
			<p><span class="gch-description"><?php _e( 'Only "public" custom post types will be displayed above. Disabling custom headers on a specific post type will not remove any meta data.', 'genesis-custom-header' ); ?></span></p>
				
			<h4><?php _e( 'Customize Header Options', 'genesis-custom-header' ); ?></h4>
			<p>	
				<label for="<?php echo $this->get_field_id( 'enable_header_image' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_header_image' ); ?>" id="<?php echo $this->get_field_id( 'enable_header_image' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_header_image' ) ); ?> />
					<?php _e( 'Check to enable Header Images', 'genesis-custom-header' ); ?> 
				</label>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id( 'enable_header_slideshow' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_header_slideshow' ); ?>" id="<?php echo $this->get_field_id( 'enable_header_slideshow' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_header_slideshow' ) ); ?> />
					<?php _e( 'Check to enable Header Slideshows', 'genesis-custom-header' ); ?> 
				</label>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id( 'enable_header_raw' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_header_content' ); ?>" id="<?php echo $this->get_field_id( 'enable_header_content' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_header_content' ) ); ?> />
					<?php _e( 'Check to enable Header Custom Content', 'genesis-custom-header' ); ?> 
				</label>
			</p>
			<p>	
				<label for="<?php echo $this->get_field_id( 'enable_header_raw' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_header_raw' ); ?>" id="<?php echo $this->get_field_id( 'enable_header_raw' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_header_raw' ) ); ?> />
					<?php _e( 'Check to enable Header Raw Content', 'genesis-custom-header' ); ?> 
				</label>
			</p>
			<p><span class="gch-description"><?php _e( 'Enabling Header Raw Content adds a new field in the Genesis Custom Header metabox for adding raw HTML, CSS, scripts, iframes and really anything else except PHP. Data is not sanitized, so enable with caution.', 'genesis-custom-header' ); ?></span></p>

		
			<h4><label for="<?php echo $this->get_field_id( 'metabox_title' ); ?>"><?php _e( 'Custom Metabox Title', 'genesis-custom-header' ); ?></label></h4>
			<p>
				<input type="text" name="<?php echo $this->get_field_name( 'metabox_title' ); ?>" id="<?php echo $this->get_field_id( 'metabox_title' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'metabox_title' ) ); ?>" class="regular-text"/>
			</p>
			<p><span class="gch-description"><?php echo sprintf( __( 'This is the custom header metabox title that is displayed on Pages and Posts. The default is %1$sGenesis Custom Header%2$s.', 'genesis-custom-header' ), '<strong>', '</strong>' ); ?></span></p>
			
			<?php
		} // end general_settings()
	
	
		/**
		 * Metabox for all Global Positioning Settings
		 */
		function position_settings() {
			?>
			
			<h4><label for="<?php echo $this->get_field_id( 'header_position' ); ?>"><?php _e( 'Global Header Position', 'genesis-custom-header' ); ?></label></h4>
			<p>
				<select name="<?php echo $this->get_field_name( 'header_position' ); ?>" id="<?php echo $this->get_field_id( 'header_position' ); ?>">
					<optgroup label="Standard Hooks">
						<option value="genesis_before_header" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before_header' ); ?>>genesis_before_header</option>
						<option value="genesis_after_header" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_after_header' ); ?>>genesis_after_header</option>
						<option value="genesis_before_content" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before_content' ); ?>>genesis_before_content</option>
					</optgroup>
					<optgroup label="Advanced Hooks">
						<option value="genesis_before" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before' ); ?>>genesis_before</option>
						<option value="genesis_header" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_header' ); ?>>genesis_header</option>
						<option value="genesis_before_content_sidebar_wrap" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before_content_sidebar_wrap' ); ?>>genesis_before_content_sidebar_wrap</option>
						<option value="genesis_before_loop" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before_loop' ); ?>>genesis_before_loop</option>
						<option value="genesis_before_entry" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_before_entry' ); ?>>genesis_before_entry</option>
						<option value="genesis_entry_header" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_entry_header' ); ?>>genesis_entry_header</option>
						<option value="genesis_entry_content" <?php selected( $this->get_field_value( 'header_position' ), 'genesis_entry_content' ); ?>>genesis_entry_content</option>
					</optgroup>
				</select>
			</p>
			<p><span class="gch-description"><?php echo sprintf( __( 'Select the global header position. Please refer to the %1$sGenesis Visual Hook Guide%2$s for hook reference and position information. Some hooks are not available for Genesis child themes that are not using HTML5 markup. Custom header positioning can also be set on each individual post or page.', 'genesis-custom-header' ), '<a href="http://genesistutorials.com/visual-hook-guide/" alt="Genesis Visual Hook Guide" target="_blank">', '</a>' ); ?></span></p>
			
			<h4><label for="<?php echo $this->get_field_id( 'header_priority' ); ?>"><?php _e( 'Global Header Priority', 'genesis-custom-header' ); ?></label></h4>
			<p class="gch-radio">
				<label for="gch_custom_position_priority_high">
					<input type="radio" name="<?php echo $this->get_field_name( 'header_priority' ); ?>" id="gch_custom_position_priority_high" value="1" <?php checked( $this->get_field_value( 'header_priority' ), '1' ); ?>>
					<?php _e( 'High', 'genesis-custom-header' ); ?>
				</label>
				<br/>
				<label for="gch_custom_position_priority_medium">
					<input type="radio" name="<?php echo $this->get_field_name( 'header_priority' ); ?>" id="gch_custom_position_priority_medium" value="10" <?php checked( $this->get_field_value( 'header_priority' ), '10' ); ?>>
					<?php _e( 'Medium', 'genesis-custom-header' ); ?>
				</label>
				<br/>
				<label for="gch_custom_position_priority_low" class="last">
					<input type="radio" name="<?php echo $this->get_field_name( 'header_priority' ); ?>" id="gch_custom_position_priority_low" value="100" <?php checked( $this->get_field_value( 'header_priority' ), '100' ); ?>>
					<?php _e( 'Low', 'genesis-custom-header' ); ?>
				</label>
			</p>
			<p><span class="gch-description"><?php echo sprintf( __( 'Other plugins and themes can use Genesis Hooks to add content to the page. A High priority tells Wordpress to try and add your custom header before all other content using the same Genesis Hook. Medium and Low priority settings will add the custom header later in the queue. (Developer Reference: High = 1, Medium = 10, Low = 100)', 'genesis-custom-header' ), '<a href="http://genesistutorials.com/visual-hook-guide/" alt="Genesis Visual Hook Guide" target="_blank">', '</a>' ); ?></span></p>

			
			<h4><?php _e( 'Force Global Header Positioning', 'genesis-custom-header' ); ?></h4>
			<p>	
				<label for="<?php echo $this->get_field_id( 'force_header_position' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'force_header_position' ); ?>" id="<?php echo $this->get_field_id( 'force_header_position' ); ?>" value="1" <?php checked( $this->get_field_value( 'force_header_position' ) ); ?> />
					<?php _e( 'Check to force all headers into the Global Header Position using the Global Header Priority', 'genesis-custom-header' ); ?>
				</label>
			</p>
			<p><span class="gch-description"><?php _e( 'Unless this setting is enabled, the Global Header Position and Global Header Priority settings simply acts as defaults and can be overridden by the Custom Header Positioning options on each page, post, and custom post type. Activating this setting will hide the Custom Header Positioning options.', 'genesis-custom-header' ); ?></span></p>

		<?php
		} // end position_settings()
	
	
		/**
		 * Metabox for all header styling settings
		 */
		function style_settings() {
			?>
			
			<h4><?php _e( 'Disable Header Wrap', 'genesis-custom-header' ); ?></h4>
			<p>	
				<label for="<?php echo $this->get_field_id( 'disable_header_wrap' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'disable_header_wrap' ); ?>" id="<?php echo $this->get_field_id( 'disable_header_wrap' ); ?>" value="1" <?php checked( $this->get_field_value( 'disable_header_wrap' ) ); ?> />
					<?php echo sprintf( __( 'Check to disable %1$swrap%2$s CSS selector from plugin output', 'genesis-custom-header' ), '<code>', '</code>' ); ?>
				</label>
			</p>
			<p><span class="gch-description"><?php _e( 'With many Genesis child themes, disabling this selector allows for full width headers. Additional CSS styling may be necessary for the desired effect.', 'genesis-custom-header' ); ?></span></p>


			<h4><label for="<?php echo $this->get_field_id( 'header_css' ); ?>"><?php _e( 'Custom Header CSS:', 'genesis-custom-header' ); ?></label></h4>
			<p>
				<label for="<?php echo $this->get_field_id( 'enable_header_css' ); ?>">
					<input type="checkbox" name="<?php echo $this->get_field_name( 'enable_header_css' ); ?>" id="<?php echo $this->get_field_id( 'enable_header_css' ); ?>" value="1" <?php checked( $this->get_field_value( 'enable_header_css' ) ); ?> />
					<?php _e( 'Check to enable custom CSS', 'genesis-custom-header' ); ?>
				</label>
			</p>
			<p><span class="gch-description"><?php _e( 'By default, very minimal CSS is applied by this plugin. However unique selectors have been provided for each element of the plugin\'s frontend markup. Enter your custom CSS below.', 'genesis-custom-header' ); ?></span></p>
			<p>
				<textarea class="gch-code-textbox" name="<?php echo $this->get_field_name( 'header_css' ); ?>" id="<?php echo $this->get_field_id( 'header_css' ); ?>" rows="10" ><?php echo esc_attr( $this->get_field_value( 'header_css' ) ); ?></textarea>	
			</p>


			<div class="gch-html-toggle button"><?php _e( 'Plugin Markup Reference', 'genesis-custom-header' ); ?></div> &nbsp; 
			<div class="gch-html hidden">
				<pre>
				<?php echo htmlspecialchars( stripslashes_deep( '
<!-- Frontend Plugin Markup -->

<div class="gch-header">
   <div class="gch-header-inner wrap"> 
   
      <div class="gch-header-image">
         <div class="gch-header-image-inner">  
            Featured Image or Custom Image 
            <div class="gch-caption">
               <div class="gch-caption-inner">
                  Image Caption
               </div>
            </div>
         </div>
      </div>
      
      <div class="gch-header-slider"> 
         <div class="gch-slider-shortcode">
            Do Slider Shortcode
         </div>
         <div class="gch-soliloguy-slider">
            Display Selected Soliloguy Slider
         </div>	
         <div class="gch-revolution-slider">
            Display Selected Revolution Slider
         </div>	
         <div class="gch-meta-slider">
            Display Selected Meta Slider
         </div>	
         <div class="gch-sliderpro-slider">
            Display Selected Slider PRO Slider
         </div>		
      </div>
      
      <div class="gch-header-content"> 
         Custom Content
      </div>
      
      <div class="gch-header-raw"> 
         Header Raw Content
      </div>
      
   </div>
</div>
				' ) ); ?>
				</pre>
			</div>
			
			<div class="gch-css-toggle button"><?php _e( 'Default Plugin CSS', 'genesis-custom-header' ); ?></div>
			
			<div class="gch-css hidden">
				<pre>
				<?php echo htmlspecialchars( stripslashes_deep( '
/* Default Plugin Styles */

.gch-header-image {
   margin: 0 auto;
   text-align: center;
}

.gch-header-image-inner {
   display: inline-block;
   position: relative;
}

.gch-header-image-inner img {
   vertical-align: top; 
}

.gch-caption {
   bottom: 0px;
   position: absolute;
   text-align: right;
   width: 100%;
}

.gch-caption-inner {
   background: rgba(0, 0, 0, .4);
   color: #fff;
   padding: 10px 20px;
}
				' ) ); ?>
				</pre>
			</div>
	
			<?php	
		} // end style_settings()
	
	
	} // end class GCH_Admin_Settings
	
	global $_gch_admin_settings;
	$_gch_admin_settings = new GCH_Admin_Settings;	 	
	
} // end gch_register_admin_settings()
	

?>