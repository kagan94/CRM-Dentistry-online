<?php

class DatepickerSettings 
{
		// Отключаем выходные дни
    public static function getDaysOff()
    {
		// Берем сегоднешнюю дату
		$y = date("Y"); 
		$m = date("m");
		$dates = array();

		$begin = new DateTime("$y-$m -1 year");
		$begin->modify('first sunday'); 

		$end = new DateTime("$y-$m +1 year");
		$end->modify('last sunday'); 

		$interval = new DateInterval('P1W');
		$interval->createFromDateString('next sunday');

		$all_days_off = new DatePeriod($begin, $interval, $end);

		foreach ($all_days_off as $sunday) {
		    $dates[] = "'" . $sunday->format("Y/m/d") . "'";
		}

		return implode(",", $dates);
    }


		// Рабочие часы фирмы
    public static function getWorkingHours()
    {
		$allowTimes =array();
		$output = '';

		for( $xh=9;$xh<21;$xh++){
			for($xm=0;$xm<60;$xm+=15){
				if($xm==0) $output .= "'".$xh.":0".$xm."',";
				else $output .= "'".$xh.":".$xm."',";
			}
	    }

	    return $output;
    }


		// Остальные настройки для datetimepiker для визита
    public static function getVisitSettings($div_name)
    {
		return "
			// mask:true,
	        allowTimes:[" . self::getWorkingHours() . "],
         	disabledDates: [" . self::getDaysOff() . "], //'2016/01/01'
			lang:'ru',
			step:15,
			scrollMonth:false,
			scrollInput:false,
			defaultTime:'09:00',
			dayOfWeekStart: 1,
			format:'Y-m-d H:i',
			onGenerate: function(ct) {
				$(this).find('.xdsoft_date.xdsoft_5days').addClass('xdsoft_disabled');
			},
			onChangeDateTime: function( currentDateTime ){
			  // Очищаем дату, если каким-то образом дата выпадает на воскресенье 
				if( currentDateTime.getDay()==0 ){
					$('" . $div_name . "').val('');
				}
			},
		"; 
    }


		// Остальные настройки для datetimepiker для даты рождения
    public static function getDateBirthdaySettings()
    {
		return "
			lang:'ru',
			format:'Y-m-d',
			timepicker:false,
			scrollMonth:false,
			scrollInput:false,
		"; 
    }

}
