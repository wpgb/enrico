<?php

class MapTest{
    private $MapOptions;
    private $MapMarkers;
    
    public function __construct(){
        }
  
    public function setMapOptions($map_center_latitude, $map_center_longitude, $map_zoom ){
        $this->MapOptions = array($map_center_latitude, $map_center_longitude, $map_zoom );
        
        return;
        
    }

    public function getMapOptions(){
        return $this->MapOptions;
    }  
  
    public function setMapMarkers($markerlocations){
         $this->MapMarkers = $markerlocations;
    }
    
    public function getMapMarkers(){
         return $this->MapMarkers;
    }
    
    public function renderMap(){
       
        
        
        ?>
        <p>Hej!!</p>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script type="text/javascript">
        var map;
            function initTestMap() {
                  map = new google.maps.Map(document.getElementById('mapdiv2'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 8
                  });
                };
            initTestMap();
        </script>
        
<?php
    echo '<p>HELLO!!!</p>';
        }
    }      
?>