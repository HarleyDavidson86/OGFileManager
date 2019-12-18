<div id="jsDocumentManagerModal" class="modal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="container-fluid">
<div class="row">
	<div class="col p-3">
	<!--
		UPLOAD INPUT
	-->
		<h3 class="mt-2 mb-3"><i class="fa fa-file"></i>FileManager</h3>

		<div id="jsalertMedia" class="alert alert-warning d-none" role="alert"></div>

		<!-- Form and Input file -->
		<form name="bluditFormUpload" id="jsOGFormUpload" enctype="multipart/form-data">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="jsdocuments" name="documents[]" multiple>
				<label class="custom-file-label" for="jsdocuments">Dokumente auswählen und auf den Server laden</label>
			</div>
		</form>

		<!-- Progress bar -->
		<div class="progress mt-2">
			<div id="jsbluditProgressBar" class="progress-bar bg-primary" role="progressbar" style="width:0%"></div>
		</div>

	<!--
		IMAGES LIST
	-->
		<!-- Table for list files -->
		<table id="jsbluditMediaTable" class="table mt-2">
			<tr>
				<td>Keine Daten</td>
			</tr>
		</table>

		<!-- Paginator -->
		<nav>
			<ul class="pagination justify-content-center flex-wrap">
			</ul>
		</nav>

	</div>
</div>
</div>
</div>
</div>
</div>

<script>
function openMediaManager() {
	$('#jsDocumentManagerModal').modal('show');
}

function closeMediaManager() {
	$('#jsDocumentManagerModal').modal('hide');
}
</script>