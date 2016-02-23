<?php

  $cs = Yii::app()->getClientScript();

  $cs->registerScriptFile('/js/moment.min.js');
  $cs->registerScriptFile('/js/daterangepicker.js');
  $cs->registerScriptFile('/js/bootstrap.min.js');

  $cs->registerScriptFile('/js/stats_labels/classie.js');
  $cs->registerScriptFile('/js/stats_labels/selectFx.js');

  $cs->registerCssFile('/css/daterangepicker.css');
  $cs->registerCssFile('/css/font-awesome.min.css');
  $cs->registerCssFile('/css/stats_labels/cs-select.css');
  $cs->registerCssFile('/css/stats_labels/cs-skin-rotate.css');
?>


<h1 style="text-align: center;">Статистика</h1>

Всего пациентов в БД: <?=$data['total']['total_patient'] ?>
<br>
Всего визитов за все время:  <?=$data['total']['total_pat_hist'] ?> 

<br><br>
<hr>


<div id="change_visits_profit" style="float:right;">
  Выберите тип статистики:<br>
  <select>
    <option value="monthly">Ежемесячная</option>
    <option value="daily">Ежедневная</option>
  </select>
</div>

<div id="visits_profit">
  <iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=visits_profit&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>
</div>
    
<hr>

<div id="change_doctors_stat" style="float:right;">
  Выберите тип статистики:<br>
  <select>
    <option value="monthly">Ежемесячная</option>
    <option value="daily">Ежедневная</option>
  </select>
</div>

<div id="doctors">
  <iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=doctors&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>
</div>

<hr>

<div id="change_affiliates_stat" style="float:right;">
  Выберите тип статистики:<br>
  <select>
    <option value="monthly">Ежемесячная</option>
    <option value="daily">Ежедневная</option>
  </select>
</div>

<div id="affiliates">
  <iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=affiliates&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>
</div>


<script>
  (function() {
    
      // Смена статистики по прибыли и количеству пациентов
    $("#change_visits_profit select").change(function(){
      
        if($(this).val() == 'monthly'){
          $('#visits_profit').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=visits_profit&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>');
        }

        if($(this).val() == 'daily'){
          $('#visits_profit').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=daily&chart_name=visits_profit&year=<?=date('Y')?>&month=<?=date('m')?>" frameborder="0" allowfullscreen></iframe>');
        }
    });

      // Смена статистики по докторам
    $("#change_doctors_stat select").change(function(){
        
        if($(this).val() == 'monthly'){
          $('#doctors').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=doctors&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>');
        }

        if($(this).val() == 'daily'){
          $('#doctors').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=daily&chart_name=doctors&year=<?=date('Y')?>&month=<?=date('m')?>" frameborder="0" allowfullscreen></iframe>');
        }
    });

      // Смена статистики по филиалам
    $("#change_affiliates_stat select").change(function(){
        
        if($(this).val() == 'monthly'){
          $('#affiliates').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=monthly&chart_name=affiliates&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>');
        }

        if($(this).val() == 'daily'){
          $('#affiliates').html('<iframe width="1267" height="500" src="/stats/chart?chart_type=daily&chart_name=affiliates&year=<?=date('Y')?>&month=<?=date('m')?>" frameborder="0" allowfullscreen></iframe>');
        }
    });

  })();
</script>

<div id="patient_quantity"></div>

<hr>
<div id="services">
  <iframe width="1267" height="400" src="/stats/chart?chart_type=monthly&chart_name=services&year=<?=date('Y')?>" frameborder="0" allowfullscreen></iframe>
</div>

