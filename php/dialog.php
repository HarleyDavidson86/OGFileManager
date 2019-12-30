<?php
// Preload the first 5 documents to not call via AJAX when the user open the first time the media manager
$listOfFilesByPage = Filesystem::listFiles(OGFM_PATH_DOCUMENTS_ABS, '*', '*', true, 5);
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

                        <div id="jsogfmtMedia" class="alert alert-warning d-none" role="alert"></div>

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
                                <?php for ($i = 1; $i <= $numberOfPages; $i++): ?>
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
echo 'var preLoadDocs = ' . json_encode($preLoadDocs) . ';';
echo 'var PATH_OGFM = "' . HTML_PATH_PLUGINS . 'OGFileManager' . DS . '";';
echo 'var OGFM_PATH_DOCUMENTS_ABS = "' . OGFM_PATH_DOCUMENTS_ABS . '";';
?>

    $(document).ready(function () {
        // Display the files preloaded for the first time
        displayDocs(preLoadDocs);

        // Select image event
        $("#jsdocuments").on("change", function (e) {
            uploadDocuments();
        });
    });

    function closeFileManager() {
        $('#jsDocumentManagerModal').modal('hide');
    }

    function showMediaAlert(message) {
        $("#jsogfmtMedia").html(message).removeClass('d-none');
    }

    function hideMediaAlert() {
        $("#jsogfmtMedia").addClass('d-none');
    }

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
            $.each(files, function (key, filename) {
                //Maybe show Icons for different extensions?
                var thumbnail = "fa fa-file";

                tableRow = '<tr id="js' + filename + '">' +
                        '<td style="width:80px"><span class="img-thumbnail ' + thumbnail + '" style="width: 50px; height: 50px; font-size: 30px; text-align: center;"><\/td>' +
                        '<td class="information">' +
                        '<div class="text-primary pb-2">' + filename + '<\/div>' +
                        '<div>' +
                        '<a href="#" class="mr-3 text-secondary" onClick="editorInsertDocument(\'' + filename + '\'); closeFileManager();"><i class="fa fa-plus"></i><?php $L->p('Insert') ?><\/a>' +
                        '<a href="#" class="float-right text-danger" onClick="deleteDocument(\'' + filename + '\')"><i class="fa fa-trash-o"></i><?php $L->p('Delete') ?><\/a>' +
                        '<\/div>' +
                        '<\/td>' +
                        '<\/tr>';
                $('#jsOGDocsTable').append(tableRow);
            });
        }

        if (files.length == 0) {
            $('#jsOGDocsTable').html("<p><?php $L->p('There are no documents'); ?></p>");
        }
    }

// Get the list of files via AJAX, filter by the page number
    function getFiles(pageNumber) {
        $.post(PATH_OGFM + "ajax/list-documents.php",
                {tokenCSRF: tokenCSRF,
                    pageNumber: pageNumber,
                    uuid: "<?php echo PAGE_IMAGES_KEY ?>",
                    path: OGFM_PATH_DOCUMENTS_ABS
                },
                function (data) { // success function
                    if (data.status == 0) {
                        displayDocs(data.files);
                    } else {
                        console.log(data.message);
                    }
                }
        );
    }

    function deleteDocument(filename) {
        $.post(PATH_OGFM + "ajax/delete-document.php",
                {tokenCSRF: tokenCSRF,
                    filename: filename,
                    path: OGFM_PATH_DOCUMENTS_ABS,
                    uuid: "<?php echo PAGE_IMAGES_KEY; ?>"
                },
                function (data) { // success function
                    if (data.status == 0) {
                        getFiles(1);
                    } else {
                        console.log(data.message);
                    }
                }
        );
    }

    function uploadDocuments() {
        // Remove current alerts
        hideMediaAlert();

        var images = $("#jsdocuments")[0].files;
        for (var i = 0; i < images.length; i++) {

            // Check file size and compare with PHP upload_max_filesize
            if (images[i].size > UPLOAD_MAX_FILESIZE) {
                showMediaAlert("<?php echo $L->g('Maximum load file size allowed:') . ' ' . ini_get('upload_max_filesize') ?>");
                return false;
            }
        }
        ;

        // Clean progress bar
        $("#jsogfmProgressBar").removeClass().addClass("progress-bar bg-primary");
        $("#jsogfmProgressBar").width("0");

        // Data to send via AJAX
        var formData = new FormData($("#jsOGFormUpload")[0]);
        formData.append("uuid", "<?php echo PAGE_IMAGES_KEY ?>");
        formData.append("tokenCSRF", tokenCSRF);
        formData.append("destPath", OGFM_PATH_DOCUMENTS_ABS);
        $.ajax({
            url: PATH_OGFM + "ajax/upload-documents.php",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;
                            $("#jsogfmProgressBar").width(percentComplete + "%");
                        }
                    }, false);
                }
                return xhr;
            }
        }).done(function (data) {
            console.log(data)
            if (data.status === 0) {
                $("#jsogfmProgressBar").removeClass("bg-primary").addClass("bg-success");
                // Get the files for the first page, this include the files uploaded
                getFiles(1);
            } else {
                $("#jsogfmProgressBar").removeClass("bg-primary").addClass("bg-danger");
                showMediaAlert(data.message);
            }
        });
    }

</script>