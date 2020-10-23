<?php
$array_codigos = $_SESSION["user_stores"];
$tipo_usuario = $_SESSION["user_type"];
$current_landing = '';
$html_code = '';


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
					<span class="d-block text-muted pt-2 font-size-sm">LEADS generados en el Amicar</span></h3>
				</div>
				
			</div>
			<div class="card-body">
				<!--begin: Datatable-->
				<div class="row">
					<div class="col-sm-4 text-left">
						<span>Rango de fechas:</span>
						<div class="input-daterange input-group" id="kt_datepicker_5">
							<input id="initDateAmicar" type="text" class="form-control" name="start" />
							<div class="input-group-append">
								<span class="input-group-text">
									<i class="la la-ellipsis-h"></i>
								</span>
							</div>
							<input id="endDateAmicar" type="text" class="form-control" name="end" />
						</div>
						<span class="form-text text-muted">Seleccione un rango de fechas</span>
						<button type="button" class="btn btn-primary" onclick="updateDateRangeAmicar()">Filtrar</button>
					</div>
					<div class="col-sm-8 text-right">
					<button type="button" class="btn btn-success" onclick="downloadExcelAmicar()">Descargar Excel</button>
					</div>
				</div>
				<div class="datatable datatable-bordered datatable-head-custom" id="kt_datatableAmicar"></div>
				<!--end: Datatable-->
			</div>
		</div>
		<!--end::Card-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->