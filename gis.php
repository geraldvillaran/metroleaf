<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href=""/>
		<title>E-Automate RPTA GIS</title>
		<meta charset="utf-8" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
		  <style>
			#map {
			width: 100%;
			height: 100vh;
			}

			.sidebar {
			height: 100vh;
			overflow-y: auto;
			}

			.table-container {
			height: 50%;
			overflow-y: auto;
			margin-bottom: 10px;
			}

			.checkboxes {
			margin-top: 10px;
			}
		</style>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<?php include 'partials/header.php'?>
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<?php include 'partials/sidebar.php' ?>
					
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<div class="row" id="map">
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<?php include 'partials/footer.php' ?>
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<?php include 'partials/drawers.php' ?>
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		<?php include 'partials/modals.php' ?>
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
		<script>
			// Define a color palette
			const colorPalette = ['#22A699', '#F2BE22', '#F29727', '#F24C3D', '#42FFC9', '#4292FF'];

			// Initialize the map and set its view to Santa Cruz, Laguna
			var map = L.map('map').setView([14.282332, 121.423933], 13);

			// // Add a tile layer from OpenStreetMap
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			
			// Add a tile layer from Google
			// L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
			// maxZoom: 20,
			// subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
			// }).addTo(map);

			function styleFeature(feature) {
				const name = feature.properties.name;
				const index = name ? [...name].reduce((sum, char) => sum + char.charCodeAt(0), 0) % colorPalette.length : 0;
				const fillColor = colorPalette[index];

				return {
					fillColor: fillColor,
					fillOpacity: 0.3,
					weight: 1,
					color: 'white',
					name: name // Add the name attribute as a property for the shape
				};
			}


			function onEachFeature(feature, layer) {
		// Generate mock data for real estate properties
		const propertyData = {
			name: feature.properties.name || 'No name available',
			owner: 'Benito Ong',
			address: '123 Main St',
			price: 'Php 500,000',
			bedrooms: 3,
			bathrooms: 2,
			area: '2,000 sq ft',
			description: 'Spacious and modern property located in a prime location.',
		};

		// Generate the popup content using the mock data
		const popupContent = `
			<h3>${propertyData.name}</h3>
			<p><strong>Owner:</strong> ${propertyData.owner}</p>
			<p><strong>Address:</strong> ${propertyData.address}</p>
			<p><strong>Assessment Value:</strong> ${propertyData.price}</p>
			<p><strong>Area:</strong> ${propertyData.area}</p>
			<p><strong>Description:</strong> ${propertyData.description}</p>
		`;

		// Bind the popup with the generated content to the layer
		layer.bindPopup(popupContent);

		// Add click event listener
		layer.on('click', function (e) {
			// Pan and zoom to the clicked polygon
			map.fitBounds(e.target.getBounds(), {
			padding: [50, 50]
			});

			// Open the popup with the property information
			e.target.openPopup();
		});
		}

			// Function to load the GeoJSON data and add it to the map
			function loadGeoJSONFiles() {
			// Load other GeoJSON files
			// Load lines and points
			// const otherFiles = ['santacruz_lines.geojson', 'santacruz_point.geojson'];

			// otherFiles.forEach(file => {
			// 	fetch(file)
			// 	.then(response => response.json())
			// 	.then(data => {
			// 		L.geoJSON(data, {
			// 		style: styleFeature,
			// 		onEachFeature: onEachFeature
			// 		}).addTo(map);
			// 	})
			// 	.catch(error => {
			// 		console.error('Error loading GeoJSON data:', error);
			// 	});
			// });

			// Load the santacruz_multipolygon.geojson file separately
			const multipolygonFile = 'data/santacruz_multipolygon.geojson';

			fetch(multipolygonFile)
				.then(response => response.json())
				.then(data => {
				// const polygonTable = document.getElementById('polygon-table');

				// data.features.forEach((feature, index) => {
				// 	const row = document.createElement('tr');
				// 	const nameCell = document.createElement('td');

				// 	nameCell.textContent = feature.properties.name || `Polygon ${index + 1}`;
				// 	row.appendChild(nameCell);
				// 	polygonTable.appendChild(row);

				// 	row.addEventListener('click', () => {
				// 	const polygon = polygonLayer.getLayers()[index];
				// 	map.fitBounds(polygon.getBounds(), {
				// 		padding: [50, 50]
				// 	});
				// 	polygon.openPopup();
				// 	});
				// });

				const polygonLayer = L.geoJSON(data, {
					style: styleFeature,
					onEachFeature: onEachFeature
				}).addTo(map);

				// Event listeners for checkbox changes
				// const residentialCheckbox = document.getElementById('residentialCheckbox');
				// const commercialCheckbox = document.getElementById('commercialCheckbox');
				// const agriCheckbox = document.getElementById('agriCheckbox');

				// residentialCheckbox.addEventListener('change', function () {
				// 	const residentialShapes = ['Barangay I', 'Barangay IV']; // Replace with your residential shape names
				// 	if (this.checked) {
				// 	residentialShapes.forEach(shape => {
				// 		const residentialPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		residentialPolygons.forEach(polygon => map.addLayer(polygon));
				// 	});
				// 	} else {
				// 	residentialShapes.forEach(shape => {
				// 		const residentialPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		residentialPolygons.forEach(polygon => map.removeLayer(polygon));
				// 	});
				// 	}
				// });

				// commercialCheckbox.addEventListener('change', function () {
				// 	const commercialShapes = ['Barangay V', 'Barangay III', 'Barangay II']; // Replace with your commercial shape names
				// 	if (this.checked) {
				// 	commercialShapes.forEach(shape => {
				// 		const commercialPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		commercialPolygons.forEach(polygon => map.addLayer(polygon));
				// 	});
				// 	} else {
				// 	commercialShapes.forEach(shape => {
				// 		const commercialPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		commercialPolygons.forEach(polygon => map.removeLayer(polygon));
				// 	});
				// 	}
				// });

				// agriCheckbox.addEventListener('change', function () {
				// 	const agriShapes = ['Bagumbayan']; // Replace with your agricultural shape names
				// 	if (this.checked) {
				// 	agriShapes.forEach(shape => {
				// 		const agriPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		agriPolygons.forEach(polygon => map.addLayer(polygon));
				// 	});
				// 	} else {
				// 	agriShapes.forEach(shape => {
				// 		const agriPolygons = polygonLayer.getLayers().filter(layer => layer.feature.properties.name === shape);
				// 		agriPolygons.forEach(polygon => map.removeLayer(polygon));
				// 	});
				// 	}
				// });
				})
				.catch(error => {
				console.error('Error loading GeoJSON data:', error);
				});
			}

			loadGeoJSONFiles();
		</script>
	</body>
	<!--end::Body-->
</html>