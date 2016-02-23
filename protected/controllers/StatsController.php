<?php

class StatsController extends Controller
{
	public $layout='//layouts/column1';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}


	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','daily','monthly','chart'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				//'actions'=>array('daily','view'),				
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');
				
		$data = array();

        $data['total'] = Yii::app()->getDb()->createCommand('
        	select 
        	 (select count(*) from patients) as total_patient,
        	 (select count(*) from patients_history) as total_pat_hist
        ')->queryRow();

		$this->render('view',array(
			'data'=>$data,
		));

	}


	public function actionChart()
	{
		$arr_keys = array();
		$arr_values = array();
		$sum_arr = array();
		$data['errors'] = array();
		$max_num = 1;

		$year = (int)Yii::app()->request->getParam('year');
		$month = (int)Yii::app()->request->getParam('month');
		$chart_name = Yii::app()->request->getParam('chart_name');
		$chart_type = Yii::app()->request->getParam('chart_type');


			// Дневная стата
		if($chart_type === 'daily' && $month != 0 && $year != 0){

			$max_num = 31;  // Для цикла
			$date_format = "DATE_FORMAT(ph.datetime_visit, '%d')";
			$datetime_condition = "BETWEEN '".$year."-".$month."-01 00:00:00' AND '".$year."-".$month."-31 23:59:59'";
				
				// Labels для дней  
	        for($i=1; $i<=31; $i++){
	            $data['dates'][] = "$i/".$month;
	        }
			

			// Помесячная стата
		} else if($chart_type === 'monthly' && $year != 0 ){

			$max_num = 12;  // Для цикла
			$date_format = "DATE_FORMAT(ph.datetime_visit, '%m')";
			$datetime_condition = "BETWEEN '".$year."-01-01 00:00:00' AND '".$year."-12-31 23:59:59'";

				// Labels для месяцев  
			for($i=1; $i<=12; $i++){
			    $data['dates'][] = "$i/".$year;
			}

		} else { 
			$data['errors'][] = "Не верно задан тип графика."  ;
		}

			switch ($chart_name) {

				case 'visits_profit':
		            // Статистика по визитам и прибыли
		          $dataVP = Yii::app()->getDb()->createCommand("
		              SELECT COUNT(id) value , ". $date_format ." name, 
					  (SUM(ph.total)-SUM(ph.discount)) as sum 

		              FROM patients_history ph
		              WHERE ph.datetime_visit ". $datetime_condition . "
		              GROUP BY ". $date_format ."
		          ")->queryAll();

		          if(count($dataVP) > 0){
		              // Перезаписываем массив 
		            for($k=0; $k<count($dataVP); $k++){
		                $chart_arr_keys[(int)$dataVP[$k]['name']] = (int)$dataVP[$k]['value'];
		                $sum_arr[(int)$dataVP[$k]['name']] = (int)$dataVP[$k]['sum'];
		            }

		              //  Формируем новый массив со значениями
		            for($i=1; $i<=$max_num; $i++){
		                if (array_key_exists($i, $chart_arr_keys)) {
		                    $data['values']['quantity'][] = $chart_arr_keys[$i];
		                    $data['values']['sum'][] = $sum_arr[$i];
		                } else {
		                    $data['values']['quantity'][] = 0;
		                    $data['values']['sum'][] = 0;
		                }
		            }
 

				  } else {
				  	$data['errors'][] = "Нет данных за выбранный период.";
				  }

				  break;


				case 'doctors':
				case 'affiliates':
					$data['values'] = $this->loadDataForChart($chart_type, $chart_name, $year, $month);

					if( count($data['values']['output']) == 0 )
						$data['errors'][] = "Нет данных за выбранный период.";
					break;

				case 'services':

					if( count($this->getStatServices('monthly', $chart_name, $year)) == 0 ):
						$data['errors'][] = "Нет данных за выбранный период.";
					else:
						$data['values']['output'] = $this->getStatServices('monthly', $chart_name, $year);
					endif;

					break;


				default:
					$data['errors'][] = "Не верно задано название графика."  ;
					break;
			}


		$this->renderPartial('chart', array('data'=>$data), false, true);

	}

	public function getHeadlingChart($type, $chart_name, $year, $month=0){
		
		echo "<center>";
		
		if($type == 'daily' && $year != 0 && $month != 0){
		
			if($month == 1){
			
				echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. ($year-1) .'&month=12"> [ <<< ] </a>';
				echo "Ежедневные данные за 01/$month/$year - 31/$month/$year";
				
				if( strtotime($year.'-'.($month+1).'-01') <= strtotime( date('Y-n-d') ) )
					echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. $year .'&month=2"> [ >>> ] </a>';
			
			} else if($month == 12) {

				echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. $year .'&month=11"> [ <<< ] </a>';
				echo "Ежедневные данные за 01/$month/$year - 31/$month/$year";
				
				if( strtotime($year.'-'.($month+1).'-01') <= strtotime( date('Y-n-d') ) )
					echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. ($year+1) .'&month=1"> [ >>> ] </a>';
			
			} else {

				echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. $year .'&month='.($month-1).'"> [ <<< ] </a>';
				echo "Ежедневные данные за 01/$month/$year - 31/$month/$year";
				
				if( strtotime($year.'-'.($month+1).'-01') <= strtotime( date('Y-n-d') ) )
					echo '<a href="/stats/chart?chart_type=daily&chart_name='.$chart_name.'&year='. $year .'&month='.($month+1).'"> [ >>> ] </a>';
			}

		} else if($type == 'monthly' && $year != 0){

			echo '<a href="/stats/chart?chart_type=monthly&chart_name='.$chart_name.'&year='. ($year-1) .'"> [ <<< ] </a>';
			echo "Месячные данные за 01/$year - 12/$year";

			if($year < date('Y'))
				echo '<a href="/stats/chart?chart_type=monthly&chart_name='.$chart_name.'&year='. ($year+1) .'"> [ >>> ] </a>';

		} else {
			echo "<center><b>Нет данных за выбранный период.</b></center>";
		}

		echo "</center>";
	}


	public function loadDataForChart($type, $chart_name, $year, $month=''){

		$output_values = array();
		
			// Формируем labels для граффика
		if($type == 'daily'){

			$max_num = 31;  // Для цикла
			$date_format = "DATE_FORMAT(ph.datetime_visit, '%d')";
			$datetime_condition = "ph.datetime_visit BETWEEN '".$year."-".$month."-01 00:00:00' AND '".$year."-".$month."-31 23:59:59'";

		} else if($type == 'monthly'){

			$max_num = 12;  // Для цикла
			$date_format = "DATE_FORMAT(ph.datetime_visit, '%m')";
			$datetime_condition = "ph.datetime_visit BETWEEN '".$year."-01-01 00:00:00' AND '".$year."-12-31 23:59:59'";

		}


			// Подгружаем данные для обработки
		if($chart_name == 'doctors'){

				// Статистика по докторам
			$items = Doctors::model()->FindAll();
			$search_field = "doctor_id";

			if($type == 'daily')
				$chart_title = 'Статистика по врачам (ежедневная)'; // /'.$month.'/'.$year.' год 
			else 
				$chart_title = 'Статистика по врачам за '.$year.' год (ежемесячная)';

		} else if($chart_name == 'affiliates'){

				// Статистика по филиалам
			$items = Affiliate::model()->FindAll();
			$search_field = "affiliate_id";

			if($type == 'daily')
				$chart_title = 'Статистика по филиалам (ежедневная)';
			else 
				$chart_title = 'Статистика по филиалам за '.$year.' год (ежемесячная)';
		}



		foreach ($items as $item) {

			$values = array();
			$arrItemsValues = array();

			$getItems = Yii::app()->getDb()->createCommand("
				SELECT ". $date_format ." month, 
				(SUM(ph.total)-SUM(ph.discount)) as sum 


				FROM patients_history ph
				WHERE ph.".$search_field."=".$item->id."
				AND ". $datetime_condition ."
				GROUP BY ". $date_format
			)->queryAll();

			if(count($getItems) > 0){

					// Перезаписываем массив заточенный под поиск
				for( $i=0; $i<count($getItems); $i++){
					$arrItemsValues[(int)$getItems[$i]['month']] = (int)$getItems[$i]['sum'];
				}

					// формируем новый массив со значениями
				for($i=1; $i<=$max_num; $i++){
				    
				    if (array_key_exists($i, $arrItemsValues)) {
				        $values[] = $arrItemsValues[$i];
				    } else {
				        $values[] = 0;
				    }
				}

				$output_values[]= array(
					"type"=>"line",
					"name"=>($chart_name == "doctors") ? $item->fio :  $item->name,
					"data"=> $values,
				);
			}
		}

		return array(
			'chart_title'=>$chart_title,
			'output'=>$output_values,		
		);
	}


		// Статистика для услуг 
	public function getStatServices($type='monthly', $chart_name, $year){

		$services = Yii::app()->getDb()->createCommand("
			SELECT s.title, s.code,

			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 1 then os.total else 0 end) 'm1',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 2 then os.total else 0 end) 'm2',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 3 then os.total else 0 end) 'm3',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 4 then os.total else 0 end) 'm4',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 5 then os.total else 0 end) 'm5',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 6 then os.total else 0 end) 'm6',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 7 then os.total else 0 end) 'm7',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 8 then os.total else 0 end) 'm8',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 9 then os.total else 0 end) 'm9',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 10 then os.total else 0 end) 'm10',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 11 then os.total else 0 end) 'm11',
			SUM(case WHEN DATE_FORMAT(ph.datetime_visit, '%m') = 12 then os.total else 0 end) 'm12'

			From order_services os
			
			INNER JOIN services s ON os.service_id = s.service_id
			INNER JOIN patients_history ph ON os.patient_history_id = ph.id AND ph.datetime_visit BETWEEN '".$year."-01-01 00:00:00' AND '".$year."-12-31 23:59:59'

			GROUP BY os.service_id
			ORDER BY s.code
        ")->queryAll();

		return $services;
	}


}
