<?php
session_start();
if ($_SESSION["id"] == '') {
	header('Location:login.php');
	exit;
}

error_reporting(E_ALL);

date_default_timezone_set('America/Lima');

//Obtenemos la fecha del día
$initDate = date('Y-m-d');
$endDate = $initDate;

//Obtener los datos del usuario y tienda
$id = $_SESSION["id"];
$id_society = $_SESSION["id_society"];
$user_type = $_SESSION["user_type"];
$nombre = $_SESSION["user_name"];
$correo = $_SESSION["user_email"];
$current_marca = "Todas";
$array_codigos = $_SESSION["user_stores"];
if ($user_type == 'Administrador') {
	$api_tiendas = 'https://cotizadorderco.com/clients/filterTest';
	$update_por_tienda = 'https://cotizadorderco.com/clients/filterTest';
	$current_store_code = '';
} else {
	//$api_tiendas = 'https://cotizadorderco.com/clients/filter';
	$api_tiendas = 'https://cotizadorderco.com/clients/filterTest';
	$update_por_tienda = 'https://cotizadorderco.com/clients/filterTest';

	$arrayStoresCES = [];
	foreach ($array_codigos as $tienda) {
		array_push($arrayStoresCES, $tienda['store_code']);
	}
	$current_store_code = json_encode($arrayStoresCES);
}



?>

<!DOCTYPE html>
<html lang="en">

<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	<title>CRM | DERCO PERÚ</title>
	<meta name="description" content="CRM - DERCO PERÚ es un Dashboard dónde podrán gestionar los leads generados a través de los landings de DERCO PERÚ." />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="canonical" href="https://landing.derco.com.pe/plataforma/crm" />

	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

	<!--end::Fonts-->

	<!--begin::Page Vendors Styles(used by this page)-->
	<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

	<!--end::Page Vendors Styles-->

	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

	<!--end::Global Theme Styles-->

	<!--begin::Layout Themes(used by all pages)-->
	<link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />

	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
</head>

<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="page-loading-enabled page-loading header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed page-loading">

	<?php include("partials/_page-loader.php"); ?>

	<?php include("layout.php"); ?>

	<?php include("partials/_extras/offcanvas/quick-user.php"); ?>

	<?php include("partials/_extras/scrolltop.php"); ?>

	<!--begin::Global Config(global config for global JS scripts)-->
	<script>
		var KTAppSettings = {
			"breakpoints": {
				"sm": 576,
				"md": 768,
				"lg": 992,
				"xl": 1200,
				"xxl": 1400
			},
			"colors": {
				"theme": {
					"base": {
						"white": "#ffffff",
						"primary": "#3699FF",
						"secondary": "#E5EAEE",
						"success": "#1BC5BD",
						"info": "#8950FC",
						"warning": "#FFA800",
						"danger": "#F64E60",
						"light": "#E4E6EF",
						"dark": "#181C32"
					},
					"light": {
						"white": "#ffffff",
						"primary": "#E1F0FF",
						"secondary": "#EBEDF3",
						"success": "#C9F7F5",
						"info": "#EEE5FF",
						"warning": "#FFF4DE",
						"danger": "#FFE2E5",
						"light": "#F3F6F9",
						"dark": "#D6D6E0"
					},
					"inverse": {
						"white": "#ffffff",
						"primary": "#ffffff",
						"secondary": "#3F4254",
						"success": "#ffffff",
						"info": "#ffffff",
						"warning": "#ffffff",
						"danger": "#ffffff",
						"light": "#464E5F",
						"dark": "#ffffff"
					}
				},
				"gray": {
					"gray-100": "#F3F6F9",
					"gray-200": "#EBEDF3",
					"gray-300": "#E4E6EF",
					"gray-400": "#D1D3E0",
					"gray-500": "#B5B5C3",
					"gray-600": "#7E8299",
					"gray-700": "#5E6278",
					"gray-800": "#3F4254",
					"gray-900": "#181C32"
				}
			},
			"font-family": "Poppins"
		};
	</script>
	<!--end::Global Config-->

	<!--begin::Global Theme Bundle(used by all pages)-->
	<script src="assets/plugins/global/plugins.bundle.js"></script>
	<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>

	<!--end::Global Theme Bundle-->

	<!--begin::Page Vendors(used by this page)-->
	<script src="assets/js/pages/widgets.js"></script>
	<script type="text/javascript" src="assets/js/pages/custom/xlsx/xlsx.full.min.js"></script>
	<!--end::Page Vendors-->

	<!--begin::Page Scripts(used by this page)-->
	<script>
		//Global Variables
		var global_current_store_code = '<?php echo $current_store_code; ?>';
		var global_current_marca = '<?php echo $current_marca; ?>';
		if (global_current_store_code != '') {
			global_current_store_code = JSON.parse(global_current_store_code);
			//console.log("global_current_store_code", global_current_store_code)
		}
		var isAdministrador = '<?= $user_type; ?>';
		//Condicionar para administrador
		var consesionariosData = JSON.parse('<?= json_encode($array_codigos) ?>');


		var initDate = $('#initDate').val();
		var endDate = $('#endDate').val();
		var dataTableRaw = '';
		if (initDate == '' && endDate == '') {
			initDate = '<?php echo $initDate; ?>';
			endDate = '<?php echo $endDate; ?>';
		}

		var initDateAmicar = $('#initDateAmicar').val();
		var endDateAmicar = $('#endDateAmicar').val();
		if (initDateAmicar == '' && endDateAmicar == '') {
			initDateAmicar = '<?php echo $initDate; ?>';
			endDateAmicar = '<?php echo $endDate; ?>';
		}

		// Class definition
		var KTDatatableRemoteAjaxLeads = function() {
			// Private functions
			var initTableLanding = function() {
				var global_current_store_code = '<?php echo $current_store_code; ?>';
				var global_current_marca = '<?php echo $current_marca; ?>';
				if (global_current_store_code != '') {
					global_current_store_code = JSON.parse(global_current_store_code);
					//console.log("global_current_store_code", global_current_store_code)
				}
				var code = global_current_store_code; //Variable Global
				var marca2 = global_current_marca;
				$('#codeConcesionario').val('Todas');

				var current_landing = '<?php echo $current_landing; ?>';
				var current_landing2 = '<?php if(isset($current_landing2)) echo $current_landing2; else echo null;?>';
				var current_landing3 = '<?php if(isset($current_landing3)) echo $current_landing3; else echo null;?>';
				var id_cotizador = '';

				var startRange = $('#initDate').val();
				var endRangeDate = $('#endDate').val();

				console.log(startRange, endRangeDate);

				if (startRange == '' && endRangeDate == '') {
					startRange = '<?php echo $initDate; ?>';
					endRangeDate = '<?php echo $endDate; ?>';
					$('#fechaInicial').val(startRange);
					$('#fechaFinal').val(endRangeDate);
				}

				//console.log(current_landing);

				$('#kt_datatable').KTDatatable('destroy');
				$('#kt_datatable').KTDatatable('reload');

				var params = {
					date1: startRange,
					date2: endRangeDate
				};
				if (current_landing && current_landing !== '') {
					var arrayurl1_w2l = [];
					arrayurl1_w2l.push(current_landing);
					if (current_landing2){
						arrayurl1_w2l.push(current_landing2);
					}
					if (current_landing3){
						arrayurl1_w2l.push(current_landing3);
					}
					params = {
						...params,
						url1_w2l: arrayurl1_w2l
					}
				}

				if (code && code !== '') {
					params = {
						...params,
						store: code
					}
				}

				if (marca2 && marca2 !== ''){
					params = {
						...params,
						marca2
					}
				}


				var datatable = $('#kt_datatable').KTDatatable({

					// datasource definition
					data: {
						type: 'remote',
						source: {
							read: {
								url: '<?php echo $api_tiendas; ?>',
								headers: {
									'Authorization': 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo',
									'Accept': 'application/json',
								},
								//contentType: 'application/json',
								params: params,
								map: function(raw) {
									// sample data mapping
									var dataSet = raw;
									if (typeof raw.data !== 'undefined') {
										dataSet = raw.data;
										dataTableRaw = dataSet;
									}
									return dataSet;
								},
								timeout: 90000,
							},
						},
						pageSize: 50,
						serverPaging: false,
						serverFiltering: false,
						serverSorting: false,
					},

					// layout definition
					layout: {
						scroll: false,
						footer: false,
					},

					// column sorting
					sortable: true,

					pagination: true,

					translate: {
						records: {
							processing: 'Cargando leads...',
							noRecords: 'No se encontraron leads.',
						},

						toolbar: {
							pagination: {
								items: {
									default: {
										first: 'Inicio',
										prev: 'Anterior',
										next: 'Siguiente',
										last: 'Fin',
										more: 'Más páginas',
										input: 'Número de página',
										select: 'Máximo por página',
									},

									info: '{{start}} - {{end}} de {{total}} leads',
								}
							}
						},
					},

					// columns definition
					columns: [{
						field: 'id',
						title: '#',
						sortable: 'asc',
						width: 30,
						type: 'number',
						selector: false,
						textAlign: 'center',
					}, {
						field: 'marca2',
						title: 'Marca',
					}, {
						field: 'model_w2l',
						title: 'Modelo',
					}, {
						field: 'version_w2l',
						title: 'Versión',
					}, {
						field: 'rut_w2l',
						title: 'Número de Documento',
					}, {
						field: 'first_name',
						title: 'Nombres',
					}, {
						field: 'last_name',
						title: 'Apellidos',
					}, {
						field: 'fone1_w2l',
						title: 'Celular',
					}, {
						field: 'created_at',
						title: 'Creado',
						autoHide: false,
						type: 'date',
						//format: 'DD/MM/YYYY HH:mm:ss',
					}, {
						field: 'estado',
						title: 'Status',
						overflow: 'visible',
						autoHide: false,
						// callback function support for column rendering
						template: function(row) {
							var status = {
								'Nuevo': {
									'title': 'Por gestionar',
									'class': ' label-light-success'
								},
								'Contactado': {
									'title': 'Contactado-cerrado',
									'class': ' label-light-danger'
								},
								'Cotizado': {
									'title': 'Contactado-cotizado',
									'class': ' label-light-primary'
								},
								'Facturado': {
									'title': 'Facturado',
									'class': ' label-light-success'
								},
								'Cancelado': {
									'title': 'Reservado',
									'class': ' label-light-info'
								},
								'Gestionado': {
									'title': 'No Contactado',
									'class': ' label-light-dark'
								},
							};
							return '<p id="label-' + status[row.estado].title + '"class="label font-weight-bold label-lg ' + status[row.estado].class + ' label-inline py-5">' + status[row.estado].title + '</p>';
						},
					}, {
						field: '_id',
						title: 'ID Cotizador',
						visible: false,
						template: function(row) {
							id_cotizador = row._id;
							return id_cotizador;
						}
					}, {
						field: 'Actions',
						title: 'Acciones',
						sortable: false,
						//width: 80,
						overflow: 'visible',
						autoHide: false,
						template: function() {
							return '\
									<div class="dropdown dropdown-inline">\
										<a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
											<svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
													<rect x="0" y="0" width="24" height="24"/>\
													<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
													<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
												</g>\
											</svg>\
										</a>\
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
											<ul class="navi flex-column navi-hover py-2">\
												<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
													Cambiar estado:\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Nuevo" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-star"></i></span>\
														<span class="navi-text">Por gestionar</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Contactado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-volume-control-phone"></i></span>\
														<span class="navi-text">Contactado-cerrado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cotizado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-file-invoice-dollar"></i></span>\
														<span class="navi-text">Contactado-cotizado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Facturado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-handshake"></i></span>\
														<span class="navi-text">Facturado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cancelado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">Reservado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Gestionado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">No Contactado</span>\
													</a>\
												</li>\
											</ul>\
										</div>\
									</div>\
								';
						},
					}],
				});


				return {
					datatable: function() {
						return datatable;
					}
				};
			};
			// basic demo
			var getLeads = function() {

				var codeConcesionario = global_current_store_code; //Variable Global
				var marca2 = global_current_marca;

				console.log("getLeads -> isAdministrador", isAdministrador)
				//Condicionar para administrador
				var arrayCodes = [];
				if (isAdministrador == 'Administrador') {
					consesionariosData.map(concesionario => {
						if (concesionario.id_concesionario == codeConcesionario) {
							concesionario.tiendas.map(tienda => {
								arrayCodes.push(tienda.code);
							})
							$('#codeConcesionario').val(concesionario.concesionario);
						}
					});
				} else {
					/* console.log('Concesionario data es ............', consesionariosData); */
					consesionariosData.map(concesionario => {
						if (concesionario.store_code == codeConcesionario) {
							$('#codeConcesionario').val(concesionario.store_name);
						}
					})
					arrayCodes = codeConcesionario;
				}

				code = arrayCodes;



				var current_landing = '<?php echo $current_landing; ?>';
				var current_landing2 = '<?php if(isset($current_landing2)) echo $current_landing2; else echo null;?>';
				var current_landing3 = '<?php if(isset($current_landing3)) echo $current_landing3; else echo null;?>';
				var id_cotizador = '';

				var startRange = $('#initDate').val();
				var endRangeDate = $('#endDate').val();
				$('#fechaInicial').val(startRange);
				$('#fechaFinal').val(endRangeDate);

				console.log(startRange, endRangeDate);

				if (startRange == '' && endRangeDate == '') {
					startRange = '<?php echo $initDate; ?>';
					endRangeDate = '<?php echo $endDate; ?>';
					$('#fechaInicial').val(startRange);
					$('#fechaFinal').val(endRangeDate);
				}

				//console.log(current_landing);

				$('#kt_datatable').KTDatatable('destroy');
				$('#kt_datatable').KTDatatable('reload');

				var params = {
					date1: startRange,
					date2: endRangeDate
				};

				if (current_landing && current_landing !== '') {
					var arrayurl1_w2l = [];
					arrayurl1_w2l.push(current_landing);
					if (current_landing2){
						arrayurl1_w2l.push(current_landing2);
					}
					if (current_landing3){
						arrayurl1_w2l.push(current_landing3);
					}
					params = {
						...params,
						url1_w2l: arrayurl1_w2l
					}
				}

				if (code && code !== '') {
					params = {
						...params,
						store: code
					}
				}

				if (marca2 && marca2 !== ''){
					params = {
						...params,
						marca2
					}
				}

				var datatable = $('#kt_datatable').KTDatatable({

					// datasource definition
					data: {
						type: 'remote',
						source: {
							read: {
								url: '<?php echo $update_por_tienda; ?>',
								headers: {
									'Authorization': 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo',
									'Accept': 'application/json',
								},
								//contentType: 'application/json',
								params: params,
								map: function(raw) {
									// sample data mapping
									var dataSet = raw;
									if (typeof raw.data !== 'undefined') {
										dataSet = raw.data;
										dataTableRaw = dataSet;
									}
									return dataSet;
								},
								timeout: 90000,
							},
						},
						pageSize: 50,
						serverPaging: false,
						serverFiltering: false,
						serverSorting: false,
					},
					// layout definition
					layout: {
						scroll: false,
						footer: false,
					},

					// column sorting
					sortable: true,

					pagination: true,

					search: {
						input: $('#generalSearch'),
					},

					translate: {
						records: {
							processing: 'Cargando leads...',
							noRecords: 'No se encontraron leads.',
						},

						toolbar: {
							pagination: {
								items: {
									default: {
										first: 'Inicio',
										prev: 'Anterior',
										next: 'Siguiente',
										last: 'Fin',
										more: 'Más páginas',
										input: 'Número de página',
										select: 'Máximo por página',
									},

									info: '{{start}} - {{end}} de {{total}} leads',
								}
							}
						},
					},

					// columns definition
					columns: [{
						field: 'id',
						title: '#',
						sortable: 'asc',
						width: 30,
						type: 'number',
						selector: false,
						textAlign: 'center',
					}, {
						field: 'marca2',
						title: 'Marca',
					}, {
						field: 'model_w2l',
						title: 'Modelo',
					}, {
						field: 'version_w2l',
						title: 'Versión',
					}, {
						field: 'rut_w2l',
						title: 'Número de Documento',
					}, {
						field: 'first_name',
						title: 'Nombres',
					}, {
						field: 'last_name',
						title: 'Apellidos',
					}, {
						field: 'fone1_w2l',
						title: 'Celular',
					}, {
						field: 'created_at',
						title: 'Creado',
						autoHide: false,
						type: 'date',
						//format: 'DD/MM/YYYY HH:mm:ss',
					}, {
						field: 'estado',
						title: 'Status',
						overflow: 'visible',
						autoHide: false,
						// callback function support for column rendering
						template: function(row) {
							var status = {
								'Nuevo': {
									'title': 'Por gestionar',
									'class': ' label-light-success'
								},
								'Contactado': {
									'title': 'Contactado-cerrado',
									'class': ' label-light-danger'
								},
								'Cotizado': {
									'title': 'Contactado-cotizado',
									'class': ' label-light-primary'
								},
								'Facturado': {
									'title': 'Facturado',
									'class': ' label-light-success'
								},
								'Cancelado': {
									'title': 'Reservado',
									'class': ' label-light-info'
								},
								'Gestionado': {
									'title': 'No Contactado',
									'class': ' label-light-dark'
								},
							};
							return '<p class="label font-weight-bold label-lg ' + status[row.estado].class + ' label-inline py-5">' + status[row.estado].title + '</p>';
						},
					}, {
						field: '_id',
						title: 'ID Cotizador',
						visible: false,
						template: function(row) {
							id_cotizador = row._id;
							return id_cotizador;
						}
					}, {
						field: 'Actions',
						title: 'Acciones',
						sortable: false,
						//width: 80,
						overflow: 'visible',
						autoHide: false,
						template: function() {
							return '\
									<div class="dropdown dropdown-inline">\
										<a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
											<svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
													<rect x="0" y="0" width="24" height="24"/>\
													<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
													<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
												</g>\
											</svg>\
										</a>\
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
											<ul class="navi flex-column navi-hover py-2">\
												<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
													Cambiar estado:\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Nuevo" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-star"></i></span>\
														<span class="navi-text">Por gestionar</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Contactado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-volume-control-phone"></i></span>\
														<span class="navi-text">Contactado-cerrado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cotizado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-file-invoice-dollar"></i></span>\
														<span class="navi-text">Contactado-cotizado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Facturado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-handshake"></i></span>\
														<span class="navi-text">Facturado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cancelado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">Reservado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatus(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Gestionado" data-id="' + id_cotizador + '" data-code="' + code + '" data-landing="' + current_landing + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">No Contactado</span>\
													</a>\
												</li>\
											</ul>\
										</div>\
									</div>\
								';
						},
					}],
				});


				return {
					datatable: function() {
						return datatable;
					}
				};
			};

			var eventsCapture = function() {
				$('#kt_datatable').on('datatable-on-init', function() {
					eventsWriter('Datatable init');
				}).on('datatable-on-layout-updated', function() {
					eventsWriter('Layout render updated');
				}).on('datatable-on-ajax-done', function() {
					eventsWriter('Ajax data successfully updated');
				}).on('datatable-on-ajax-fail', function(e, jqXHR) {
					eventsWriter('Ajax error');
				}).on('datatable-on-goto-page', function(e, args) {
					eventsWriter('Goto to pagination: ' + args.page);
				}).on('datatable-on-update-perpage', function(e, args) {
					eventsWriter('Update page size: ' + args.perpage);
				}).on('datatable-on-reloaded', function(e) {
					eventsWriter('Datatable reloaded');
				}).on('datatable-on-check', function(e, args) {
					eventsWriter('Checkbox active: ' + args.toString());
				}).on('datatable-on-uncheck', function(e, args) {
					eventsWriter('Checkbox inactive: ' + args.toString());
				}).on('datatable-on-sort', function(e, args) {
					eventsWriter('Datatable sorted by ' + args.field + ' ' + args.sort);
				});
			};

			var eventsWriter = function(string) {
				console.log(string);
			};

			return {
				// public functions
				init: function() {
					initTableLanding();
					eventsCapture();
				},

				reloadData: function() {
					getLeads();
					eventsCapture();
				},
			};

		}();

		// Class definition
		var KTDatatableLocalDataLeads = function() {
			// Private functions
			var initTableLanding = function() {

				var code = global_current_store_code; //Variable Global
				var id_cotizador = '';

				var startRange = $('#initDateAmicar').val();
				var endRangeDate = $('#endDateAmicar').val();
				$('#fechaInicialAmicar').val(startRange);
				$('#fechaFinalAmicar').val(endRangeDate);

				if (startRange == '' && endRangeDate == '') {
					startRange = '<?php echo $initDate; ?>';
					endRangeDate = '<?php echo $endDate; ?>';
					$('#fechaInicialAmicar').val(startRange);
					$('#fechaFinalAmicar').val(endRangeDate);
				}

				console.log(startRange, endRangeDate);

				//console.log(current_landing);

				$('#kt_datatableAmicar').KTDatatable('destroy');
				$('#kt_datatableAmicar').KTDatatable('reload');
				var datatable = $('#kt_datatableAmicar').KTDatatable({

					// datasource definition
					data: {
						type: 'remote',
						source: {
							read: {
								url: 'https://landing.derco.com.pe/catalogo-de-flotas/requests/getAmicarLeads.php',
								headers: {
									/* 'Authorization': 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo',  */
									'Accept': 'application/json',
								},
								/* method: 'GET', */
								//contentType: 'application/json',
								params: {
									date1: startRange,
									date2: endRangeDate
								},
								map: function(raw) {
									// sample data mapping
									var dataSet = raw;
									if (typeof raw.data !== 'undefined') {
										dataSet = raw.data;
										dataTableRaw = dataSet;
									}
									return dataSet;
								},
							},
						},
						pageSize: 50,
						serverPaging: false,
						serverFiltering: false,
						serverSorting: false,
					},

					// layout definition
					layout: {
						scroll: false,
						footer: false,
					},

					// column sorting
					sortable: true,

					pagination: true,

					translate: {
						records: {
							processing: 'Cargando leads...',
							noRecords: 'No se encontraron leads.',
						},

						toolbar: {
							pagination: {
								items: {
									default: {
										first: 'Inicio',
										prev: 'Anterior',
										next: 'Siguiente',
										last: 'Fin',
										more: 'Más páginas',
										input: 'Número de página',
										select: 'Máximo por página',
									},

									info: '{{start}} - {{end}} de {{total}} leads',
								}
							}
						},
					},

					// columns definition
					columns: [{
						field: 'id',
						title: '#',
						sortable: 'asc',
						width: 30,
						type: 'number',
						selector: false,
						textAlign: 'center',
					}, {
						field: 'documento',
						title: 'Documento',
					}, {
						field: 'oferta',
						title: 'Oferta',
					}, {
						field: 'cotizando',
						title: 'Cotizando',
					}, {
						field: 'valor_vehiculo_estimado',
						title: 'Valor estimado del vehiculo',
					}, {
						field: 'marca',
						title: 'Marca',
					}, {
						field: 'celular',
						title: 'celular',
					}, {
						field: 'email',
						title: 'Email',
					}, {
						field: 'sucursal',
						title: 'Sucursal',
					}, {
						field: 'fecha_lead',
						title: 'Creado',
						autoHide: false,
						type: 'date',
						//format: 'DD/MM/YYYY HH:mm:ss',
					}, {
						field: 'estado',
						title: 'Status',
						overflow: 'visible',
						autoHide: false,
						// callback function support for column rendering
						template: function(row) {
							var status = {
								'Nuevo': {
									'title': 'Por gestionar',
									'class': ' label-light-success'
								},
								'Contactado': {
									'title': 'Contactado-cerrado',
									'class': ' label-light-danger'
								},
								'Cotizado': {
									'title': 'Contactado-cotizado',
									'class': ' label-light-primary'
								},
								'Facturado': {
									'title': 'Facturado',
									'class': ' label-light-success'
								},
								'Cancelado': {
									'title': 'Reservado',
									'class': ' label-light-info'
								},
								'Gestionado': {
									'title': 'No Contactado',
									'class': ' label-light-dark'
								},
							};
							return '<p class="label font-weight-bold label-lg ' + status[row.estado].class + ' label-inline py-5">' + status[row.estado].title + '</p>';
						},
					}, {
						field: 'real_id',
						title: 'ID Cotizador',
						visible: false,
						template: function(row) {
							id_cotizador = row.real_id;
							return id_cotizador;
						}
					}, {
						field: 'Actions',
						title: 'Acciones',
						sortable: false,
						//width: 80,
						overflow: 'visible',
						autoHide: false,
						template: function() {
							return '\
									<div class="dropdown dropdown-inline">\
										<a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
											<svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
													<rect x="0" y="0" width="24" height="24"/>\
													<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
													<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
												</g>\
											</svg>\
										</a>\
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
											<ul class="navi flex-column navi-hover py-2">\
												<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
													Cambiar estado:\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Nuevo" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-star"></i></span>\
														<span class="navi-text">Por gestionar</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Contactado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-volume-control-phone"></i></span>\
														<span class="navi-text">Contactado-cerrado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cotizado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-file-invoice-dollar"></i></span>\
														<span class="navi-text">Contactado-cotizado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Facturado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-handshake"></i></span>\
														<span class="navi-text">Facturado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cancelado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">Reservado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Gestionado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">No Contactado</span>\
													</a>\
												</li>\
											</ul>\
										</div>\
									</div>\
								';
						},
					}],
				});


				return {
					datatable: function() {
						return datatable;
					}
				};
			};
			// basic demo
			var getLeads = function() {

				var code = global_current_store_code; //Variable Global
				var id_cotizador = '';

				var startRange = $('#initDateAmicar').val();
				var endRangeDate = $('#endDateAmicar').val();
				$('#fechaInicialAmicar').val(startRange);
				$('#fechaFinalAmicar').val(endRangeDate);

				//console.log(current_landing);

				$('#kt_datatableAmicar').KTDatatable('destroy');
				$('#kt_datatableAmicar').KTDatatable('reload');
				var datatable = $('#kt_datatableAmicar').KTDatatable({

					// datasource definition
					data: {
						type: 'remote',
						source: {
							read: {
								url: 'https://landing.derco.com.pe/catalogo-de-flotas/requests/getAmicarLeads.php',
								headers: {
									'Authorization': 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo',
									'Accept': 'application/json',
								},
								//contentType: 'application/json',
								params: {
									date1: startRange,
									date2: endRangeDate
								},
								map: function(raw) {
									// sample data mapping
									var dataSet = raw;
									if (typeof raw.data !== 'undefined') {
										dataSet = raw.data;
										dataTableRaw = dataSet;
									}
									return dataSet;
								},
							},
						},
						pageSize: 50,
						serverPaging: false,
						serverFiltering: false,
						serverSorting: false,
					},

					// layout definition
					layout: {
						scroll: false,
						footer: false,
					},

					// column sorting
					sortable: true,

					pagination: true,

					translate: {
						records: {
							processing: 'Cargando leads...',
							noRecords: 'No se encontraron leads.',
						},

						toolbar: {
							pagination: {
								items: {
									default: {
										first: 'Inicio',
										prev: 'Anterior',
										next: 'Siguiente',
										last: 'Fin',
										more: 'Más páginas',
										input: 'Número de página',
										select: 'Máximo por página',
									},

									info: '{{start}} - {{end}} de {{total}} leads',
								}
							}
						},
					},

					// columns definition
					columns: [{
						field: 'id',
						title: '#',
						sortable: 'asc',
						width: 30,
						type: 'number',
						selector: false,
						textAlign: 'center',
					}, {
						field: 'documento',
						title: 'Documento',
					}, {
						field: 'oferta',
						title: 'Oferta',
					}, {
						field: 'cotizando',
						title: 'Cotizando',
					}, {
						field: 'valor_vehiculo_estimado',
						title: 'Valor estimado del vehiculo',
					}, {
						field: 'marca',
						title: 'Marca',
					}, {
						field: 'celular',
						title: 'celular',
					}, {
						field: 'email',
						title: 'Email',
					}, {
						field: 'sucursal',
						title: 'Sucursal',
					}, {
						field: 'fecha_lead',
						title: 'Creado',
						autoHide: false,
						type: 'date',
						//format: 'DD/MM/YYYY HH:mm:ss',
					}, {
						field: 'estado',
						title: 'Status',
						overflow: 'visible',
						autoHide: false,
						// callback function support for column rendering
						template: function(row) {
							var status = {
								'Nuevo': {
									'title': 'Por gestionar',
									'class': ' label-light-success'
								},
								'Contactado': {
									'title': 'Contactado-cerrado',
									'class': ' label-light-danger'
								},
								'Cotizado': {
									'title': 'Contactado-cotizado',
									'class': ' label-light-primary'
								},
								'Facturado': {
									'title': 'Facturado',
									'class': ' label-light-success'
								},
								'Cancelado': {
									'title': 'Reservado',
									'class': ' label-light-info'
								},
								'Gestionado': {
									'title': 'No Contactado',
									'class': ' label-light-dark'
								},
							};
							return '<p class="label font-weight-bold label-lg ' + status[row.estado].class + ' label-inline py-5">' + status[row.estado].title + '</p>';
						},
					}, {
						field: 'real_id',
						title: 'ID Cotizador',
						visible: false,
						template: function(row) {
							id_cotizador = row.real_id;
							return id_cotizador;
						}
					}, {
						field: 'Actions',
						title: 'Acciones',
						sortable: false,
						//width: 80,
						overflow: 'visible',
						autoHide: false,
						template: function() {
							return '\
									<div class="dropdown dropdown-inline">\
										<a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
											<svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
													<rect x="0" y="0" width="24" height="24"/>\
													<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
													<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
												</g>\
											</svg>\
										</a>\
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
											<ul class="navi flex-column navi-hover py-2">\
												<li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
													Cambiar estado:\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Nuevo" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-star"></i></span>\
														<span class="navi-text">Por gestionar</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Contactado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-volume-control-phone"></i></span>\
														<span class="navi-text">Contactado-cerrado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cotizado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-file-invoice-dollar"></i></span>\
														<span class="navi-text">Contactado-cotizado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Facturado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-handshake"></i></span>\
														<span class="navi-text">Facturado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Cancelado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">Reservado</span>\
													</a>\
												</li>\
												<li class="navi-item">\
													<a href="javascript:void(0);" onclick="updateLeadStatusAmicar(this.dataset.id, this.dataset.status, this.dataset.code, this.dataset.landing);" data-status="Gestionado" data-id="' + id_cotizador + '" data-code="' + code + '" class="navi-link">\
														<span class="navi-icon"><i class="la la-window-close"></i></span>\
														<span class="navi-text">No Contactado</span>\
													</a>\
												</li>\
											</ul>\
										</div>\
									</div>\
								';
						},
					}],
				});


				return {
					datatable: function() {
						return datatable;
					}
				};
			};

			var eventsCapture = function() {
				$('#kt_datatableAmicar').on('datatable-on-init', function() {
					eventsWriter('Datatable init');
				}).on('datatable-on-layout-updated', function() {
					eventsWriter('Layout render updated');
				}).on('datatable-on-ajax-done', function() {
					eventsWriter('Ajax data successfully updated');
				}).on('datatable-on-ajax-fail', function(e, jqXHR) {
					eventsWriter('Ajax error');
				}).on('datatable-on-goto-page', function(e, args) {
					eventsWriter('Goto to pagination: ' + args.page);
				}).on('datatable-on-update-perpage', function(e, args) {
					eventsWriter('Update page size: ' + args.perpage);
				}).on('datatable-on-reloaded', function(e) {
					eventsWriter('Datatable reloaded');
				}).on('datatable-on-check', function(e, args) {
					eventsWriter('Checkbox active: ' + args.toString());
				}).on('datatable-on-uncheck', function(e, args) {
					eventsWriter('Checkbox inactive: ' + args.toString());
				}).on('datatable-on-sort', function(e, args) {
					eventsWriter('Datatable sorted by ' + args.field + ' ' + args.sort);
				});
			};

			var eventsWriter = function(string) {
				console.log(string);
			};

			return {
				// public functions
				init: function() {
					initTableLanding();
					eventsCapture();
				},

				reloadData: function() {
					getLeads();
					eventsCapture();
				},
			};

		}();

		jQuery(document).ready(function() {
			KTDatatableRemoteAjaxLeads.init();
			KTDatatableLocalDataLeads.init();
		});

		var updateLeadStatus = function(id, estado) {
			var myHeaders = new Headers();
			myHeaders.append("Authorization", "JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo");
			myHeaders.append("Accept", "application/json");
			myHeaders.append("Content-Type", "application/json");

			var raw = JSON.stringify({
				"id": id,
				"estado": estado
			});

			var requestOptions = {
				method: 'PUT',
				headers: myHeaders,
				body: raw,
				redirect: 'follow'
			};

			fetch("https://cotizadorderco.com/clients/updateEstado", requestOptions)
				.then((response) => response.json()) // Transform the data into json
				.then(function(data) {
					let respuesta = data.status;

					if (data.status) {
						KTDatatableRemoteAjaxLeads.reloadData();
					} else {
						console.log('Error');
					}
				})
				.catch(function(error) {
					console.log(error);
				});
		}

		var updateLeadStatusAmicar = function(id, estado) {
			var myHeaders = new Headers();
			myHeaders.append("Accept", "application/json");
			myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

			$.ajax({
				url: 'https://landing.derco.com.pe/catalogo-de-flotas/requests/updateEstadoAmicar.php',
				type: 'POST',
				data: {
					id: id,
					estado: estado
				},
				dataType: 'json',
				success: function(response) {
					if (response.status) {
						console.log(response);
						KTDatatableLocalDataLeads.reloadData();
					} else {
						console.log('Error');
					}
				},
				error: function(err) {
					console.log('Error al actualizar el estado')
				}
			})
		}

		function updateStoreId(store_id) {
			global_current_store_code = store_id;
			KTDatatableRemoteAjaxLeads.reloadData();
		}

		function updateMarca(marca) {
			console.log(marca)
			if (marca == 0) global_current_marca = ['suzuki', 'Suzuki','SUZUKI'];
			if (marca == 1) global_current_marca = ['mazda', "Mazda", 'MAZDA'];
			if (marca == 2) global_current_marca = ['renault', "Renault", 'RENAULT'];
			if (marca == 3) global_current_marca = ['changan', 'Changan', 'CHANGAN'];
			if (marca == 4) global_current_marca = ['haval', 'Haval', 'HAVAL'];
			if (marca == 5) global_current_marca = ['jac', 'Jac', 'JAC'];
			if (marca == 6) global_current_marca = ['citroen', 'Citroen', 'CITROËN', 'CITROEN'];
			if (marca == 7) global_current_marca = ['greatwall', 'Great Wall', 'GREAT WALL'];
			KTDatatableRemoteAjaxLeads.reloadData(); 
		}

		function updateStoreIdAmicar(store_id) {
			global_current_store_code = store_id;
			KTDatatableLocalDataLeads.reloadData();
		}

		function updateDateRange() {
			KTDatatableRemoteAjaxLeads.reloadData();
		}

		function updateDateRangeAmicar() {
			KTDatatableLocalDataLeads.reloadData();
		}

		function setTodasTable() {
			KTDatatableRemoteAjaxLeads.init();
		}

		function downloadExcel() {

			var createXLSLFormatObj = [];  // distrito, direccion,  codigo web, codigo de tienda, legales, created_at, 

			var section_title = '<?php echo $section_title; ?>';
			var arrayMarcas = ['Suzuki', 'Renault', 'Mazda', 'Jac', 'Changan', 'Haval', 'Great Wall']
			var xlsRows = [];
			var element = arrayMarcas.findIndex((e) => e == section_title)
			var xlsHeader = []
			
			if (element !== -1){
				var dataTableFilter = dataTableRaw.map(({id, updateFecha, brand_w2l, ...keepFields}) => keepFields);
				 xlsHeader = ['ESTADO', 'ID WEB', 'ID W2L', 'TIPO DE DOCUMENTO', 'DNI / RUC', 'RAZÓN SOCIAL', 'NOMBRES', 'APELLIDOS', 'CELULAR', 'E-MAIL', 'URL FUENTE', 'URL PRINCIPAL', 'MARCA', 'MODELO', 'VERSIÓN', 'CÓDIGO SAP', 'CÓDIGO DE TIENDA', 'PRECIO', 'PAIS', 'LOCAL', 'DISTRITO','DIRECCIÓN', 'CÓDIGO WEB', 'LEGALES','FECHA DE REGISTRO', 'FECHA DE ACTUALIZACION'];
			}else {
				var dataTableFilter = dataTableRaw.map(({id, updateFecha, ...keepFields}) => keepFields);
				 xlsHeader = ['ESTADO', 'ID WEB', 'ID W2L', 'DNI / RUC', 'NOMBRES', 'APELLIDOS', 'CELULAR', 'TIPO DE DOCUMENTO', 'RAZÓN SOCIAL', 'E-MAIL', 'PAÍS', 'URL FUENTE', 'URL PRINCIPAL', 'FUENTE', 'MARCA', 'MODELO', 'VERSIÓN', 'CÓDIGO SAP', 'PRECIO', 'LOCAL', 'DISTRITO','DIRECCIÓN', 'CÓDIGO WEB', 'CÓDIGO DE TIENDA', 'LEGALES','FECHA DE REGISTRO', 'FECHA DE ACTUALIZACION'];
			}

			
			console.log('La data de la tabla es ',dataTableFilter)
			xlsRows.push(dataTableFilter);

			createXLSLFormatObj.push(xlsHeader);

			var count = 0;


			$.each(xlsRows[0], function(index, value) {
				var innerRowData = [];

				$.each(value, function(ind, val) {
					innerRowData.push(val);
				});

				count++;

				createXLSLFormatObj.push(innerRowData);
			});

			var filename = "CES_LEADS.xlsx";
			var ws_name = "Reporte de LEADS";

			if (typeof console !== 'undefined') console.log(new Date());
			var wb = XLSX.utils.book_new(),
				ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);


			XLSX.utils.book_append_sheet(wb, ws, ws_name);


			if (typeof console !== 'undefined') console.log(new Date());
			XLSX.writeFile(wb, filename);
			if (typeof console !== 'undefined') console.log(new Date());
		}

		/* function downloadExcel() {

			var createXLSLFormatObj = [];
			var xlsHeader = ['DOCUMENTO DE IDENTIDAD', 'MARCA', 'MODELO', 'TIENDA', 'FECHA DE RECEPCION', 'CONTACTADO', 'PROSPECTO', 'RESERVA', 'FACTURADO', 'CANCELADO'];

			var xlsRows = [];

			let xlsRowsMap = dataTableRaw.map(({
				_id,
				id,
				id_w2l,
				first_name,
				last_name,
				fone1_w2l,
				email,
				state,
				url1_w2l,
				url2_w2l,
				brand_w2l,
				version_w2l,
				sap_w2l,
				price_w2l,
				cod_origen2_w2l,
				store,
				terms,
				tipo_documento,
				razon_social,
				direccion,
				distrito,
				estado,
				updated_at,
				...keepFields
			}) => keepFields);

			let xlsRowsFinal = xlsRowsMap.map(row => {
				const { updateFecha, ...keepFields } = row;
				let newrow = {};
				newrow = {...newrow, ...{Contactado: updateFecha.contactado_date ? 'SI' : 'NO'}}
				newrow = {...newrow, ...{Prospecto: updateFecha.cotizado_date ? 'SI' : 'NO'}}
				newrow = {...newrow, ...{Reserva: updateFecha.gestionado_date ? 'SI' : 'NO'}}
				newrow = {...newrow, ...{Facturado: updateFecha.facturado_date ? 'SI' : 'NO'}}
				newrow = {...newrow, ...{Cancelado: updateFecha.cancelado_date ? 'SI' : 'NO'}}
				return {...keepFields, ...newrow};
			})

			xlsRows.push(xlsRowsFinal);

			createXLSLFormatObj.push(xlsHeader);

			var count = 0;


			$.each(xlsRows[0], function(index, value) {
				var innerRowData = [];

				$.each(value, function(ind, val) {
					innerRowData.push(val);
				});

				count++;

				createXLSLFormatObj.push(innerRowData);
			});

			var filename = "CES_LEADS.xlsx";
			var ws_name = "Reporte de LEADS";

			if (typeof console !== 'undefined') console.log(new Date());
			var wb = XLSX.utils.book_new(),
				ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);


			XLSX.utils.book_append_sheet(wb, ws, ws_name);


			if (typeof console !== 'undefined') console.log(new Date());
			XLSX.writeFile(wb, filename);
			if (typeof console !== 'undefined') console.log(new Date());
		} */

		function downloadExcelAmicar() {

			var createXLSLFormatObj = [];
			var xlsHeader = [ 'DOCUMENTO', 'OFERTA', 'FECHA_CREADO', 'COTIZANDO', 'VALOR ESTIMADO DEL VEHICULO', 'EMAIL', 'TELEFONO', 'MARCA', 'SUCURSAL', 'ESTADO'];

			var xlsRows = [];

			const xlsRowsMap = dataTableRaw.map(({
				coddoc_base,
				nuevo_date,
				contactado_date,
				gestionado_date,
				cotizado_date,
				facturado_date,
				cancelado_date,
				real_id,
				fecha_registro,
				...keepFields
			}) => keepFields);

			xlsRows.push(xlsRowsMap);

			createXLSLFormatObj.push(xlsHeader);

			var count = 0;


			$.each(xlsRows[0], function(index, value) {
				var innerRowData = [];

				$.each(value, function(ind, val) {
					innerRowData.push(val);
				});

				count++;

				createXLSLFormatObj.push(innerRowData);
			});

			var filename = "CES_LEADS_AMICAR.xlsx";
			var ws_name = "Reporte de LEADS";

			if (typeof console !== 'undefined') console.log(new Date());
			var wb = XLSX.utils.book_new(),
				ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);


			XLSX.utils.book_append_sheet(wb, ws, ws_name);


			if (typeof console !== 'undefined') console.log(new Date());
			XLSX.writeFile(wb, filename);
			if (typeof console !== 'undefined') console.log(new Date());
		}
	</script>
	<!--begin::Page Vendors(used by this page)-->
	<script src="assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>

	<!--end::Page Scripts-->
</body>

<!--end::Body-->

</html>