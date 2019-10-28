<?php

/**
 * DivisionRatingReport filter form.
 *
 * @package    kimkelen
 * @subpackage filter
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */   
class TimeBlockForDayReportFormFilter extends sfFormFilter
{
  public function configure()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $sf_formatter_revisited = new sfWidgetFormSchemaFormatterRevisited($this);
    $this->getWidgetSchema()->addFormFormatter("Revisited", $sf_formatter_revisited);
    $this->getWidgetSchema()->setFormFormatterName("Revisited");
    $this->getWidgetSchema()->setNameFormat('time_block_for_day_report[%s]');

    $this->configureWidgets();
    $this->configureValidators();
  }

  public function configureWidgets()
  {    
    $this->setWidget('career_school_year_id', new sfWidgetFormPropelChoice(array(
      'model' => 'CareerSchoolYear', 
      'add_empty' => true,
      'peer_method' => 'sort'
    ))); 
    
    $this->setWidget('shift_id', new sfWidgetFormPropelChoice(array(
      'model' => 'Shift', 
      'add_empty' => true
    ))); 
    sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
    $days = array(0 => '');
    for ($i = $this->getWeekDayFrom(); $i <= $this->getWeekDayTo(); $i++)
    {
      $days[$i] =   __(constant("CourseSubjectDay::DAY_NAME_$i"));
    }
    
    $this->setWidget('day_id', new sfWidgetFormChoice(array('choices'=> $days))) ;
    
    $this->setWidget('career_school_year_period_id', new sfWidgetFormPropelChoice(array(
      'model' => 'CareerSchoolYearPeriod', 
      'add_empty' => true
    )));
    
    $w = new sfWidgetFormChoice(array('choices' => array()));
    $this->setWidget('career_school_year_period_id', new dcWidgetAjaxDependence(array(
        'dependant_widget' => $w,
        'observe_widget_id' => 'time_block_for_day_report_career_school_year_id',
        "message_with_no_value" => "Seleccione una carrera y aparecerán los periodos que correspondan",
        'get_observed_value_callback' => array(get_class($this), 'getCareerSchoolYearPeriods'),
        'label' => 'Career school year period'

    )));

    $this->getWidgetSchema()->setHelp('career_school_year_period_id', 'Si selecciona una opción el reporte mostrará las materias anuales con regimen trimestral y las del periodo elegido.');
  }

  public function getWeekDayFrom()
  {
    return SchoolBehaviourFactory::getInstance()->getFirstCourseSubjectWeekday();
  }

  public function getWeekDayTo()
  {
    return SchoolBehaviourFactory::getInstance()->getLastCourseSubjectWeekday();
  }
    
  public function configureValidators()
  {
    $this->setValidator('career_school_year_id', new sfValidatorPropelChoice(array('model' => 'CareerSchoolYear')));
    $this->setValidator('career_school_year_period_id', new sfValidatorPropelChoice(array('model' => 'CareerSchoolYearPeriod','required' => false)));
    $this->setValidator('shift_id', new sfValidatorPropelChoice(array('model' => 'Shift')));
    $days = array();
    for ($i = $this->getWeekDayFrom(); $i <= $this->getWeekDayTo(); $i++)
    {
      $days[$i] = 'Day_'.$i;
    }
    $this->setValidator('day_id', new sfValidatorChoice(array('choices'=> array_keys($days))));
  }
  
  public static function getCareerSchoolYearPeriods($widget, $values)
  {
    $periods = CareerSchoolYearPeriodPeer::getQuaterlyPeriodsSchoolYear($values);
    
    $choices = array(''=>'');
    for($i=0;$i< count($periods);$i++)
    {
      $choices[$periods[$i]->getId()] = $periods[$i];
    }
    
    $widget->setOption('choices', $choices);
  }

}