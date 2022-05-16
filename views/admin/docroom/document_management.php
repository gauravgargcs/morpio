<!-- Plugins css -->
<link href="<?= base_url(); ?>skote_assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

<?= message_box('success'); ?>
<?= message_box('error');

$is_get=false;
if(isset($_GET['folder_id']) && isset($_GET['parentId']) && isset($_GET['folder_name'])){
    $get_folder_id=$_GET['folder_id'];
    $get_parentId=$_GET['parentId'];
    $get_folder_name=$_GET['folder_name'];
    $is_get=true;
}
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

            <?php $this->load->view('admin/skote_layouts/title'); ?>
            

        </div>
    </div>
</div>

<div class="d-xl-flex">
    <div class="w-100">
        <div class="d-md-flex">
            <div class="card filemanager-sidebar me-md-2">
                <div class="card-body">
                    <div class="pull-right float-end mt-2">
                        <a href="<?= base_url() ?>admin/docroom/settings"> <i class="fa fa-cogs"></i></a>
                    </div>
                    <h6 class="card-title mt-2"><?= lang('all_folders') ?></h6>
                    <div class="d-flex flex-column h-100">
                        <div class="mb-4">
                            <div class="mb-3">
                                <?php if($is_get){ ?>

                                <a class="btn btn-light w-100" href="<?=base_url('admin/docroom/create_folder/');?>?folder_id=<?=$get_folder_id; ?>&parentId=<?=$get_parentId; ?>&folder_name=<?=urlencode($get_folder_name);?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><i class="bx bx-folder me-1"></i><?= lang('create_new'); ?> <?= lang('folder'); ?></a>
                                
                                <?php }else{ ?>

                                <a class="btn btn-light w-100" href="<?=base_url('admin/docroom/create_folder/');?>" data-bs-toggle="modal" data-bs-placement="top" data-bs-target="#myModal"><i class="bx bx-folder me-1"></i><?= lang('create_new'); ?> <?= lang('folder'); ?></a>
                              
                                <?php } ?>

                            </div>
                            <?php if(!empty($docrooom_folders_main)){ ?>
                            <ul class="list-unstyled folders-list">
                                <?php foreach ($docrooom_folders_main as $key => $folder) {
                                    $folder_id=$folder->id;
                                    $parentId=$folder->parentId;
                                    $folderName=strval($folder->folderName);
                                    $totalSize=convertToReadableSize($folder->totalSize);
                                    $isPublic=$folder->isPublic;
                                    $status=$folder->status;
                                    $date_added=$folder->date_added;
                                    $date_updated=$folder->date_updated;
                                    $file_count=$folder->file_count;
                                    $child_folder_count=$folder->child_folder_count;
                                    $total_downloads=$folder->total_downloads;
                                    $url_folder=$folder->url_folder;
                                    $urlHash=$folder->urlHash;                                    
                                ?>
                                <li>
                                    <div class="custom-accordion">
                                        <a class="text-body fw-medium py-1 d-flex align-items-center" data-bs-toggle="collapse" href="#child-folder-collapse-<?=$folder_id?>" role="button" aria-expanded="false" aria-controls="child-folder-collapse-<?=$folder_id?>" ondblclick="load_folders(<?=$folder_id?>, <?=$parentId?>, '<?=$folderName?>');" >
                                            <i class="mdi mdi-folder font-size-16 text-warning me-2"></i> <?=$folderName; ?> <span class="font-size-10 text-warning ms-2 mt-1">(<?= $file_count; ?>)</span>
                                            <?php if($child_folder_count>0){ ?> 
                                                <i class="mdi mdi-chevron-up accor-down-icon ms-auto"></i>
                                            <?php } ?>
                                        </a>
                                        <?php if($child_folder_count>0){ ?> 
                                        <div class="collapse show child-folders" id="child-folder-collapse-<?=$folder_id?>" data-id="<?=$folder_id?>"></div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- filemanager-leftsidebar -->

            <div class="w-100">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-xl-9 col-sm-6">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="<?=base_url('admin/docroom')?>"><strong><?=$docroom_folderName; ?></strong></a></li>
                                    <?php if($is_get){
                                        if($get_parentId != $main_folder_id){ ?>
                                        <li class="breadcrumb-item"><strong>...</strong></li>
                                        <?php } ?>
                                        <li class="breadcrumb-item active"><strong><?=$get_folder_name;?></strong></li>
                                    <?php } ?>

                                </ol>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="search-box mb-2 me-2">
                                    <div class="position-relative">
                                        <input type="text" class="form-control bg-light border-light rounded" id="search" placeholder="Search...">
                                        <i class="bx bx-search-alt search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(empty($docrooom_folders) && empty($docrooom_files)){ ?>
                        <div class="row mb-3">
                            <h4 class="text-muted font-size-15 text-center"><?=lang('folder_empty');?></h4>
                        </div>
                        <?php } ?>
                        <?php if($enable_features){ ?>
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <form action="<?=base_url('admin/docroom/create_file/');?><?php if($is_get){ echo $get_folder_id; } ?>" class="dropzone" enctype="multipart/form-data" method="POST">
                                    <div class="fallback" id="">
                                        <input name="file" type="file" multiple="multiple">
                                    </div>
                                    <div class="dz-message needsclick">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                        </div>

                                        <h4>Drop files here or click to upload.</h4>
                                    </div>
                                </form>
                            </div>
                        </div> 
                        <?php } ?>
                        <?php if(!empty($docrooom_folders)){ ?>
                        <div class="row folders">
                            <?php foreach ($docrooom_folders as $key => $folder) {
                                $folder_id=$folder->id;
                                $parentId=$folder->parentId;
                                $folderName=strval($folder->folderName);
                                $totalSize=convertToReadableSize($folder->totalSize);
                                $isPublic=$folder->isPublic;
                                $status=$folder->status;
                                $date_added=$folder->date_added;
                                $date_updated=$folder->date_updated;
                                $file_count=$folder->file_count;
                                $child_folder_count=$folder->child_folder_count;
                                $total_downloads=$folder->total_downloads;
                                $url_folder=$folder->url_folder;
                                $urlHash=$folder->urlHash;                                    
                            ?>
                            <div class="col-xl-3 col-sm-4">
                                <div class="card shadow-none border">
                                    <div class="card-body p-3">
                                        <div class="">
                                            <div class="avatar-xs me-3 mb-3">
                                                <div class="avatar-title bg-transparent rounded">
                                                    <i class="bx bxs-folder font-size-24 text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="overflow-hidden me-auto">
                                                    <h5 class="font-size-14 text-truncate mb-1"><a href="<?=base_url('admin/docroom/index')?>?folder_id=<?=$folder_id?>&parentId=<?=$parentId?>&folder_name=<?=urlencode($folderName);?>" class="text-body"><?=$folderName; ?> </a></h5>
                                                    <p class="text-muted text-truncate mb-0"><?= $file_count; ?> <?= lang('files');?></p>
                                                </div>
                                                <div class="align-self-end ms-2">
                                                    <p class="text-muted mb-0"><?= $totalSize; ?></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end col -->
                            <?php } ?>
                        </div>
                        <!-- end row -->
                        <?php } ?>
                        
                        
                        <div class="mt-4">
                            <div class="d-flex flex-wrap">
                                <h5 class="font-size-16 me-3"><?= lang('files'); ?></h5>
                            </div>

                            <hr class="mt-2">
                            
                            <div class="table-responsive files-list">
                                <table class="table align-middle table-nowrap table-hover mb-0" id="">
                                    <thead>
                                        <tr>
                                            <th scope="col"><?= lang('name'); ?></th>
                                            <th scope="col"><?= lang('type'); ?></th>
                                            <th scope="col" colspan="2"><?= lang('size'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($docrooom_files)){ ?>
                                        <?php foreach ($docrooom_files as $key => $file) { 
                                            $file_id=$file->id;
                                            $filename=$file->filename;
                                            $file_shortUrl=$file->shortUrl;
                                            $fileType=$file->fileType;
                                            $file_extension=$file->extension;
                                            $fileSize=convertToReadableSize($file->fileSize);
                                            $file_status=$file->status;
                                            $file_downloads=$file->downloads;
                                            $file_folderId=$file->folderId;
                                            $file_keywords=$file->keywords;
                                            $url_file=$file->url_file;
                                            
                                            if($file_extension=='png' || $file_extension=='jpg' || $file_extension=='jpeg'){
                                                $icon_class="mdi-image";
                                            }elseif($file_extension=='pdf'){
                                                $icon_class="mdi-image";
                                            }elseif($file_extension=='zip'){
                                                $icon_class="mdi-folder-zip";
                                            }elseif($file_extension=='txt'){
                                                $icon_class="mdi-text-box";
                                            }else{
                                                $icon_class="mdi-file-document";
                                            }
                                        ?>
                                        <tr>
                                            <td><a href="<?=base_url('admin/docroom/download_file/'.$file_id);?>" target="_blank" class="text-dark fw-medium file-name"><i class="mdi <?=$icon_class; ?> font-size-16 align-middle text-primary me-2"></i><?= $filename; ?></a></td>
                                            <td><?= $fileType; ?></td>
                                            <td><?= $fileSize; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                       
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end w-100 -->
        </div>
    </div>
</div>

<!-- Plugins js -->
<script src="<?=base_url();?>skote_assets/libs/dropzone/min/dropzone.min.js"></script>

<script>
    var account_id='<?=$account_id?>';
    function load_folders_menu_data(parent_folder_id) {
        var formData = {
            'account_id': account_id,
            'parent_folder_id':parent_folder_id
        };

        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: "<?php echo base_url(); ?>admin/docroom/folders_menu_listings",
            data: formData,
            dataType: 'json', // what type of data do we expect back from the server
            encode: true,
            success: function (data) {
                $('#child-folder-collapse-'+parent_folder_id).html(data.folder_list);

            }
        })
    }
    function load_folders(folder_id, parentId, folder_name){
        var base_url='<?=base_url('admin/docroom/index')?>';
        var url=''+base_url+'?folder_id='+folder_id+'&parentId='+parentId+'&folder_name='+folder_name+'';
        window.location=url;
    }
    Dropzone.autoDiscover = false;

$(document).ready(function () {
    var IDs = [];
    $('.folders-list').find('div.child-folders').map(function() {
        IDs.push($(this).attr('data-id'));
    }).get();
    $.each(IDs, function(index, parent_folder_id) {
        load_folders_menu_data(parent_folder_id);
    });  

    $(function () {
        $('#search').keyup(function () {
            var that = this, 
            $allListFilesElements = $('div.files-list > table >tbody >tr');
            var $matchingListElements = $allListFilesElements.filter(function (em, a) {
                var listItemText = $(a).text().toUpperCase(), searchText = that.value.toUpperCase();
                return ~listItemText.indexOf(searchText);
            });
            $allListFilesElements.hide();
            $matchingListElements.show();

            $allListFoldersElements = $('div.folders > div');
            var $matchingListFoldersElements = $allListFoldersElements.filter(function (em, a) {
                var listItemText = $(a).text().toUpperCase(), searchText = that.value.toUpperCase();
                return ~listItemText.indexOf(searchText);
            });

            $allListFoldersElements.hide();
            $matchingListFoldersElements.show();

        });
    });

    var previewdropzone = document.querySelector("form.dropzone");
    if(previewdropzone) {
        var myDropzone = new Dropzone("form.dropzone", { 
            url: "<?=base_url('admin/docroom/create_file/');?><?php if($is_get){ echo $get_folder_id; } ?>"
            }
        );

        myDropzone.on("success",function(file) {
            var file_name= file.name;
            var file_type= file.type;
            var file_size= file.size;
            var icon_class='mdi-file-document';
            if(file_type=='png' || file_type=='jpg' || file_type=='jpeg'){
                icon_class="mdi-image";
            }else if(file_type=='pdf'){
                icon_class="mdi-image";
            }else if(file_type=='zip'){
                icon_class="mdi-folder-zip";
            }else if(file_type=='txt'){
                icon_class="mdi-text-box";
            }
            var cur_url=$(location).attr('href');

            var html='<tr><td><a href="'+cur_url+'" class="text-dark fw-medium file-name"><i class="mdi '+icon_class+' font-size-16 align-middle text-primary me-2"></i>'+file_name+'</a></td><td>'+file_type+'</td><td>'+file_size+'</td></tr>';

            $('div.files-list > table >tbody').append(html);
        });
    }


})
</script>
