<?php
/**
* @author Hafid Mukhlasin
* @license MIT License
* @version 1.0
*/  
class EFullCalendarHeart extends CWidget
{

    /**
     * @var string Google's calendar URL.
     */
    public $googleCalendarUrl;

    /**
     * @var string Theme's CSS file.
     */
    public $themeCssFile;

    /**
     * @var array FullCalendar's options.
     */
    public $options=array();

    /**
     * @var array HTML options.
     */
    public $htmlOptions=array();

    
    /**
     * @var string PHP file extension. Default is php.
     */
    public $ext='php';

    /**
     * Run the widget.
     */
    public function run()
    {

        $this->registerFiles();

        echo $this->showOutput();
    }


    /**
     * Register assets.
     */
    protected function registerFiles()
    {        
        $assetsDir=dirname(__FILE__).DIRECTORY_SEPARATOR."fullcalendar-2.0.0-beta2";

        $assets=Yii::app()->assetManager->publish($assetsDir);

        $cs=Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');

        //$ext=defined('YII_DEBUG') && YII_DEBUG ? 'js' : 'min.js';
        $ext="min.js";
        $cs->registerScriptFile($assets.'/lib/moment.'.$ext);
        $cs->registerScriptFile($assets.'/lib/jquery-ui.custom.'.$ext);
        $cs->registerScriptFile($assets.'/fullcalendar/fullcalendar.'.$ext);


        $cs->registerCssFile($assets.'/fullcalendar/fullcalendar.css');
        $cs->registerCssFile($assets.'/fullcalendar/fullcalendar.print.css','print');

        if ($this->googleCalendarUrl) {
            $cs->registerScriptFile($assets.'/fullcalendar/gcal.js');
            $this->options['events']=$this->googleCalendarUrl;
        }
        if ($this->themeCssFile) {
            $this->options['theme']=true;
            $cs->registerCssFile($assets.'/lib/'.$this->themeCssFile);
        }

        $js='$("#'.$this->id.'").fullCalendar('.CJavaScript::encode($this->options).');';
        $cs->registerScript(__CLASS__.'#'.$this->id, $js, CClientScript::POS_READY);
        
    }

    /**
     * Returns the html output.
     *
     * @return string Html output
     */
    protected function showOutput()
    {
        if (! isset($this->htmlOptions['id']))
            $this->htmlOptions['id']=$this->id;

        return CHtml::tag('div', $this->htmlOptions,'');
    }
}
