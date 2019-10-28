<?php

/**
 * TimeBlock form.
 *
 * @package    symfony
 * @subpackage form
 * @author     Your name here
 */
class TimeBlockForm extends BaseTimeBlockForm
{
  public function configure()
  {
      parent::configure();
      $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'checkTime'))));
  }
  
  public function checkTime($validator, $values)
  {
 
      if ( $values['start_time'] >= $values['end_time']  )
      {
        $error = new sfValidatorError($validator, 'La hora de inicio debe ser menor a la de fin');
        throw new sfValidatorErrorSchema($validator, array('start_time' => $error));
      }
      return $values;
  }
}
