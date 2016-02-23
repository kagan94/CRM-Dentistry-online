<?php
/* @var $this PatientsController */
/* @var $model Patients */
Yii::app()->clientScript->scriptMap['*.js'] = false;
?>


<?php if(isset($model->patient_id)): ?>
<div style="text-align:right; text-decoration:underline;">
    <a href="/patients/print/<?= $model->patient_id; ?>" target="_blank" title="Печать карты о пациенте"><img src="/images/print.png" width="50"></a>
</div>
<?php endif; ?>


<script type="text/javascript">
    $(function() {
        var line2 = $("#ui-id-2").attr("href");

        $('#add_pat_hist').attr('href', $('#add_pat_hist').attr('href')+line2.substr(35,line2.lenght));

        var $dialog = $('#update_pat_hist');
            $dialog.dialog({
                "title":"Добавление новой истории лечения пациента",
                "autoOpen":false,
                "modal":true,
                "width":"900",
                "height":"500",
                "resizable":"false",
                "close":function(){
                    $.fn.yiiGridView.update("patients-history-grid", {
                        data: $(this).serialize()
                   });
                    $("#patients-history-form").remove();
                },
               //  "close":function(){
               //      $('#patients-history-grid').yiiGridView.update();
               //      $('#patients-history-form').remove();
               //      $('#patient_history').html(' ');
               //  },
            });

        $(' #add_pat_hist').click(function() { //table.items a.update,
            $dialog.load($(this).attr('href')).dialog('open');
            return false;
        });

       // $('body').on("click", ".ui-widget-overlay", function() {
       //     $('#update_pat_hist').dialog("close");
       // });
    });
</script>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'update_pat_hist',
    'options'=>array(
        'autoOpen'=>false,
        'modal'=>true,
    ),
));
?>
<?php $this->endWidget();

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'patients-history-grid',
    'dataProvider'=>$model->search(),
    //'filter'=>$model,
    'columns'=>array(
        array(
            'name' => 'doctor_id',
            'value'=> 'isset($data->doctor_id) ? Doctors::getDoctorShortFIO($data->doctor->fio) : ""' ,
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
        array(
            'name' => 'affiliate_id',
            'value'=> 'isset($data->affiliate_id) ? PatientsHistory::getAffiliateName($data->affiliate) : ""' ,
        ),
        array(
            'name' => 'datetime_visit',
            'value'=> '$data->getShortDate($data->datetime_visit)',
        ),
        array(
            'name' => 'duration',
            'value'=> 'isset($data->duration) ? $data->getPrettyDuration($data->duration) : ""',
        ),
        array(
            'name' => 'status_record_id',
            'value'=> 'isset($data->status_record_id) ? $data->statusRecord->title : ""' ,
        ),
        array(
            'name' => 'total2',
            'header'=> 'Стоимость, c учетом скидки, грн.',
            'filter'=>false,
        ),
        array(
            'name' => 'comment',
            'htmlOptions'=>array('style'=>'text-align:left;'),
            'filter' =>  false,
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>(Yii::app()->user->getName()=='admin') ? '{update} &nbsp;{delete}' : '{update}',
            'buttons' => array(
                'delete' => array(
                    'url' => 'Yii::app()->createUrl( "/patient/history/delete", array( "id" => $data->id ) )',
                    'options' => array(
                        'data-update-dialog-title' => Yii::t( 'app', 'Delete confirmation' ),
                    ),
                    'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/delete.png',
                ),
                'update' => array(
                    'url' => 'Yii::app()->createUrl( "/patient/history/update", array( "id" => $data->id ) )',
                    'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/update.png',
                    'click'=>'function(){
                        $("#update_pat_hist").dialog({
                            "title":"Изменение истории лечения",
                            "autoOpen":false,
                            "modal":true,
                            "width":"900",
                            "height":"500",
                            "resizable":"false",
                            "close":function(){
                                $.fn.yiiGridView.update("patients-history-grid", {
                                    data: $(this).serialize()
                               });
                                $("#patients-history-form").remove();
                            },
                        }).load($(this).attr("href")).dialog("open");

                        return false;
                    }',
                ),
            ),
        ),
    ),
)); ?>

<u><b>
    <a id="add_pat_hist" href="/patient/history/create">Добавить новую историю лечения</a>
</b></u>
