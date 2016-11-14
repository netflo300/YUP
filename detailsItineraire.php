<?php 
session_start();
require_once ("class/db.class.php");
require_once ("lib/fonctions.php");
if (!auth()) {
  Header("Location:login.php");
  die;
}
$db = new Db();
?>
<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY; ?>"></script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: { lat: 48.583333, lng: 7.75},
          zoom: 14
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        var markerT1 = new google.maps.Marker({
        	position: { lat: 48.5852655, lng: 7.735451900000044},
        	 map: map, title: "arr�t de tram Gare centrale",
           icon: "img/tram.png"
        	 	 });
    var markerT2 = new google.maps.Marker({
    	position: { lat: 48.5853285, lng: 7.742377400000009},
    	 map: map, title: "arr�t de tram Ancienne Synagogue Les Halles",
    		 icon: "img/tram.png"
    	 	 });
    var markerT3 = new google.maps.Marker({
    	position: { lat: 48.5749381, lng: 7.753464300000019},
        	 map: map, title: "arr�t de tram Etoile Bourse",
    		 icon: "img/tram.png"
    	 	 });
    var markerT4 = new google.maps.Marker({
    	position: { lat: 48.59928499999999, lng: 7.7665508000000045},
    	 map: map, title: "arr�t de tram Parlement europ�en",
    	 icon: "img/tram.png"
    	 	 });
    var markerT5 = new google.maps.Marker({
    	position: { lat: 48.5759, lng: 7.774639999999977},
    	 map: map, title: "Parc de la Citadelle (600 jeunes en v�lo)",
    	 icon: "img/tram.png"
    	 	 });
    var markerT6 = new google.maps.Marker({
    	position: { lat: 48.57270459999999, lng: 7.795306699999969},
    	 map: map, title: "arr�ts de bus Jardin des Deux Rives",
    	 icon: "img/tram.png"
    	 	 });
		
			<?php 
				
			$db->query("SELECT * FROM etapes, checkpoint where etapes.id_itineraire = ".$_GET['id_itineraire']." and etapes.id_checkpoint = checkpoint.id order by position ;");
				foreach ($db->fetch_array() as $k => $v) {
					echo 'var marker'.$k.' = new google.maps.Marker({position: { lat: '.$v['positionY'].', lng: '.$v['positionX'].'}, map: map, title: "hello"});'."\n";
					echo 'google.maps.event.addListener(marker'.$k.', \'click\', function() { new google.maps.InfoWindow({content: "Etape '.$v['position'].'"}).open(map,marker'.$k.');});'."\n";
					echo 'var point'.$k.' =	new google.maps.LatLng('.$v['positionY'].', '.$v['positionX'].');';
				}
				?>
				var flightPlanCoordinates = [point0, point1, point2, point3, point4, point5, point6];
				var flightPath = new google.maps.Polyline({
				    path: flightPlanCoordinates,
				    geodesic: true,
				    strokeColor: '#FF0000',
				    strokeOpacity: 1.0,
				    strokeWeight: 2
				  });
				flightPath.setMap(map);
				
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
<div id="map-canvas"></div>
  </body>
</html>