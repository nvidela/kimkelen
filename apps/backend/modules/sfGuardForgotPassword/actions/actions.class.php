<?php

/**
 * sfGuardForgotPassword actions.
 *
 * @package    symfony
 * @subpackage sfGuardForgotPassword
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */

require_once(dirname(__FILE__).'/../lib/BasesfGuardForgotPasswordActions.class.php');

class sfGuardForgotPasswordActions extends BasesfGuardForgotPasswordActions
{
 
    	public function executePassword(sfWebRequest $request)
	{
		$this->form = new sfGuardFormForgotPassword();
		if ($request->isMethod(sfRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$values = $this->form->getValues();
				$sf_guard_user = sfGuardUserPeer::retrieveByUsernameOrEmail($values['username_or_email'], true);
				/*$tutor = TutorPeer::retrieveByUsername($sf_guard_user->getUsername());
				if (!is_null($tutor)) {
					$token_user = new TokenUser();
					$token_user->setsfGuardUser($sf_guard_user);
					$token_user->setToken(md5(uniqid(rand(), true)));
					$token_user->save();
					$result = dcMailer::sendResetPasswordEmail($tutor->getPerson(), $token_user);
					if ($result) {
						$this->redirect('@request_reset_password');
					}
				}*/
			}
		}
			$this->setLayout('cleanLayout');
	}
}
