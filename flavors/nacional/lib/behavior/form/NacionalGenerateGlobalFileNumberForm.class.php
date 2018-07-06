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

class NacionalGenerateGlobalFileNumberForm extends GenerateGlobalFileNumberForm
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
          //NACIONAL
          $sy = SchoolYearPeer::retrieveCurrent()->getYear();
          $c = new Criteria();
          $c->add(StudentPeer::GLOBAL_FILE_NUMBER,"%$sy%", Criteria::LIKE);
          $num = StudentPeer::doCount($c);
          
          
          $c=new Criteria();
          $c->addJoin(StudentPeer::PERSON_ID, PersonPeer::ID);
          $c->add(StudentPeer::ID,$values,Criteria::IN);
          $c->addAscendingOrderByColumn(PersonPeer::LASTNAME);
          $c->addAscendingOrderByColumn(PersonPeer::FIRSTNAME);
          
          $students = StudentPeer::doSelect($c);
          
          $num ++;
          
          foreach ($students as $s) 
          {
              $global_file_number = $num . '/' . $sy;
              $s->setGlobalFileNumber($global_file_number);
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