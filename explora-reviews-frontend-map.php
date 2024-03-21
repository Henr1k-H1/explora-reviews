<?php
/**
 * This is the short code and front end map
 */

	function explora_map_shortcode() {

		$plugin_dir = plugin_dir_path( __FILE__ );
		$markerFile = plugins_url( 'explora-reviews/explora-reviews-markers-xml.php', dirname(__FILE__) ) ;
		$clusterImg = plugins_url( 'explora-reviews/core/images/m', dirname(__FILE__) ) ;
    $defaultIMG = plugins_url( 'explora-reviews/core/images/exploraluxia_logo.png', dirname(__FILE__) ) ;
		$IMGFolder = plugins_url( 'explora-reviews/core/images/', dirname(__FILE__) ) ;
		$clusterJS = plugins_url( 'explora-reviews/core/markerclusterer.js', dirname(__FILE__) ) ;
		$xml_marker_file = $plugin_dir . 'explora-reviews-markers-xml.php';
		$custmarker = plugins_url( 'explora-reviews/core/icons_red/', dirname(__FILE__) ) ;

	function parseToXML($htmlStr) {
  	$xmlStr=str_replace('<','&lt;',$htmlStr);
  	$xmlStr=str_replace('>','&gt;',$xmlStr);
  	$xmlStr=str_replace('"','&quot;',$xmlStr);
  	$xmlStr=str_replace("'",'&#39;',$xmlStr);
  	$xmlStr=str_replace("&",'&amp;',$xmlStr);
  	$xmlStr=str_replace("http://",'',$xmlStr);
  	$xmlStr=str_replace("&amp;#8217;",'',$xmlStr);
    $xmlStr=str_replace("&amp;#8211;",'',$xmlStr);

	  return $xmlStr;
	}

  $xmlString = '<?php header("Content-type: text/xml"); echo "<markers>";';

  //Get custom posts ids as an array
	$posts = get_posts(array(
	    'post_type'   => 'explora-reviews',
	    'post_status' => 'publish',
	    'posts_per_page' => -1,
	    'fields' => 'ids'
	    )
	);
	$i = 0;

	//loop over each post
	foreach($posts as $p){
		print_r(get_post_meta($p));
	    //get the meta you need form each post
			$pid = $i;
      $post_title = parseToXML(substr(get_the_title($p), 0, 100) . "");
			$pname = parseToXML(substr(get_post_meta($p,"_ptitle",true), 0 , 50) . "");
			$lat = get_post_meta($p,"_plat",true);
	    $lng = get_post_meta($p,"_plng",true);
			$type = get_post_meta($p,"_ptype",true);
      if (empty(get_the_post_thumbnail_url($p))) { $img = $defaultIMG; } else { $img = 'http://' . parseToXML(get_the_post_thumbnail_url($p)); }
			$url = 'http://' . parseToXML(get_post_meta($p,"_purl",true));

			$xmlString .= "echo '<marker id=";
			$xmlString .= '"' . $pid . '" ';
			$xmlString .= 'title="' . $post_title . '" name="' . $pname . '" type="' . $type . '" lat="' . $lat . '" lng="' . $lng . '" url="' . $url . '" img="' . $img . '"/>';
			$xmlString .= "';
    ";
			$i++;
	}

	$xmlString .= 'echo "</markers>"; ?>';

  //write XML formatted PHP file
	$myfile = fopen("$xml_marker_file", "w") or die("Unable to open file!");

	fwrite($myfile, $xmlString);
	fclose($myfile);




/**
 * Add Google Map with markers
*/
?>

<div id="map" style="border: 1px solid #ababab; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); height: 500px;"></div>

	<script>
	     var customIcons = {
									Hotel: {
										icon: '<?php echo $custmarker; ?>lodging-2.png'
									},
									Restaurant: {
										icon: '<?php echo $custmarker; ?>restaurant.png'
									},
									Spa: {
										icon: '<?php echo $custmarker; ?>ying.png'
									},
									Other: {
										icon: '<?php echo $custmarker; ?>zoom.png'
									},
                  Bar: {
										icon: '<?php echo $custmarker; ?>bar_coktail.png'
									}
				    };

			function initMap() {
						var cluster = [];
						var map = new google.maps.Map(document.getElementById("map"), {
							center: new google.maps.LatLng(20.1, 8.0),
							zoom: 2,
							gestureHandling: 'cooperative',
							streetViewControl: false,
              mapTypeControl: false
						});

			var infowindow = new google.maps.InfoWindow();
						downloadUrl('<?php echo $markerFile; ?>', function(data) {
							var xml = data.responseXML;
							var markers = xml.documentElement.getElementsByTagName("marker");
							for (var i = 0; i < markers.length; i++) {
								var name = markers[i].getAttribute("name");
								var type = markers[i].getAttribute("type");
								var point = new google.maps.LatLng(
										parseFloat(markers[i].getAttribute("lat")),
										parseFloat(markers[i].getAttribute("lng")));
								var icon = customIcons[type] || {};
								var marker = new google.maps.Marker({
									map: map,
									position: point,
									icon: icon.icon,
								});
								google.maps.event.addListener(marker, 'click', (function(marker, i) {
															return function() {
																	infowindow.setContent(
																		      '<a href="' +
																					markers[i].getAttribute("url") +
																					'"><div style="text-decoration:none; max-width:200px;"><h4 style="color:#000; font-weight:900; font-size:14px;">' +
																		      markers[i].getAttribute("title") +
																					'</h4><p style="color:#000; font-weight:500";>' +
																					markers[i].getAttribute("type") +
																					': '+
                                          markers[i].getAttribute("name") +
																					'</p><p style="font-style: italic; color:#27aae1;">Click here to read the review!</p><div style="background: url(' +
																					markers[i].getAttribute("img") +
																					'); height:100px; width:100%; min-width:150px; background-repeat:no-repeat; background-position: center; background-size: cover;">' +
																		      '</div></div></a>'
																				);
																	infowindow.open(map, marker);
															}
													})(marker, i));
								cluster.push(marker);
							}

							var options = {
										imagePath: '<?php echo $clusterImg; ?>'
								};

							var mc = new MarkerClusterer(map,cluster,options);
						});
					}

					function downloadUrl(url, callback) {
						var request = window.ActiveXObject ?
								new ActiveXObject('Microsoft.XMLHTTP') :
								new XMLHttpRequest;

						request.onreadystatechange = function() {
							if (request.readyState == 4) {
								request.onreadystatechange = doNothing;
								callback(request, request.status);
							}
						};

						request.open('GET', url, true);
						request.send(null);
					}

					function doNothing() {}
	</script>
	<script src="<?php echo $clusterJS; ?>"/></script>

<?php
	}

	add_shortcode('explora_map', 'explora_map_shortcode');
