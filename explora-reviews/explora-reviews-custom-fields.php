<?php
/**
 * Add ccustom field box wrapper
 * @param post $post The post object
 */

function explora_reviews_add_meta_boxes( $post ){
	add_meta_box( 'explora_reviews_meta_box', __( 'Set Marker', 'explora_reviews_plugin' ), 'explora_reviews_build_meta_box', 'explora-reviews', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'explora_reviews_add_meta_boxes' );




/**
 * Add custom fields to custom field box
 * @param post $post The post object
 */

 /**
  * Check if form request from WP and populate fields if data exists
  */

function explora_reviews_build_meta_box( $post ){
	wp_nonce_field( basename( __FILE__ ), 'explora_reviews_meta_box_nonce' );

	$current_title = get_post_meta( $post->ID, '_ptitle', true );
	$current_lng = get_post_meta( $post->ID, '_plng', true );
	$current_lat = get_post_meta( $post->ID, '_plat', true );
	$current_latlng = $current_lat . '' . $current_lng;
	$current_type = get_post_meta( $post->ID, '_ptype', true );

/*
 * Add custom fields to custom field box
*/

   ?>
<? echo get_permalink( $post_id ); ?>
<div class='inside'>
    <div id="map" style="height: 250px"></div>
    <div id="infowindow-content">
        <span id="place-name"  class="title"></span><br>
        <span id="place-address"></span>
    </div>
<br>
		<div style="background-color: #dff0d8; padding: 1px 5px 10px 10px; border: 1px solid #b2dba1;">
		    <p>
					<h3><?php _e( 'Search for a place', 'explora_reviews_plugin' ); ?></h3>
		      <input id="pac-input" type="text" size="65">
			  </p>
		</div>
		<br>

		<h3><?php _e( 'The information below is for the marker on the map', 'explora_reviews_plugin' ); ?></h3>
		<p>
      <label for="ptitle" class="control-label">Place Name:</label>
      <br>
			<input type="text" name="ptitle" id="ptitle" size="50" value="<?php echo $current_title; ?>" />
		</p>

		<p>
			<label for="ptype" class="control-label">Place Type:</label>
			<br>
			<input type="radio" name="ptype" value="Hotel" <?php echo ($current_type=='Hotel')?'checked':'' ?>>Hotel<br>
      <input type="radio" name="ptype" value="Restaurant" <?php echo ($current_type=='Restaurant')?'checked':'' ?>>Restaurant<br>
      <input type="radio" name="ptype" value="Spa" <?php echo ($current_type=='Spa')?'checked':'' ?>>Spa<br>
      <input type="radio" name="ptype" value="Bar" <?php echo ($current_type=='Bar')?'checked':'' ?>>Bar<br>
			<input type="radio" name="ptype" value="Other" <?php echo ($current_type=='Other')?'checked':'' ?>>Other
		</p>

		<p>
      <label for="plat" class="control-label">LAT:</label>
      <br>
			<input type="text" name="plat" id="plat" readonly value="<?php echo $current_lat; ?>" />
		</p>
		<p>
      <label for="plng" class="control-label">LNG:</label>
      <br>
			<input type="text" name="plng" id="plng" readonly value="<?php echo $current_lng; ?>" />
		</p>
		<br>
		<h3><?php _e( 'Remember to set a Featured Image and a region!', 'explora_reviews_plugin' ); ?></h3>
</div><!--inside-->

<!--
/**
 * Add Google Map and Autocomplete form with Google Places data
*/
-->

  <script>
  function initMap() {

		<?php if (!empty($current_latlng)) { echo 'var myLatLng = { lat: ' . $current_lat . ', lng: ' . $current_lng . '};';
					//Shows a marker and info window when editing a place
		      echo "var map = new google.maps.Map(document.getElementById('map'), {";
		          echo 'zoom: 12,';
		          echo 'center: myLatLng,';
							echo "gestureHandling: 'none',";
							echo 'mapTypeControl: false,';
							echo 'streetViewControl: false,';
							echo 'clickableIcons: false,';
							echo 'scrollwheel: false';
		      echo '});';

					echo "var contentString = '<div>" . $current_title . "</div>';";

					echo 'var infowindow = new google.maps.InfoWindow({';
          		echo 'content: contentString,';
          		echo 'maxWidth: 200';
        	echo '});';

		      echo 'var marker = new google.maps.Marker({';
		          echo 'position: myLatLng,';
		          echo 'map: map,';
		          echo "title: 'Hello World!'";
		      echo '});';

					echo 'infowindow.open(map,marker);';

					//Shows no marker or info window when creating a new review
				} else {
					echo "var map = new google.maps.Map(document.getElementById('map'), {";
							echo 'center: new google.maps.LatLng(42.1, -20.20),';
							echo 'zoom: 2,';
							echo "gestureHandling: 'none',";
							echo 'mapTypeControl: false,';
							echo 'streetViewControl: false,';
							echo 'clickableIcons: false,';
							echo 'scrollwheel: false';
					echo '});';
				}
?>


   var input = document.getElementById('pac-input');
   var autocomplete = new google.maps.places.Autocomplete(input);

   autocomplete.bindTo('bounds', map);

   var infowindow = new google.maps.InfoWindow();
   var infowindowContent = document.getElementById('infowindow-content');
   infowindow.setContent(infowindowContent);
   var marker = new google.maps.Marker({
     map: map,
     anchorPoint: new google.maps.Point(0, -29)
   });

   autocomplete.addListener('place_changed', function() {
     infowindow.close();
     marker.setVisible(false);
     var place = autocomplete.getPlace();
     if (!place.geometry) {
       // User entered the name of a Place that was not suggested and
       // pressed the Enter key, or the Place Details request failed.
       window.alert("No details available for input: '" + place.name + "'");
       return;
     }

     var place = autocomplete.getPlace();
     var lat = place.geometry.location.lat(),
         lng = place.geometry.location.lng();

     document.getElementById('plat').value = lat;
     document.getElementById('plng').value = lng;
     document.getElementById('ptitle').value = place.name;

     // If the place has a geometry, then present it on a map.
     if (place.geometry.viewport) {
       map.fitBounds(place.geometry.viewport);
     } else {
       map.setCenter(place.geometry.location);
       map.setZoom(17);  // Why 17? Because it looks good.
     }
     marker.setPosition(place.geometry.location);
     marker.setVisible(true);

     var address = '';
     if (place.address_components) {
       address = [
         (place.address_components[0] && place.address_components[0].short_name || ''),
         (place.address_components[1] && place.address_components[1].short_name || ''),
         (place.address_components[2] && place.address_components[2].short_name || '')
       ].join(' ');
     }

     infowindowContent.children['place-name'].textContent = place.name;
     infowindowContent.children['place-address'].textContent = address;
     infowindow.open(map, marker);
   });
 } //<-- end of initMap -->

</script>

	<?php
} //<-- end of explora_reviews_build_meta_box -->


/**
 * Store custom field meta box data
 * @param int $post_id The post ID.
 */

function explora_reviews_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['explora_reviews_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['explora_reviews_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
	}

	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}

  // Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}

	// store custom fields values in db
	if ( isset( $_REQUEST['ptitle'] ) ) {
		update_post_meta( $post_id, '_ptitle', sanitize_text_field( $_POST['ptitle'] ) );
	}

	if ( isset( $_REQUEST['plat'] ) ) {
		update_post_meta( $post_id, '_plat', $_POST['plat'] );
	}

	if ( isset( $_REQUEST['plng'] ) ) {
		update_post_meta( $post_id, '_plng', $_POST['plng'] );
	}

  if ( isset( $_REQUEST['ptype'] ) ) {
		update_post_meta( $post_id, '_ptype', $_POST['ptype'] ); }
  else {
    update_post_meta( $post_id, '_ptype', 'Other' );
  }

	update_post_meta( $post_id, '_purl', get_permalink( $post_id ) );

}
add_action( 'save_post', 'explora_reviews_save_meta_box_data' );
