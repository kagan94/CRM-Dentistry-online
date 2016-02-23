   

<div class="all-image">
<?php if($all_files){
        foreach ($all_files as $file) { ?>
            <div id="img-<?=$file->id;?>" class="image">
                <div>
                    <a href="/upload/patients_files/<?=$patient_id;?>/<?=$file->file_name;?>" data-lightbox="patient_photos">
                        <img src="/upload/patients_files/<?=$patient_id;?>/<?=$file->file_name;?>">
                    </a>
                </div>
                <div>
                    Дата загрузки: <?=$file->uploaded_time;?><br>
                    <a href="#" class="delete-files" data-file-id="<?=$file->id;?>">Удалить</a>
                </div>
            </div>
<?php } } ?>
</div>

<?php
$this->widget('ext.EAjaxUpload.EAjaxUpload',
array(
    'id'=>'uploadFile',
    'config'=>array(
       'action'=>Yii::app()->createUrl('patients/attach_files'),
       'allowedExtensions'=>array("jpg","jpeg","gif","png"),
       'sizeLimit'=>10*1024*1024,// maximum file size in bytes
       // 'minSizeLimit'=>1*1024,// minimum file size in bytes  // 1 KB
       'multiple'=>'multiple',
       'onComplete'=>"js:function(id, fileName, responseJSON){ 
            
            add_image_html = '';
            path_to_img = '';

            if(responseJSON['file_id'] != ''){
                path_to_img = '/upload/patients_files/' + responseJSON['patient_id'] + '/' + responseJSON['filename'];

                add_image_html+= '<div id=\"img-' + responseJSON['file_id'] + '\" class=\"image\">';

                add_image_html+= ' <div>';
                add_image_html+= '  <a href=\"'+ path_to_img +'\" data-lightbox=\"patient_photos\">';
                add_image_html+= '   <img src=\"'+ path_to_img +'\">';
                add_image_html+= '  </a>';
                add_image_html+= ' </div>';

                add_image_html+= ' <div>';
                add_image_html+= '  Дата загрузки: ' + responseJSON['uploaded_time'];
                add_image_html+= ' <br><a href=\"#\" class=\"delete-files\" data-file-id=\"' + responseJSON['file_id'] +'\">Удалить</a>';
                add_image_html+= ' </div>';

                add_image_html+= '</div>';
            }

            if($.trim($('.all-image').html())==''){
                $('.all-image').html( add_image_html );
            } else {
                $( add_image_html ).insertAfter( '.all-image .image:last-child' );
            }
        }",

       'messages'=>array(
            'typeError'=>"Файл \"{file}\" имеет недопостимое расширение. Разрещено загружать файлы только с расширением {extensions}.",
            'sizeError'=>"Размер файла \"{file}\" слишком большой, максимальный размер файла: {sizeLimit}.",
           // 'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
            'emptyError'=>"Файл \"{file}\" пустой, пожалуйста выберите файл заново.",
            'onLeave'=>"Файлы загружаются, если Вы покините страницу загрузка будет отменена"
       ),
    )
)); 
?>

<div id="result-file-uploading"></div>


<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", ".delete-files", function () {
            id = 0;
            id = $(this).attr('data-file-id');

            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'success': function (data) {
                    if (data.status == "success") {
                        $('#img-'+id).remove();
                        $('#result-file-uploading').append('Файл успешно удален.<br>');
                   }
                },
                'url': '/patients/deletefile',
                'cache': false,
                'data': {
                    'file_id': $(this).attr('data-file-id'),
                },
            });
            return false;
        });
    });
</script>
