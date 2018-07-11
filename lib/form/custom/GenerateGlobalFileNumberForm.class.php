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

class GenerateGlobalFileNumberForm extends sfForm
{
  
  public static function getCriteriaForAvailableStudentsIds()
  {
    $c_sy = SchoolYearPeer::retrieveCurrent();
    $c = new Criteria();
    $c->addJoin(StudentPeer::ID,SchoolYearStudentPeer::STUDENT_ID);
    $c->addJoin(DivisionStudentPeer::STUDENT_ID, StudentPeer::ID);
    $c->addJoin(DivisionPeer::ID, DivisionStudentPeer::DIVISION_ID);
    $c->addJoin(DivisionPeer::CAREER_SCHOOL_YEAR_ID, CareerSchoolYearPeer::ID);
    $c->add(CareerSchoolYearPeer::SCHOOL_YEAR_ID,$c_sy->getId());
    $c->add(SchoolYearStudentPeer::SCHOOL_YEAR_ID,$c_sy->getId());
    $c->add(StudentPeer::GLOBAL_FILE_NUMBER,array('888888','ingresante',''),Criteria::IN);
    $c->clearSelectColumns();
    $c->addSelectColumn(StudentPeer::ID);
    $stmt = StudentPeer::doSelectStmt($c);
    $students = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    
    $criteria = new Criteria();
    $criteria->add(StudentPeer::ID,$students,Criteria::IN);
    $criteria->addJoin(StudentPeer::PERSON_ID, PersonPeer::ID);
    $criteria->add(PersonPeer::IS_ACTIVE, true);

    return $criteria;

  }  
    
  public function configure()
  {
      
    $this->widgetSchema['student_list'] = new csWidgetFormStudentMany(array('criteria'=> self::getCriteriaForAvailableStudentsIds()));
    $this->getWidget('student_list')->setLabel('Alumnos');

    $this->validatorSchema['student_list'] = new sfValidatorPass();
    
    
    $sf_formatter_revisited = new sfWidgetFormSchemaFormatterRevisited($this);
    $this->getWidgetSchema()->addFormFormatter("Revisited", $sf_formatter_revisited);
    $this->getWidgetSchema()->setFormFormatterName("Revisited");
    
    $this->widgetSchema->setNameFormat('generate_global_file_number[%s]');
    
    $this->validatorSchema->setOption("allow_extra_fields", true);
  }
  
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
          $c->add(StudentPeer::GLOBAL_FILE_NUMBER,array('888888', 'ingresante'), Criteria::NOT_IN);
          $c->addDescendingOrderByColumn(StudentPeer::GLOBAL_FILE_NUMBER);
          $student = StudentPeer::doSelectOne($c);
          $num = $student->getGlobalFileNumber();
          
          
          $c=new Criteria();
          $c->addJoin(StudentPeer::PERSON_ID, PersonPeer::ID);
          $c->add(StudentPeer::ID,$values,Criteria::IN);
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