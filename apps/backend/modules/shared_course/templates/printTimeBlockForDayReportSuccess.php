<?php /*
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
<?php use_stylesheet('examination-record.css') ?>
<?php use_stylesheet('print-examination-record.css', 'last', array('media' => 'print')) ?>
<?php use_helper('I18N', 'Date', 'Javascript') ?>
<?php use_helper('Asset', 'I18N') ?>
<div class="non-printable">
  <a href="<?php echo url_for('shared_course/filterForDay') ?>"><?php echo __('Go back') ?></a>
  <a href="<?php echo url_for('shared_course/reportCourseSubjectsToPDF') ?>"><?php echo __('Export') ?></a>
</div>

<div class="record-wrapper">
  <div class="">
      <div class="logo"><?php echo image_tag("kimkelen_logo.png", array('absolute' => true)) ?></div>
  </div>  
  <div style="text-align: center; ">
      <h2><?php echo __("Shift %shift%",array('%shift%'=>$shift))?></h2>
  </div>
  <div class="gray-background">
    <span><strong><?php echo 'Día: ' . __(constant("CourseSubjectDay::DAY_NAME_$day"))?></strong></span>
    <span class="right"><strong><?php echo 'Ciclo lectivo: ' . $career_school_year->getSchoolYear()->getYear() ?></strong>  </span>
  </div>
  <br>

<table class="table_course_subject">
    <thead>
        <th class="th_division"></th>
        <?php foreach ($time_blocks as $tb):?>
        <th><?php echo format_date($tb->getStartTime(), 'HH:mm')  . ' - '. format_date($tb->getEndTime(), 'HH:m')?></th>
        <?php endforeach; ?>
    </thead>
    <tbody>
    <?php foreach ($divisions as $d):?>
        <tr>
            <td class="th_division"><strong><?php echo $d->getYear() .'°' . $d->getDivisionTitle()->getName()?></strong></td>
            <?php foreach ($time_blocks as $tb):?>
            <td>
                <?php $css = $d->getCourseSubjectsPerTimeBLock($tb,$day,$period_id);?>
                <?php foreach ($css as $cs):?>
                <div class="box_course">
                    <span class="subject_info_report"><strong><?php echo $cs?></strong></span>
                    <div class="teacher_info_report"><?php echo ($cs->getTeachersString() != "") ? "( " . $cs->getTeachersString() . ")" : ""?></div>
                </div>
                
                <?php endforeach; ?>
            </td>
            <?php endforeach; ?>
        </tr>
        
        
       <?php endforeach; ?>
        
     
    </tbody>

</table>
</div>

