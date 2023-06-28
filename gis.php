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
				<?php //include 'partials/header.php'?>
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
						<?php //include 'partials/footer.php' ?>
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

			map.zoomControl.remove();

			L.control.zoom({
				position: 'bottomright'
			}).addTo(map);

			// Add a tile layer from OpenStreetMap
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				maxNativeZoom: 19,
				maxZoom: 25
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

			const attributes = [];

			// start::onEachFeature
			function onEachFeature(feature, layer) {
				// Generate mock data for real estate properties
				const propertyData = {
					name: feature.properties.name || 'Unregistered Property',
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

				const checkAttributes = ['building', 'amenity'];

				checkAttributes.forEach(attribute => {
					const value = feature.properties[attribute];
					if (value && !attributes.includes(value)) {
						attributes.push(value);
					}
				});
			}
			// end::onEachFeature

			// Function to load the GeoJSON data and add it to the map
			function loadGeoJSONFiles() {
				// Array of GeoJSON file names in the 'data' directory
				const fileNames = [
					'laguna_boundary.geojson',
					'buildings.geojson',
					// Add more file names here
				];

				// Process each GeoJSON file
				fileNames.forEach(fileName => {
					const filePath = `data/${fileName}`;

					fetch(filePath)
						.then(response => response.json())
						.then(data => {
							const polygonLayer = L.geoJSON(data, {
								style: styleFeature,
								onEachFeature: onEachFeature
							}).addTo(map);

							// Populate the attrib_table with the attributes
							attributes.forEach(attribute => {
								const row = document.createElement('tr');

								const checkboxCell = document.createElement('td');
								checkboxCell.className = 'px-3';
								checkboxCell.style.width = '10px';
								const checkboxDiv = document.createElement('div');
								checkboxDiv.className = 'form-check form-check-sm';
								const checkboxInput = document.createElement('input');
								checkboxInput.type = 'checkbox';
								checkboxInput.className = 'form-check-input';
								checkboxInput.name = 'checkbox';
								checkboxInput.value = attribute;
								checkboxDiv.appendChild(checkboxInput);
								checkboxCell.appendChild(checkboxDiv);
								row.appendChild(checkboxCell);

								const textCell = document.createElement('td');
								textCell.style.paddingLeft = '0px';
								textCell.textContent = attribute;
								row.appendChild(textCell);

								document.querySelector('#attrib_table tbody').appendChild(row);
							});

							// Add event listener to checkboxes
							const checkboxes = document.querySelectorAll('input[name="checkbox"]');
							checkboxes.forEach(checkbox => {
								checkbox.addEventListener('change', () => {
									const checkedCheckboxes = Array.from(document.querySelectorAll('input[name="checkbox"]:checked'))
										.map(checkbox => checkbox.value);
									console.log('Selected checkboxes:', checkedCheckboxes);
								});
							});
						})
						.catch(error => {
							console.error(`Error loading GeoJSON file '${fileName}':`, error);
						});
				});
			}
			loadGeoJSONFiles();

			const toggleButtons = document.querySelectorAll('.toggle-button');
			toggleButtons.forEach(button => {
				button.addEventListener('click', () => {
					toggleButtons.forEach(btn => {
						if (btn !== button) {
							btn.classList.remove('btn-success');
							btn.classList.add('btn-secondary');
						} else {
							btn.classList.remove('btn-secondary');
							btn.classList.add('btn-success');
						}
					});

					const selectedButton = button.dataset.button;
            		console.log('Selected button:', selectedButton);

				});
			});
		</script>
	</body>
	<!--end::Body-->
</html>