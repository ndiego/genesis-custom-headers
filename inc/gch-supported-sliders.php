<?php	
/**
 * Sliders that are natively supported
 *
 * - Soliloquy (Including Version 2)
 * - Revolution Slider
 * - Meta Slider (Not Pro Version)
 * - Slider Pro
 *
 * More to come as needed or requested
 */

defined( 'WPINC' ) or die;

/**
 * Grab Soliloquy sliders and display in admin area
 */ 
function gch_get_soliloquy_sliders( $gch_soliloquy_slider ) {
	
	// Grab id and title of all available sliders
	$posts = get_posts( array( 'post_type' => 'soliloquy', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
	
	foreach( $posts as $post ) {		
		$soliliquysliders[] = array(
			'title' => $post->post_title,
			'id' => $post->ID
		);
	}
	
	// Display all available sliders by title in dropdown for selection
	?>
	<th scope="row"><label for="gch_soliloquy_slider"><strong><?php _e( 'Soliloquy Slider', 'genesis-custom-header' ); ?></strong></label></th>
	<td>			
		<select name="gch_soliloquy_slider" id="gch_soliloquy_slider">
			<option value="none" <?php selected( $gch_soliloquy_slider, 'none' ); ?> ><?php _e( 'Display None', 'genesis-custom-header' ); ?></option>
			<?php foreach ( (array) $soliliquysliders as $soliliquyslider ) { ?>
				<option value="<?php echo esc_attr( $soliliquyslider['id'] ); ?>"<?php selected( $gch_soliloquy_slider, $soliliquyslider['id'] ); ?>><?php echo esc_html( $soliliquyslider['title'] ); ?></option>
			<?php } ?>
		</select>

		<?php if ( empty( $soliliquysliders ) ) { ?>
			<div class="gch-error"><?php _e( 'You have not created any Soliloquy sliders yet.', 'genesis-custom-header' ); ?></div>
		<?php } ?>
	</td>
	<?php
}

/**
 * Display Soliloquy slider on the frontend
 */ 
function gch_display_soliloquy_sliders( $gch_soliloquy_slider ) {

	if ( $gch_soliloquy_slider != '' && $gch_soliloquy_slider != 'none' ) {
		echo '<div class="gch-soliloguy-slider">';
			echo do_shortcode( '[soliloquy id="' . $gch_soliloquy_slider . '"]' );
		echo '</div>';
	}

}


	
/**
 * Grab Revolution sliders and display in admin area
 */ 
function gch_get_revolution_sliders( $gch_revolution_slider ) {
	
	// Grab id and title of all available sliders
	$revolutionsliders = array();

	if( class_exists('RevSlider') ){
		$slider = new RevSlider();
		$arrSliders = $slider->getArrSliders();
		foreach($arrSliders as $revSlider) { 
			$revolutionsliders[$revSlider->getAlias()] = $revSlider->getTitle();
		}
	}	
	
	// Display all available sliders by title in dropdown for selection
	?>
	<th scope="row"><label for="gch_revolution_slider"><strong><?php _e( 'Revolution Slider', 'genesis-custom-header' ); ?></strong></label></th>
	<td>
		<select name="gch_revolution_slider" id="gch_revolution_slider">
			<option value="none" <?php selected( $gch_revolution_slider, 'none' ); ?>><?php _e( 'Display None', 'genesis-custom-header' ); ?></option>
			<?php foreach ( $revolutionsliders as $revolutionslider => $title ) { ?>
				<option value="<?php echo esc_attr( $revolutionslider ); ?>"<?php selected( $gch_revolution_slider, $revolutionslider ); ?>><?php echo esc_html( $title ); ?></option>
			<?php } ?>
		</select>

		<?php if ( empty( $revolutionsliders ) ) { ?>
			<div class="gch-error"><?php _e( 'You have not created any Revolution sliders yet.', 'genesis-custom-header' ); ?></div>
		<?php } ?>
	</td>
	<?php
}

/**
 * Display Revolution slider on the frontend
 */ 
function gch_display_revolution_sliders( $gch_revolution_slider ) {

	if ( $gch_revolution_slider != 'none' ) {
		echo '<div class="gch-revolution-slider">';
			putRevSlider ( $gch_revolution_slider );
		echo '</div>';
	}

}



/**
 * Grab Meta sliders and display in admin area
 */ 
function gch_get_metaslider_sliders( $gch_meta_slider ) {

	// Grab id and title of all available sliders
	$posts = get_posts( array( 'post_type' => 'ml-slider', 'posts_per_page' => -1, 'post_status' => 'publish' ) );

	foreach( $posts as $post ) {		
		$metasliders[] = array(
			'title' => $post->post_title,
			'id' => $post->ID
		);
	}
	
	// Display all available sliders by title in dropdown for selection
	?>
	<th scope="row"><label for="gch_meta_slider"><strong><?php _e( 'Meta Slider', 'genesis-custom-header' ); ?></strong></label></th>
	<td>
		<select name="gch_meta_slider" id="gch_meta_slider">
			<option value="none" <?php selected( $gch_meta_slider, 'none' ); ?> ><?php _e( 'Display None', 'genesis-custom-header' ); ?></option>
			<?php foreach ( (array) $metasliders as $metaslider ) { ?>
				<option value="<?php echo esc_attr( $metaslider['id'] ); ?>"<?php selected( $gch_meta_slider, $metaslider['id'] ); ?>><?php echo esc_html( $metaslider['title'] ); ?></option>
			<?php } ?>
		</select>

		<?php if ( empty( $metasliders ) ) { ?>
			<div class="gch-error"><?php _e( 'You have not created any Meta sliders yet.', 'genesis-custom-header' ); ?></div>
		<?php } ?>
	</td>
	<?php
}

/**
 * Display Meta slider on the frontend
 */ 
function gch_display_metaslider_sliders( $gch_meta_slider ) {

	if ( $gch_meta_slider != '' && $gch_meta_slider != 'none' ) {
		echo '<div class="gch-meta-slider">'; 
			echo do_shortcode( '[metaslider id="' . $gch_meta_slider . '"]' );
		echo '</div>';
	}
			
}



/**
 * Grab SliderPro sliders and display in admin area
 */ 
function gch_get_sliderpro_sliders( $gch_sliderpro_slider ) {

	// Grab id and title of all available sliders
	global $wpdb;
	$table_name = $wpdb->prefix . 'sliderpro_sliders';
	$sliderprosliders = $wpdb->get_results("SELECT id, name FROM $table_name");
		
	// Display all available sliders by title in dropdown for selection
	?>
	<th scope="row"><label for="gch_sliderpro_slider"><strong><?php _e( 'Slider PRO Slider', 'genesis-custom-header' ); ?></strong></label></th>
	<td>
		<select name="gch_sliderpro_slider" id="gch_sliderpro_slider">
			<option value="none" <?php selected( $gch_sliderpro_slider, 'none' ); ?> ><?php _e( 'Display None', 'genesis-custom-header' ); ?></option>
			<?php foreach ( (array) $sliderprosliders as $sliderproslider ) { ?>
				<option value="<?php echo esc_attr( $sliderproslider->id ); ?>"<?php selected( $gch_sliderpro_slider, $sliderproslider->id ); ?>><?php echo esc_html( $sliderproslider->name ); ?></option>
			<?php } ?>
		</select>

		<div class="gch-description"><?php echo sprintf( __( 'To ensure your Slider PRO slider displays correctly, select the \'Include Skin\' property on the \'General\' panel of the slide editor. %1$sDetailed explanation%2$s.', 'genesis-custom-header' ), '<a href="http://support.bqworks.com/entries/23455732-The-slider-appears-broken-on-the-homepage-or-when-it-s-inserted-in-PHP-code" alt="Slider PRO Suport" target="_blank">', '</a>' ); ?></div>

		<?php if ( empty( $sliderprosliders ) ) { ?>
			<div class="gch-error"><?php _e( 'You have not created any Slider PRO sliders yet.', 'genesis-custom-header' ); ?></div>
		<?php } ?>
	</td>
	<?php
}

/**
 * Display Slider PRO slider on the frontend
 */ 
function gch_display_sliderpro_sliders( $gch_sliderpro_slider ) {

	if ( $gch_sliderpro_slider != '' && $gch_sliderpro_slider != 'none' ) {
		echo '<div class="gch-sliderpro-slider">';
			echo slider_pro( $gch_sliderpro_slider );
		echo '</div>';
	}
	
}



?>