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

/**
 * sfGuardUser form.
 *
 * @package    form
 * @subpackage sf_guard_user
 * @version    SVN: $Id: sfGuardUserForm.class.php 13001 2008-11-14 10:45:32Z noel $
 */
class sfGuardForgotPasswordCustomForm extends sfGuardFormForgotPassword
{
  public function configure()
  {
    parent::configure();
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'checkEmail'))));
  }
  
  public function checkEmail($validator, $values)
  {
      $username = $values['username_or_email'];
      
      if (true )
      {
        $error = new sfValidatorError($validator, 'El usuario ingresado no tiene cargado el e-mail.');
        throw new sfValidatorErrorSchema($validator, array('date' => $error));
      }
      
      return $values;
  }
 
}