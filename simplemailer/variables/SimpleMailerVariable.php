<?php

namespace Craft;

class SimpleMailerVariable
{
	/**
	 * Render a form
	 *
	 * @param array $params
	 * @return string
	 */
	public function getForm($params = array())
	{
		return craft()->simpleMailer_form->getForm($params);
	}

	/**
	 * Open a form
	 *
	 * @param string $form
	 * @param array $params
	 * @return string
	 */
	public function getFormOpen($form, $params = array())
	{
		return craft()->simpleMailer_form->getFormOpen($form, $params);
	}

	/**
	 * Close a form
	 *
	 * @return string
	 */
	public function getFormClose()
	{
		return craft()->simpleMailer_form->getFormClose();
	}

	/**
	 * Get form config
	 *
	 * @param string $name
	 * @return array
	 */
	public function getFormConfig($name = null)
	{
		return craft()->simpleMailer_form->getFormConfig($name);
	}
}
