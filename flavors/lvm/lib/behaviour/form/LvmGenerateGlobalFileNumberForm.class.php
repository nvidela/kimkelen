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

class LvmGenerateGlobalFileNumberForm extends GenerateGlobalFileNumberForm
{  
  public function save($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['student_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = Propel::getConnection();
    }
    $con->beginTransaction();
    try
    {
      $values = $this->getValue('student_list');

      if (is_array($values))
      {
          //tomo el número de legajo más grande.
          $c = new Criteria();
          $c->add(StudentPeer::GLOBAL_FILE_NUMBER,array('888888', 'ingresante', 'Intercambio AFS'), Criteria::NOT_IN);
          $c->addDescendingOrderByColumn(StudentPeer::GLOBAL_FILE_NUMBER);
          $student = StudentPeer::doSelectOne($c);
          $num = $student->getGlobalFileNumber();
          
          
          /*Ordeno los alumnos seleccionados por division y luego alfabeticamente*/
          $c=new Criteria();
          $c->addJoin(StudentPeer::PERSON_ID, PersonPeer::ID);
          $c->addJoin(StudentPeer::ID, DivisionStudentPeer::STUDENT_ID);
          $c->addJoin(DivisionStudentPeer::DIVISION_ID, DivisionPeer::ID);
          $c->addJoin(DivisionPeer::DIVISION_TITLE_ID, DivisionTitlePeer::ID);
          $c->addJoin(DivisionPeer::CAREER_SCHOOL_YEAR_ID, CareerSchoolYearPeer::ID);
          $c->add(CareerSchoolYearPeer::SCHOOL_YEAR_ID, SchoolYearPeer::retrieveCurrent()->getId());
          $c->add(StudentPeer::ID,$values,Criteria::IN);
          $c->addAscendingOrderByColumn(DivisionPeer::YEAR);
          $c->addAscendingOrderByColumn(DivisionTitlePeer::NAME);
          $c->addAscendingOrderByColumn(PersonPeer::LASTNAME);
          $c->addAscendingOrderByColumn(PersonPeer::FIRSTNAME);
          
          $students = StudentPeer::doSelect($c);
          
          $num ++;
          
          foreach ($students as $s) 
          {
              $s->setGlobalFileNumber($num);
              $s->save($con);
              $num ++;
          }
      }
      $con->commit();
     }
    catch (Exception $e)
    {
      $con->rollBack();
      throw $e;
    }
  
  }

}