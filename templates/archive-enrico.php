<?php get_header(); ?>


	<section id="primary" class="content-area">
		
			<div class="container" style="width:50%">
				
		<?php if ( have_posts() ){ ?>

			
			<?php
			//Location Collectors Initialized
			$locations =array();
			$latitudes  =array();
			$longitudes = array();
			
			// Start the Loop.
			while ( have_posts() ) : the_post(); ?>

				<div class="col-md-6"  style="padding: 10px;">
				
						<?php the_title( '<h4><a href="' . get_permalink() . '" title="' . 
							the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h4>' );

						echo get_post_meta($post->ID, "enrico-streetName", true)."<br>";
            			echo get_post_meta($post->ID, "enrico-postCode", true)."   ".get_post_meta($post->ID, "enrico-postArea", true)."<br>";
			            echo "Tel: ".get_post_meta($post->ID, "enrico-phoneNumber", true)."<br>";
			            
			            
			            echo "<span>";
			            
			            
			            if(get_post_meta($post->ID, "enrico-homepage", true) !="")
			            	echo "<a href ='" .get_post_meta($post->ID,'enrico-homepage',true)."' target='_blank'>Hemsida</a> | ";
			           	
			           	if(get_post_meta($post->ID, "enrico-eniroId", true) !="")
			            	echo "<a href ='http://kartor.eniro.se/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Vägbeskrivning</a>";
			            
			            if(get_post_meta($post->ID, "enrico-email", true) !="")
			            	echo " | <a href= 'mailto: ".get_post_meta($post->ID, 'enrico-email', true)."' 
			            		target='_top' >email</a>";
			            		
			            echo "</span>";
			            ?>
			
				<br>
				</div>
		
			<?php //Creating input for the Map script
			$InfoBox='<strong>'.get_the_title($post).'</strong><br>'.
        				get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
        				get_post_meta($post->ID, 'enrico-postArea', true).'<br>'.
        				'Lat: '.get_post_meta($post->ID, 'enrico-latitude', true).'<br>'.
        				'  Long: '.get_post_meta($post->ID, 'enrico-longitude', true);
        				
        	
			$locations[]=array($InfoBox,
								get_post_meta($post->ID, 'enrico-latitude', true),
								get_post_meta($post->ID,'enrico-longitude', true),
								get_the_title($post),
				);
			$latitudes[]= get_post_meta($post->ID,'enrico-latitude', true);
	    	$longitudes[]= get_post_meta($post->ID,'enrico-longitude', true);
			// End the loop.
			endwhile;

	

				}
				
			the_posts_pagination( array(
				'prev_text'          => 'Föregående sida',
				'next_text'          => 'Nästa sida',
				'before_page_number' => '<span class="meta-nav screen-reader-text">'. 'Sida'.' </span>',
			) );
			
			?>
			
		</div>	<!-- / div container -->
		
		
	<?php //Calculate Map Center and Zoom
		
		$map_center_latitude=(max($latitudes)+min($latitudes))/2;
		$map_center_longitude=(max($longitudes)+min($longitudes))/2;
		
		
		//var eniro_zoom_factor = 5 //used to approximate correct map zoom factor
	    //var req_zoom_lat= Math.floor((Math.log10(mapDiv.clientHeight/(delta_latitudes*eniro_zoom_factor)))/Math.log10(2));
		if(get_option( 'enrico_map_preferredMap' )=='google'){
				$zoomfactor = 1.5 ;//zoomfactor GoogleMap approx
		}else {
				$zoomfactor = 5 ;//zoomfactor EniroMap approx
		}
		
		$MapDivHeight = 500;
		$MapDivWidth = 500; //Check the css for these values. Height & Width is variable...
		
		if(max($latitudes)-min($latitudes)>0){
		
			$map_zoom_lat = floor(log10($MapDivHeight/((max($latitudes)-min($latitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_lat = 11;}
		
		if(max($longitudes)-min($longitudes)>0){
		
			$map_zoom_long = floor(log10($MapDivHeight/((max($longitudes)-min($longitudes))*$zoomfactor))/log10(2));}
	
			else {$map_zoom_long = 11;}
		
		$map_zoom = min ($map_zoom_lat,$map_zoom_long );

		
?>
	<div id="map-canvas" style=" height: 500px;
  margin: 0px;
  padding: 0px;
  border: 2px solid silver;"></div>

<?php 

if(get_option( 'enrico_map_preferredMap' )=='google'){ //If Google map?>

	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=InitMap"></script>
	
	<script type="text/javascript">
	//Google Map
	function InitGoogleMap() {
		
		var locations = <?php echo json_encode($locations)?>;
		
		var mapDiv = document.getElementById('map-canvas');
		
	    var mapOptions = {
	        center: new google.maps.LatLng(<?php echo $map_center_latitude;?>,<?php echo $map_center_longitude;?>),
	        zoom: <?php echo $map_zoom;?>,
	        mapTypeControl: false,
	      	zoomControl: true,
	      	scaleControl: false,
	      	streetViewControl: true,
	      	rotateControl: false,
	      	fullscreenControl: false,
	        mapTypeId: google.maps.MapTypeId.ROADMAP 
	    };
	
	    var map = new google.maps.Map(mapDiv, mapOptions);
	    
	    var infowindow = new google.maps.InfoWindow()
	    var marker,i;
	    
	    for (i=0;i<locations.length; i++){
	    
	    	marker = new google.maps.Marker({
			    position: new google.maps.LatLng(locations[i][1],locations[i][2]),
			    map: map,
			    title: locations[i][3]
			  });
			
			
			//Closure magic formula for multiple marker events:
			marker.addListener('click', (function(marker,i) {
				return function(){
				infowindow.setContent(locations[i][0]);
	          infowindow.open(map, marker);
				}
	        })(marker,i));  
	    
	    }
	    

	} //end of function InitGoogleMap
	  
	  InitGoogleMap()
	 </script>
			
			                
<?php 						}	//"Endif " GFoogle Map?>


<?php 

if(get_option( 'enrico_map_preferredMap' )=='eniro'){ //If Eniro map is preferred ?>
			
	<!-- javascript to cover both http and https requests -->
	<script type="text/javascript" src="http://kartor.eniro.se/rs/eniro.js?partnerId=<?php echo get_option("enrico_map_partnerId");?>&profile=se"></script>
	<script type="text/javascript" src="https://map.eniro.no/rs/eniro.js?partnerId=<?php echo get_option("enrico_map_partnerId");?>&profile=se&https"></script>

	<script type="text/javascript">
	//Eniro Map setup
	function InitEniroMap() {
	    var mapDiv = document.getElementById('map-canvas');
	    
	    var locations = <?php echo json_encode($locations)?>;
	    
	    
	   	var mapOptions = {
	        center: new eniro.maps.LatLng(<?php echo $map_center_latitude;?>,<?php echo $map_center_longitude;?>),
	        zoom: <?php echo $map_zoom;?>,
	        mapTypeControl: false,
	        mapTypeId: eniro.maps.MapTypeId.MAP,  // [AERIAL/HYBRID/MAP (=default)/NAUTICAL]
	        zoomControl: true,
	    };
	    
	    var map = new eniro.maps.Map(mapDiv,mapOptions);
	    
	    var infoWindow = new eniro.maps.InfoWindow();
	    
	    var marker,i;
	    
	    for (i=0;i<locations.length; i++){
	    	marker = new eniro.maps.Marker({
                      position: new eniro.maps.LatLng(Number(locations[i][1]),Number(locations[i][2])), 
                      map: map, // by providing map the marker is directly added
                      
                  } );
                  
            eniro.maps.event.addListener(marker, 'click', (function(marker,i) {
					return function(){
                  // set the content as either HTML or a DOM node.
                  infoWindow.setContent(locations[i][0]);
                  // open the window on the marker.
                  infoWindow.open(marker);
					}
					})(marker,i));
					
	    	} 
	        
	   myEniroMap.setFocus(true); 
	   
	    }
	
		
	
		InitEniroMap()
	 	</script>
<?php 						
}      //"Endif" EniroMap  ?>  

</div>	
	</section>  <!-- .content-area -->
<?php get_footer(); ?>