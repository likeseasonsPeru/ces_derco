<?php
$array_codigos = $_SESSION["user_stores"];
$tipo_usuario = $_SESSION["user_type"];
$current_landing = $current_landing;
$html_code = '';
if($tipo_usuario == "Administrador"){
	$textoSelect = 'Seleccione un concesionario';
	foreach($array_codigos as $concesionario) {
		$js_store_code = "".json_encode($concesionario['id_concesionario'])."";
	
		$html_code .= '<li class="navi-item">';
		$html_code .= '<a href="javascript:void(0);" onclick="updateStoreId('.$js_store_code.')" class="navi-link">'; // llamar al JS de tienda
		$html_code .= '<span class="navi-icon">';
		$html_code .= '<i class="la la-store"></i>';
		$html_code .= '</span>';
		$html_code .= '<span class="navi-text">'.$concesionario['concesionario'].' - ('.count($concesionario['tiendas']).' - Tiendas)</span>';
		$html_code .= '</a>';
		$html_code .= '</li>';
	}
}else{
	$textoSelect = 'Seleccione una tienda';
	foreach($array_codigos as $store_code => $key) {
		/*if($store_code != '') {
			
		}*/
	
		$js_store_code = "'".$array_codigos[$store_code]['store_code']."'";
	
		$html_code .= '<li class="navi-item">';
		$html_code .= '<a href="javascript:void(0);" onclick="updateStoreId('.$js_store_code.');" class="navi-link">'; // llamar al JS de tienda
		$html_code .= '<span class="navi-icon">';
		$html_code .= '<i class="la la-store"></i>';
		$html_code .= '</span>';
		$html_code .= '<span class="navi-text">'.$array_codigos[$store_code]['store_name'].' - ('.$array_codigos[$store_code]['store_code'].')</span>';
		$html_code .= '</a>';
		$html_code .= '</li>';
	}
}

?>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Card-->
		<div class="card card-custom">
			<div class="card-header flex-wrap border-0 pt-6 pb-0">
				<div class="card-title">
					<h3 class="card-label"><?php echo $section_title; ?>
						<span class="d-block text-muted pt-2 font-size-sm">LEADS generados en el landing. <a
								href="<?php echo $current_landing; ?>" target="_blank">Visitar landing</a></span></h3>
				</div>
				<div class="card-toolbar">
					<!--begin::Dropdown-->
					<div class="dropdown dropdown-inline mr-2">
						<button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="svg-icon svg-icon-md">
								<svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"
									width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path
											d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
											fill="#000000" opacity="0.3" />
										<path
											d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
											fill="#000000" />
									</g>
								</svg>
							</span>
							<?= $textoSelect; ?>
							</button>
						<!--begin::Dropdown Menu-->
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right"
							style="width:100%;     transform: translate3d(5px, 39px, 0px);">
							<!--begin::Navigation-->
							<ul class="navi flex-column navi-hover py-2 editDropdown">
								<!--li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Seleccione una tienda:</li-->
								<li class="navi-item"><a href="javascript:void(0);" onclick="setTodasTable();" class="navi-link">
								<span class="navi-icon"><i class="la la-store"></i></span><span class="navi-text">
									Todas
								</span></a></li>
								<?php echo $html_code; ?>
							</ul>
							<!--end::Navigation-->
						</div>
						<!--end::Dropdown Menu-->
					</div>
					<!--end::Dropdown-->
				</div>
			</div>
			<div class="card-body">
				<!--begin: Datatable-->
				<div class="row">
					<div class="col-sm-4 text-left">
						<span>Rango de fechas:</span>
						<div class="input-daterange input-group" id="kt_datepicker_5">
							<input id="initDate" type="text" class="form-control" name="start" />
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="la la-ellipsis-h"></i>
								</span>
							</div>
							<input id="endDate" type="text" class="form-control" name="end" />
						</div>
						<span class="form-text text-muted">Seleccione un rango de fechas</span>
						<button type="button" class="btn btn-primary" onclick="updateDateRange()">Filtrar</button>
					</div>
					<div class="col-sm-8 text-right">
						<button type="button" class="btn btn-success" onclick="downloadExcel()">Descargar Excel</button>
					</div>
				</div>
				<div class="row">
					<div class="col-12 mt-4">
						<label for="codeConcesionario">Tienda o concesionario: </label>
						<input style="min-width: 300px;" id="codeConcesionario" type="text" disabled="true">
					</div>
					<div class="col-12 mt-4">
						<label for="codeConcesionario">Fecha Inicial: </label>
						<input style="min-width: 250px;" id="fechaInicial" type="text" disabled="true">
					</div>
					<div class="col-12 mt-4">
						<label for="codeConcesionario">Fecha Final: </label>
						<input style="min-width: 250px;" id="fechaFinal" type="text" disabled="true">
					</div>

					<div class="col-12 mt-4">
						<label for="codeConcesionario">DNI o nombre:</label>
						<input style="min-width: 250px;" id="generalSearch" type="text">
					</div>
				</div>
				<div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
				<!--end: Datatable-->
			</div>
		</div>
		<!--end::Card-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->