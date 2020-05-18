<?php 
/*
 * Kimkëlen - School Management Software
 * Copyright (C) 2013 CeSPI - UNLP <desarrollo@cespi.unlp.edu.ar>
 *
 * This file is part of Kimkëlen.
 *
 * Kimkëlen is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2.0 as published by
 * the Free Software Foundation.
 *
 * Kimkëlen is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kimkëlen.  If not, see <http://www.gnu.org/licenses/gpl-2.0.html>.
 */ ?>
<?php

class TutorPeer extends BaseTutorPeer
{
	static public function findByDocumentTypeAndNumber($document_type,$document_number)
	{
		$c = new Criteria();
		$c->addJoin(TutorPeer::PERSON_ID, PersonPeer::ID);
		$c->add(PersonPeer::IDENTIFICATION_NUMBER, $document_number);
		$c->add(PersonPeer::IDENTIFICATION_TYPE,$document_type );
		$s = self::doSelectOne($c);

		return $s;
	 }
    static public function getForDocumentTypeAndNumber($parameters)
    {
        $c = new Criteria();
        $c->addJoin(TutorPeer::PERSON_ID, PersonPeer::ID);
        $c->add(PersonPeer::IDENTIFICATION_NUMBER, $parameters['document_number']);
        $c->add(PersonPeer::IDENTIFICATION_TYPE,$parameters['document_type'] );
        $t = self::doSelectOne($c);

        if (!$t)
        {
          throw new sfError404Exception(sprintf('Tutor with document "%s" "%s" does not exist.',  $parameters['document_type'], $parameters['document_number']));
        }

        return $t;
    }

    static function getStudentsByTutorId($parameters)
    {
        $c= new Criteria();
        $c->addJoin(TutorPeer::ID, StudentTutorPeer::TUTOR_ID);
        $c->addJoin(StudentPeer::ID,StudentTutorPeer::STUDENT_ID);
        $c->add(TutorPeer::ID, $parameters['tutor_id']);
        $s = StudentPeer::doSelect($c);
        
         if (!$s)
        {
          throw new sfError404Exception(sprintf('Tutor with id "%s"  does not have students.',  $parameters['tutor_id']));
        }

        return $s;
    }
}
