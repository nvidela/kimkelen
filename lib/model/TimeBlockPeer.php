<?php

class TimeBlockPeer extends BaseTimeBlockPeer
{
    public static function retrieveByShift($shift_id)
    {
        $c = new Criteria();
        $c->add(TimeBlockPeer::SHIFT_ID,$shift_id);
        $c->addAscendingOrderByColumn(TimeBlockPeer::START_TIME);
        
        return TimeBlockPeer::doSelect($c);
       
    }
}
