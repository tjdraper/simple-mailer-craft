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

		var_dump(craft()->config->get('forms', 'simplemailer'));
		die;
	}
}
