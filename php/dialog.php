<?php
// Preload the first 10 documents to not call via AJAX when the user open the first time the media manager
$listOfFilesByPage = Filesystem::listFiles(OGFM_PATH_DOCUMENTS, '*', '*', false, 5);
$preLoadDocs = array();
if (!empty($listOfFilesByPage[0])) {
	foreach ($listOfFilesByPage[0] as $file) {
		$filename = Filesystem::filename($file);
		array_push($preLoadDocs, $filename);
	}
}

// Amount of pages for the paginator
$numberOfPages = count($listOfFilesByPage);
?>

<div id="jsDocumentManagerModal" class="modal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="container-fluid">
<div class="row">
	<div class="col p-3">
	<!--
		UPLOAD INPUT
	-->
		<h3 class="mt-2 mb-3"><i class="fa fa-file"></i><?php $L->p('FileManager'); ?></h3>

		<div id="jsalertMedia" class="alert alert-warning d-none" role="alert"></div>

		<!-- Form and Input file -->
		<form name="bluditFormUpload" id="jsOGFormUpload" enctype="multipart/form-data">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="jsdocuments" name="documents[]" multiple>
				<label class="custom-file-label" for="jsdocuments"><?php $L->p('Choose files to upload'); ?></label>
			</div>
		</form>

		<!-- Progress bar -->
		<div class="progress mt-2">
			<div id="jsbluditProgressBar" class="progress-bar bg-primary" role="progressbar" style="width:0%"></div>
		</div>

	<!--
		DOC LIST
	-->
		<!-- Table for list files -->
		<table id="jsOGDocsTable" class="table mt-2">
			<tr>
				<td><?php $L->p('There are no documents'); ?></td>
			</tr>
		</table>

		<!-- Paginator -->
		<nav>
			<ul class="pagination justify-content-center flex-wrap">
				<?php for ($i=1; $i<=$numberOfPages; $i++): ?>
				<li class="page-item"><button type="button" class="btn btn-link page-link" onClick="getFiles(<?php echo $i ?>)"><?php echo $i ?></button></li>
				<?php endfor; ?>
			</ul>
		</nav>

	</div>
</div>
</div>
</div>
</div>
</div>

<script>

<?php
echo 'var preLoadDocs = '.json_encode($preLoadDocs).';';
?>

$(document).ready(function() {
	// Display the files preloaded for the first time
	displayDocs(preLoadDocs);
});

// Remove all files from the table
function cleanDocsTable() {
	$('#jsOGDocsTable').empty();
}

// Show the files in the table
function displayDocs(files) {
	if (!Array.isArray(files)) {
		return false;
	}

	// Clean table
	cleanDocsTable();

	// Regenerate the table
	if (files.length > 0) {
		$.each(files, function(key, filename) {
			//Maybe show Icons for different extensions?
			var thumbnail = "fa fa-file";	
			var image = "<?php echo OGFM_PATH_DOCUMENTS; ?>"+filename;

			tableRow = '<tr id="js'+filename+'">'+
					'<td style="width:80px"><span class="img-thumbnail '+thumbnail+'" style="width: 50px; height: 50px; font-size: 30px; text-align: center;"><\/td>'+
					'<td class="information">'+
						'<div class="text-primary pb-2">'+filename+'<\/div>'+
						'<div>'+
							'<a href="#" class="mr-3 text-secondary" onClick="editorInsertMedia(\''+image+'\'); closeMediaManager();"><i class="fa fa-plus"></i><?php $L->p('Insert') ?><\/a>'+
							'<a href="#" class="text-secondary" onClick="setCoverImage(\''+filename+'\'); closeMediaManager();"><i class="fa fa-square-o"></i><?php $L->p('Set as cover image') ?><\/button>'+
							'<a href="#" class="float-right text-danger" onClick="deleteMedia(\''+filename+'\')"><i class="fa fa-trash-o"></i><?php $L->p('Delete') ?><\/a>'+
						'<\/div>'+
					'<\/td>'+
				'<\/tr>';
			$('#jsOGDocsTable').append(tableRow);
		});
	}

	if (files.length == 0) {
		$('#jsOGDocsTable').html("<p><?php (IMAGE_RESTRICT ? $L->p('There are no images for the page') : $L->p('There are no images')) ?></p>");
	}
}

</script>