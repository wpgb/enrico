<?php get_header(); ?>


	<div id="primary" class="content-area">
		
		<div class "row>
			<div  style="width:50%; float:left;  margin: 0px;
  								padding: 0px;
  								border: 2px solid silver;">
			
			<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>
			
			<h2><?php echo get_the_title($post);?></h2>
			
			<p><?php echo the_excerpt($post);?></p>
			

                        <p>Adress: <br>
                        <?php echo get_post_meta($post->ID, 'enrico-streetName', true);?><br>
			
				
		                <?php if (get_post_meta($post->ID, 'enrico-postBox', true)){?>
					                Postbox:  <?php echo get_post_meta($post->ID, 'enrico-postBox', true);?><br>
				                  
				         			<?php  } ?>     
	
		                <?php echo get_post_meta($post->ID, 'enrico-postCode', true);?><br>
		                    </tr>
                 
                		<strong><?php echo get_post_meta($post->ID, 'enrico-postArea', true);?></strong></p>
                 
                    
               			<p>Telefon:  <?php echo get_post_meta($post->ID, 'enrico-phoneNumber', true);?><br>
                        
						Länkar:<br>
						<span>
			            
			           <?php 
			            if(get_post_meta($post->ID, "enrico-homepage", true) !="")
			            	echo "<a href ='" .get_post_meta($post->ID,'enrico-homepage',true)."' target='_blank'>Hemsida</a> | ";
			            	
			            if(get_post_meta($post->ID, "enrico-facebook", true) !="")
			            	echo "<a href ='" .get_post_meta($post->ID,'enrico-facebook',true)."' target='_blank'>Facebook</a> | ";
			           	
			           	if(get_post_meta($post->ID, "enrico-eniroId", true) !="")
			            	echo "<a href ='http://kartor.eniro.se/?index=yp&id=" .get_post_meta($post->ID,'enrico-eniroId',true)."' target='_blank'>Vägbeskrivning</a>";
			            
			            if(get_post_meta($post->ID, "enrico-email", true) !="")
			            	echo " | <a href= 'mailto: ".get_post_meta($post->ID, 'enrico-email', true)."' 
			            		target='_top' >email</a>";
			            ?>		
			            </span></p>
			
			
  </div><!-- col-md-8 -->
		
		
        <?php
        //Input for the map:
        	$InfoBox='<strong>'.get_the_title($post).'</strong><br>'.
        				get_post_meta($post->ID, 'enrico-streetName', true).'<br>'.
        				get_post_meta($post->ID, 'enrico-postArea', true).'<br>';
        				
        	$lat = get_post_meta($post->ID, 'enrico-latitude', true);
        	
        	$long = get_post_meta($post->ID,'enrico-longitude', true);
        	
       

		// End the loop.
		endwhile;
		?>
		<div id="map-canvas-single" style=" height: 200px; width:50%; float:left;
  margin: 0px;
  padding: 0px;
  border: 2px solid silver;"></div>
  
  </div>
<?php 	
if(get_option( 'enrico_map_preferredMap' )=='google'){ //If Google map?>
			
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=InitMap"></script>
		
	<script type="text/javascript">

	//Google Map Initialisation
	function InitGoogleMap() {
	    
	    var mapOptions = {
	        center: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $long;?>),
	        zoom: 12,
	        mapTypeControl: false,
	      	zoomControl: true,
	      	scaleControl: false,
	      	streetViewControl: true,
	      	rotateControl: false,
	      	fullscreenControl: false,
	        mapTypeId: google.maps.MapTypeId.ROADMAP 
	    };
	
	    var mapDiv = document.getElementById('map-canvas-single');
	    
	    var map = new google.maps.Map(mapDiv, mapOptions);
	
		var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $long;?>),
	    map: map,
	    title: 'Klicka för info'
	  });
	
		var infoWindow = new google.maps.InfoWindow({
	          content: "<?php echo $InfoBox;?>"
	        });
	   
	    marker.addListener('click', function() {
	          infoWindow.open(map, marker);
	        });
	  }
	  
	  InitGoogleMap()
	 </script>
				
				                
	<?php 						}	//"Endif " Google Map
	
	
if(get_option('enrico_map_preferredMap' )=='eniro'){ //If Eniro map  ?>

	<!-- cover both http and https requests -->
	<script type="text/javascript" src="http://kartor.eniro.se/rs/eniro.js?partnerId=<?php echo get_option("enrico_map_partnerId");?>&profile=se"></script>
	<script type="text/javascript" src="https://map.eniro.no/rs/eniro.js?partnerId=<?php echo get_option("enrico_map_partnerId");?>&profile=se&https"></script>
   
	<script type="text/javascript">
	//Eniro Map
	function InitEniroMap() {
	    
	   var mapDiv = document.getElementById('map-canvas-single');
	    
	    var mapOptions = {
		        center: new eniro.maps.LatLng(<?php echo $lat;?>,<?php echo $long;?>),
		        zoom: 11,
		        mapTypeControl: false,
		        mapTypeId: eniro.maps.MapTypeId.MAP,  // [AERIAL/HYBRID/MAP (=default)/NAUTICAL]
		        zoomControl: true,
		    };
		    
		    var map = new eniro.maps.Map(mapDiv,mapOptions);
		    
		    var infoWindow = new eniro.maps.InfoWindow();
	
	    
	   	
	    var marker = new eniro.maps.Marker({
	                      position: new eniro.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>), 
	                      map: map, // by providing map the marker is directly added
	                      
	                  } 
	              );
		
		eniro.maps.event.addListener(marker, 'click', function () {
	
	                  // set the content as either HTML or a DOM node.
	                  infoWindow.setContent('<?php echo $InfoBox;?>');
	                  // open the window on the marker.
	                  infoWindow.open(marker);
						});
		
	    myEniroMap.setFocus(true);   
	  	}
	
		InitEniroMap()
	 	</script>
	<?php 						
}      //"Endif" EniroMap  ?>     

 
</div><!-- .content-area -->


<?php get_footer(); ?>