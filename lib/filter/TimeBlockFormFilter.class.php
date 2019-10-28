<?php

/**
 * TimeBlock filter form.
 *
 * @package    symfony
 * @subpackage filter
 * @author     Your name here
 */
class TimeBlockFormFilter extends BaseTimeBlockFormFilter
{
  public function configure()
  {
       unset($this['start_time'], $this['end_time']);
  }
  
  public function addShiftIdColumnCriteria(Criteria $criteria, $field, $value)
  {
    if ($value)
    {
      $criteria->add(TimeBlockPeer::SHIFT_ID, $value); 
    }
  }
}


