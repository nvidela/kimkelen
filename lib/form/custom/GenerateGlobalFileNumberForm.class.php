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
  
  public function configure()
  {
    
    $sf_formatter_revisited = new sfWidgetFormSchemaFormatterRevisited($this);
    $this->getWidgetSchema()->addFormFormatter("Revisited", $sf_formatter_revisited);
    $this->getWidgetSchema()->setFormFormatterName("Revisited");
    
    $this->widgetSchema->setNameFormat('generate_global_file_number[%s]');
    
    $this->validatorSchema->setOption("allow_extra_fields", true);
  }
  
  
  public function setStudents($students)
  {
    foreach ($students as $student)
    {
      $student_id = $student->getId();
      
      $this->setWidget("number_$student_id", new sfWidgetFormInput(array(), array('value' => 'hola','readOnly'=> true,'required'=>false)));
      
      $this->setValidators(array(
      "number_$student_id"  => new sfValidatorString(array('max_length' => 255,'required' => false)),
     ));
        
      $this->widgetSchema->setLabel("number_$student_id", $student->getPerson()->getFullName());
      
    }
    

  }
  
  public function save($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (is_null($con))
    {
      $con = Propel::getConnection(CareerStudentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
    }

    try
    {
      $con->beginTransaction();

      $values = $this->getValues();
      
      $career = CareerPeer::retrieveByPK($values["career_id"]);
      $year = $values["year"];
      
      unset($values["career_id"]);
      unset($values["year"]);

      foreach ($values as $student_id)
      {
        $student = StudentPeer::retrieveByPk($student_id,$con);
        
        if ($student->isRegisteredToCareer($career, $con) && $student->getIsRegistered())
        {                    
            $c = new Criteria();
            $c->add(StudentCareerSubjectAllowedPeer::STUDENT_ID, $student->getId());
            // added:
            $subc = new Criteria();
            $subc->clearSelectColumns();
            $subc->addSelectColumn(CareerSubjectPeer::ID);
            $subc->add(CareerSubjectPeer::CAREER_ID, $career->getId());
            $stmt = CareerSubjectPeer::doSelectStmt($subc);
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $c->add(StudentCareerSubjectAllowedPeer::CAREER_SUBJECT_ID, $ids, Criteria::IN);

            $allowed = StudentCareerSubjectAllowedPeer::doSelectOne($c, $con);

            if ($allowed)
            {
                // Se consulta si el alumno esta en trayectorias antes de eliminarlo (Trayectoria actual)
                $sy = SchoolYearPeer::retrieveLastYearSchoolYear(SchoolYearPeer::retrieveCurrent());
                $criteria = new Criteria();
                $criteria->add(PathwayStudentPeer::STUDENT_ID, $student_id);
                $criteria->addJoin(PathwayStudentPeer::PATHWAY_ID,PathwayPeer::ID);
                $criteria->add(PathwayPeer::SCHOOL_YEAR_ID,$sy->getId());
                $pathway = PathwayStudentPeer::doSelectOne($criteria, $con);
                if (!$pathway)
                {
                  StudentCareerSubjectAllowedPeer::doDelete($c, $con);
                }   
            }
            
            $career_school_year = CareerSchoolYearPeer::retrieveByCareerAndSchoolYear($career, SchoolYearPeer::retrieveCurrent());
            //First update the year at student_career_school_year

            $student_career_school_year = StudentCareerSchoolYearPeer::getCurrentForStudentAndCareerSchoolYear($student, $career_school_year);
            $career_student = CareerStudentPeer::retrieveByCareerAndStudent($career->getId(), $student->getId());
            
            
            $student_career_school_year->setYear($year);
            $student_career_school_year->save($con);

            $c = new Criteria();
            $c->add(CareerSubjectPeer::CAREER_ID, $career->getId());
            $c->add(CareerSubjectPeer::YEAR, $year);

            foreach (CareerSubjectPeer::doSelect($c) as $career_subject)
            { /* check if not exist */
              $scsa= StudentCareerSubjectAllowedPeer::doCountStudentAndCareerSubject($student, $career_subject);
              if($scsa == 0)
              {
                  $obj = new StudentCareerSubjectAllowed();
                  $obj->setStudentId($student->getId());
                  $obj->setCareerSubject($career_subject);
                  $obj->save($con);
              }
            }

            $prev_school_year = SchoolYearPeer::retrieveLastYearSchoolYear($career_school_year->getSchoolYear());

            if ($prev_school_year) {
            $prev_student_career_school_year = StudentCareerSchoolYearPeer::retrieveCareerSchoolYearForStudentAndYear($student, $prev_school_year);
            }

            if (!empty($prev_student_career_school_year))
            {
              $prev_student_career_school_year = array_shift($prev_student_career_school_year);

              if ($year <= $prev_student_career_school_year->getYear())
              {
                $prev_student_career_school_year->setStatus(StudentCareerSchoolYearStatus::REPPROVED);
                $prev_student_career_school_year->save($con);
              }
              else
              {
                  $prev_student_career_school_year->setStatus(StudentCareerSchoolYearStatus::APPROVED);
                  $prev_student_career_school_year->save($con);
              }    
            }    
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