<?php

/**
 * This is the options page
 */

add_action( 'admin_menu', 'explora_reviews_add_admin_menu' );
add_action( 'admin_init', 'explora_reviews_settings_init' );


function explora_reviews_add_admin_menu(  ) {

	add_submenu_page( 'edit.php?post_type=explora-reviews',
										'Explora Reviews Settings',
										'Settings',
										'manage_options',
										'settings',
										'explora_reviews_options_page' );

}


function explora_reviews_settings_init(  ) {

	register_setting( 'pluginPage', 'explora_reviews_settings' );

	add_settings_section(
		'explora_reviews_pluginPage_section',
		__( 'Google Maps API', 'explora_reviews' ),
		'explora_reviews_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'explora_reviews_google_api_key',
		__( 'Google Maps API key', 'explora_reviews' ),
		'explora_reviews_google_api_key_render',
		'pluginPage',
		'explora_reviews_pluginPage_section'
	);


}


function explora_reviews_google_api_key_render(  ) {

	$google_api_key_array = get_option( 'explora_reviews_settings' );

	$google_api_key = $google_api_key_array == false ? '' : $google_api_key_array['explora_reviews_google_api_key'];

	?>
	<input type='text' name='explora_reviews_settings[explora_reviews_google_api_key]' size='65' placeholder='Obtain and enter api key...' value='<?php echo $google_api_key; ?>'>
	<?php

}


function explora_reviews_settings_section_callback(  ) {

	echo __( 'Please obtain a Google Maps API key from Google and enter it below. Without the API key, the plugin will not work.', 'explora_reviews' );

}


function explora_reviews_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>Explora Reviews Settings</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>
