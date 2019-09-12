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
<?php use_helper('Javascript', 'Object','I18N','Form', 'Asset') ?>
<?php use_stylesheet('/sfPropelRevisitedGeneratorPlugin/css/global.css') ?>
<?php use_stylesheet('/sfPropelRevisitedGeneratorPlugin/css/extended.css') ?>

<?php foreach ($forms as $form): ?>
  <?php include_stylesheets_for_form($form) ?>
  <?php include_javascripts_for_form($form) ?>
<?php endforeach ?>

<div id="sf_admin_container">
  <h1><?php echo __('Assign physical sheet to %examination_subject%', array('%examination_subject%' => $examination_subject->getCareerSubjectSchoolYear()->getCareerSubject())) ?></h1>
  <div class="examination">
    <h3><?php echo __('Examination %examination%', array('%examination%' => $examination_subject->getExamination())) ?></h3>
    <h3><?php echo __('School year %%school_year%%', array('%%school_year%%' => $examination_subject->getCareerSubjectSchoolYear()->getSchoolYear())) ?></h3>
  </div>
  
  <div id="sf_admin_content">
      
     <form id="form" action="<?php echo url_for($url.'/assignPhysicalSheet') ?>" method="post">
        <ul class="sf_admin_actions">
            <li><?php echo link_to(__('Back'), "@$url", array('class' => 'sf_admin_action_go_back')) ?></li>
            <li><input id="submit" type="submit" value="<?php echo __('Save') ?>"  /></li>
        </ul> 
        <input type="hidden" id="id" name="id" value="<?php echo $examination_subject->getId() ?>"/>
        <div id="check_sheet_book" style="display: none"></div>
        <fieldset id="califications_fieldset">
            
            <div class="sf_admin_form_row">                          
                <label for="book_id" class="required"><?php echo __('Book') ?></label>
                <select name="book_id" id="book_id" required="required">
                    <option value="" selected="selected"></option>
                    <?php foreach ($books as $b):?>
                    <option <?php if(!is_null($record_sheet->getBook()) && $record_sheet->getBook()->getId() == $b->getId()):?> selected="selected" <?php endif; ?> value="<?php echo $b->getId()?>"><?php echo $b->getName()?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php foreach($forms as $form): ?>
                <?php echo $form['id']->render() ?>
                <?php echo $form['_csrf_token']->render() ?>
                <?php echo $form['book_id']->render() ?>
                <?php echo $form['sheet']->render() ?>
                <?php echo $form['physical_sheet']->renderRow(array('oninput' => "checkSheetBook(this," . $form['sheet']->getValue() .")")) ?>
                <div id="check_sheet_book_<?php echo $form['sheet']->getValue()?>" class="check_sheet_book_desc" style="display: none"></div>
            <?php endforeach; ?>
        </fieldset>                 
      <ul class="sf_admin_actions">
        <li><?php echo link_to(__('Back'), "@$url", array('class' => 'sf_admin_action_go_back')) ?></li>
        <li><input id="submit" type="submit" value="<?php echo __('Save') ?>"  /></li> 
      </ul>
</form>
  </div>
</div>
