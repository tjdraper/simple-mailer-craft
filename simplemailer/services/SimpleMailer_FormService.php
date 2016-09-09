<?php

namespace Craft;

class SimpleMailer_FormService extends BaseApplicationComponent
{
	/**
	 * @var array $formConfigs
	 */
	private $formConfigs = array();

	/**
	 * Open a form
	 *
	 * @param string $form
	 * @param array $params
	 * @return string
	 */
	public function getFormOpen($form, $params = array())
	{
		// Start the form tag
		$formOpen = '<form method="post"';

		// Loop through params
		foreach ($params as $key => $val) {
			$formOpen .= ' ' . $key;

			if ($val !== '') {
				$formOpen .= '="' . $val . '"';
			}
		}

		// Close the form tag
		$formOpen .= '>';

		// Get action
		$formOpen .= '<input type="hidden" name="action" value="simpleMailer/form/post">';

		// Set form name
		$formOpen .= '<input type="hidden" name="form" value="' . $form . '">';

		return $formOpen;
	}

	/**
	 * Close a form
	 *
	 * @return string
	 */
	public function getFormClose()
	{
		return '</form>';
	}

	/**
	 * Get form config
	 *
	 * @param string $name
	 * @param array $overrideParams
	 * @return SimpleMailer_FormConfigModel
	 */
	public function getFormConfig($name = null, $overrideParams = array())
	{
		if (isset($this->formConfigs[$name])) {
			$formConfig = $this->formConfigs[$name];
			$formConfig->setConfig($overrideParams);
			return $formConfig;
		} else {
			return new SimpleMailer_FormConfigModel(
				$name,
				$overrideParams
			);
		}
	}

	/**
	 * Get form
	 *
	 * @param array $params
	 * @return string
	 */
	public function getForm($params = array())
	{
		// Make sure form is specified
		if (! isset($params['form'])) {
			return '';
		}

		// Get the form config
		$formConfig = $this->getFormConfig($params['form'], $params);

		// Check if there are errors on the form config
		if ($formConfig->hasErrors) {
			// Check if class already exists
			$existingClass = isset($formConfig->formAttr['class']) ?
				$formConfig->formAttr['class'] : '';

			// Add error class
			$existingClass .= " {$formConfig->errorClass}";

			// Set error class with config
			$formConfig->setConfig(array(
				'formAttr' => array(
					'class' => $existingClass
				)
			));

			// Loop through inputs
			foreach ($formConfig->inputs as $input) {
				// Check if the input has errors
				if ($input->hasError) {
					$existingClass = isset($input->attr['class']) ?
						$input->attr['class'] : '';

					// Add error class
					$existingClass .= " {$input->errorClass}";

					// Set error class with config
					$input->setConfig(array(
						'attr' => array(
							'class' => $existingClass
						)
					));
				}
			}
		}

		// Save the original templates path
		$oldPath = craft()->templates->getTemplatesPath();

		// Set templates path to our plugin
		$newPath = craft()->path->getPluginsPath() . 'simplemailer/templates';

		// Tell Craft to look in our plugin directory for the template
		craft()->templates->setTemplatesPath($newPath);

		// Render the template
		$html = craft()->templates->render('form', array(
			'formConfig' => $formConfig
		));

		// Reset Craft's template path
		craft()->templates->setTemplatesPath($oldPath);

		// Return the rendered HTML
		return $html;
	}

	/**
	 * Process form post
	 *
	 * @param string $form
	 * @throws Exception
	 * @return SimpleMailer_FormConfigModel
	 */
	public function processFormPost($form)
	{
		// Get the form config
		$formConfig = new SimpleMailer_FormConfigModel($form);

		// Make sure we have a valid form config
		if (! $formConfig->form) {
			throw new Exception('Invalid form');
		}

		// Process the form config from post
		$formConfig->processFromPost();

		// Save the form config to this service intsance
		$this->formConfigs[$formConfig->form] = $formConfig;

		// Return the form config
		return $formConfig;
	}
}
