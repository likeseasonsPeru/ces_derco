<?php
if($_GET) {
	$title = $_GET['page'] ? 'Page ' . $_GET['page'] : 'Dashboard';
} else {
	$title = 'Dashboard';
}

?>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

		<!--begin::Info-->
		<div class="d-flex align-items-center flex-wrap mr-2">

			<!--begin::Page Title-->
			<h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?php echo $title; ?></h5>

			<!--end::Page Title-->

			<!--begin::Action-->
			<div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
			<!--span class="text-muted font-weight-bold mr-4">#XRS-45670</span>
			<a href="#" class="btn btn-light-primary font-weight-bolder btn-sm">Add New</a-->

			<!--end::Action-->
		</div>
		
	</div>
</div>

<!--end::Subheader-->