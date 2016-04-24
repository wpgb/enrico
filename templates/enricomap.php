<?php

function enricomap_render($locations, $map_center_latitude, $map_center_longitude, $map_zoom ){

	if(get_option( 'enrico_map_preferredMap' )=='google'){ //If Google map?>

		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=InitGoogleMap"></script>
		
		<script type="text/javascript">
		//Google Map
		function InitGoogleMap() {
			
			var locations = <?php echo json_encode($locations)?>;
			
			var mapDiv = document.getElementById('enricomapdiv');
			
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
	    var mapDiv = document.getElementById('enricomapdiv');
	    
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
}      //"Endif" EniroMap   
}
?> 