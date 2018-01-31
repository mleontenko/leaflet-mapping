<!DOCTYPE html>
<html>
<head>
    <title>Leaflet.draw vector editing handlers</title>

    <script src="leaflet/leaflet-src.js"></script>
    <link rel="stylesheet" href="leaflet/leaflet.css"/>

    <script src="leaflet-draw/src/Leaflet.draw.js"></script>
    <script src="leaflet-draw/src/Leaflet.Draw.Event.js"></script>
    <link rel="stylesheet" href="leaflet-draw/src/leaflet.draw.css"/>

    <script src="leaflet-draw/src/Toolbar.js"></script>
    <script src="leaflet-draw/src/Tooltip.js"></script>

    <script src="leaflet-draw/src/ext/GeometryUtil.js"></script>
    <script src="leaflet-draw/src/ext/LatLngUtil.js"></script>
    <script src="leaflet-draw/src/ext/LineUtil.Intersect.js"></script>
    <script src="leaflet-draw/src/ext/Polygon.Intersect.js"></script>
    <script src="leaflet-draw/src/ext/Polyline.Intersect.js"></script>
    <script src="leaflet-draw/src/ext/TouchEvents.js"></script>

    <script src="leaflet-draw/src/draw/DrawToolbar.js"></script>	
    <script src="leaflet-draw/src/draw/handler/Draw.Feature.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.SimpleShape.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.Polyline.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.Marker.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.Circle.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.CircleMarker.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.Polygon.js"></script>
    <script src="leaflet-draw/src/draw/handler/Draw.Rectangle.js"></script>


    <script src="leaflet-draw/src/edit/EditToolbar.js"></script>
    <script src="leaflet-draw/src/edit/handler/EditToolbar.Edit.js"></script>
    <script src="leaflet-draw/src/edit/handler/EditToolbar.Delete.js"></script>

    <script src="leaflet-draw/src/Control.Draw.js"></script>

    <script src="leaflet-draw/src/edit/handler/Edit.Poly.js"></script>
    <script src="leaflet-draw/src/edit/handler/Edit.SimpleShape.js"></script>
    <script src="leaflet-draw/src/edit/handler/Edit.Rectangle.js"></script>
    <script src="leaflet-draw/src/edit/handler/Edit.Marker.js"></script>
    <script src="leaflet-draw/src/edit/handler/Edit.CircleMarker.js"></script>
    <script src="leaflet-draw/src/edit/handler/Edit.Circle.js"></script>
	
	<link rel="stylesheet" href="leaflet-easy-button/src/easy-button.css"/>
	<script src="leaflet-easy-button/src/easy-button.js"></script>
</head>
<body>
<div id="map" style="width: 800px; height: 600px; border: 1px solid #ccc"></div>

<script>
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib }),
            map = new L.Map('map', { center: new L.LatLng(51.505, -0.04), zoom: 13 }),
            drawnItems = L.featureGroup().addTo(map);
    L.control.layers({
        'osm': osm.addTo(map),
        "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
            attribution: 'google'
        })
    }, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);
    map.addControl(new L.Control.Draw({        
        draw: {
            polygon: {
                allowIntersection: false,
                showArea: true
            },
			rectangle: false,
			circle: false,
			circlemarker: false
        }
    }));
	
		
	map.on(L.Draw.Event.DRAWSTART, function (event) {
		drawnItems.clearLayers();
        console.log('drawstart');
    });

    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
		
		feature = layer.feature = layer.feature || {};

		feature.type = feature.type || "Feature";
		var props = feature.properties = feature.properties || {};
		props.desc = null;
		props.image = null;
		
        drawnItems.addLayer(layer);
		
		addPopup(layer);
    });
	
	L.easyButton( '<span title="Save">&#10004;</span>', function(){
	  //alert('you just clicked the save button');
	  var geojson = drawnItems.toGeoJSON();
	  console.log(geojson);
	}).addTo(map);
	
	function addPopup(layer) {
	  var content = document.createElement("textarea");
		content.addEventListener("keyup", function () {
		  layer.feature.properties.desc = content.value;
		});
		layer.on("popupopen", function () {
		  content.value = layer.feature.properties.desc;
		  content.focus();
		});
		layer.bindPopup(content).openPopup();
	}

</script>
</body>
</html>
