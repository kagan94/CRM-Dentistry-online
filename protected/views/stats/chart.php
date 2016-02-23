<?php 

$year = (int)Yii::app()->request->getParam('year');
$month = (int)Yii::app()->request->getParam('month');
$chart_name = Yii::app()->request->getParam('chart_name');
$chart_type = Yii::app()->request->getParam('chart_type');

$this->getHeadlingChart($chart_type, $chart_name, $year, $month);

if( count($data['errors']) > 0 ) {
  foreach ($data['errors'] as $error) {
    echo "<center>".$error."</center>";
  }
} else {

  switch ($chart_name) {
    case 'visits_profit':
        // Статистика по визитам и прибыли
        $this->Widget('ext.highcharts.HighchartsWidget', array(
          'options' => array(
            'title' => array('text' => 'Количество визитов пациентов и сумма дохода (ежедневно) с уч. всех скидок'),
            'xAxis' => array(
               'categories' =>$data['dates'],
            ),
            'yAxis' => array(
               'title' => array('text' => '') //Количество визитов
            ),
            'colors'=>array('#0563FE', '#6AC36A', '#FFD148', '#FF2F2F'),
            'gradient' => array('enabled'=> true),
            'credits' => array('enabled' => false),
             'plotOptions'=>array(
                  'column'=>array(
                      'dataLabels'=>array(
                          'enabled'=> true
                      ),
                  )
              ),
            'tooltip' => array(
              'formatter'=> 'js:function() {
                if(this.series.name == "Сумма"){
                 return "За период: " +this.x + "<br>Сумма: <b>"+this.y+" грн.</b>";
                } else {
                 return "За период: " +this.x + "<br>Количество: <b>"+this.y+" чел.</b>";
                }
              }',
            ),
            'chart' => array(
              'plotBackgroundColor' => '#ffffff',
              'plotBorderWidth' => null,
              'plotShadow' => false,
              'height' => 400,
            ),
             'series' =>
             array(
              array('type'=>'column','name' => 'Количество', 'data' => $data['values']['quantity']),
              array('type'=>'column','name' => 'Сумма', 'data' => $data['values']['sum']),
             ),
          )
        ));
      
      break;


    case 'doctors':
    case 'affiliates':

      $this->Widget('ext.highcharts.HighchartsWidget', array(
         'options'=>array(
            'title' => array('text' => $data['values']['chart_title']. " с уч. всех скидок"),
            'xAxis' => array(
               'categories' =>$data['dates']
            ),
            'yAxis' => array(
               'title' => array('text' => 'Сумма, грн.')
            ),
            'tooltip' => array(
             'formatter'=> 'js:function() {
                return "За период: " +this.x + "<br>"+this.series.name+": <b>"+this.y+" грн.</b>";
              }',
            ),
            // 'tooltip' => array(
            //      'valueSuffix' => ' грн.'// 'formatter'=> 'js:function() { return this.series.name+":  <b>"+this.value+"</b>%"; }',
            //  ),
            'credits' => array('enabled' => false),
            'series' => $data['values']['output'],
         )
      ));

      break;

    case 'services':

        echo "
        <div class='services_table'>
          <div class='title'>Статистика по услугам (ежемесячная) без уч. скидок</div>
        <table>
            <thead>
                <tr>
                  <th>Код</th>
                  <th>Услуга \ Месяц</th>";

            for($i=1; $i<=12; $i++) { 
              echo "<th>$i/$year</th>";
            }

        echo "</tr></thead>
            <tbody>";

        foreach($data['values']['output'] as $service){ 
          
          echo "<tr><td>". $service['code'] ."</td>  <td>". $service['title'] ."</td>";

          for($i=1; $i<=12; $i++) { 
            echo "<td>". $service['m'.$i] ."</td>";
          } 
          echo "</tr>";
        } 

        echo "</tbody></table></div>";

      break;

    default:
      echo "Выбран не верный тип статистики";
      break;
  }
}

    // $this->Widget('ext.highcharts.HighchartsWidget', array(
    //   'options' => array(
    //     'title' => array('text' => 'Количество визитов пациентов и сумма дохода (ежедневно)'),
    //     'xAxis' => array(
    //        'categories' =>$data['dates'],
    //     ),
    //     'yAxis' => array(
    //        'title' => array('text' => '') //Количество визитов
    //     ),
    //     'colors'=>array('#0563FE', '#6AC36A', '#FFD148', '#FF2F2F'),
    //     'gradient' => array('enabled'=> true),
    //     'credits' => array('enabled' => false),
    //      'plotOptions'=>array(
    //           'column'=>array(
    //               'dataLabels'=>array(
    //                   'enabled'=> true
    //               ),
    //           )
    //       ),
    //     'tooltip' => array(
    //       'formatter'=> 'js:function() {
    //         if(this.series.name == "Сумма"){
    //          return "За период: " +this.x + "/'.$year.'<br>Сумма: <b>"+this.y+" грн.</b>";
    //         } else {
    //          return "За период: " +this.x + "/'.$year.'<br>Количество: <b>"+this.y+" чел.</b>";
    //         }
    //       }',
    //     ),
    //     'chart' => array(
    //       'plotBackgroundColor' => '#ffffff',
    //       'plotBorderWidth' => null,
    //       'plotShadow' => false,
    //       'height' => 400,
    //     ),
    //      'series' =>
    //      array(
    //       array('type'=>'column','name' => 'Количество', 'data' => $chart_arr_values['quantity']),
    //       array('type'=>'column','name' => 'Сумма', 'data' => $chart_arr_values['sum']),
    //      ),

    //   )
    // ));
?>