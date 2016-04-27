// JavaScript File

function initGoogleMap() {
    var mapOptions = {
		        center: new google.maps.LatLng(LatLong[0],LatLong[1]),
		        zoom: zoom,
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
     
  
}



function initEniroMap() {
	    
	   	var mapOptions = {
	        center: new eniro.maps.LatLng(LatLong[0],LatLong[1]),
	        zoom: zoom,
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