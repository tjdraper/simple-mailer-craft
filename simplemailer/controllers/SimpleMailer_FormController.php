<?php

namespace Craft;

class SimpleMailer_FormController extends BaseController
{
	/**
	 * Allow all users to access controller
	 *
	 * @var bool
	 */
	protected $allowAnonymous = true;

	/**
	 * Check if this is an ajax request
	 *
	 * @return bool
	 */
	private static function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/**
	 * Post form
	 */
	public function actionPost()
	{
		// Require this to be a post request
		$this->requirePostRequest();

		// Process the post request
		$formConfig = craft()->simpleMailer_form->processFormPost(
			craft()->request->getPost('form')
		);

		// Check if there are errors
		if ($formConfig->hasErrors) {
			// Check if ajax request
			if (self::isAjaxRequest()) {
				$this->returnJson($formConfig->getJsonArray());
			}

			// End controller processing
			return;
		}

		// Send the form
		craft()->simpleMailer_mail->sendForm($formConfig);

		// Check if ajax request
		if (self::isAjaxRequest()) {
			$this->returnJson($formConfig->getJsonArray());

			// End controller processing
			return;
		}

		// Set success flash variable for reload
		craft()->userSession->setFlash("{$formConfig->form}_Sent", true);

		// Redirect to posted URL
		$this->redirectToPostedUrl();
	}
}
