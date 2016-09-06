<?php

namespace Craft;

class SimpleMailer_FormService extends BaseApplicationComponent
{
	/**
	 * Get form
	 *
	 * @param array $params
	 * @return string
	 */
	public function getForm($params = array())
	{
		// Make sure a form is specified
		if (
			! isset($params['form']) ||
			! $formConfig = $this->getFormConfig($params['form'])
		) {
			return '';
		}

		/**
		 * Set default params
		 */

		// Set formAttr
		if (isset($params['formAttr'])) {
			$formConfig['attr'] = array_merge(
				$formConfig['attr'],
				$params['formAttr']
			);
		}

		// Set labelAttr
		if (isset($params['labelAttr'])) {
			$formConfig['labelAttr'] = array_merge(
				$formConfig['labelAttr'],
				$params['labelAttr']
			);
		}

		// Set fieldsetAttr
		if (isset($params['fieldsetAttr'])) {
			$formConfig['fieldsetAttr'] = array_merge(
				$formConfig['fieldsetAttr'],
				$params['fieldsetAttr']
			);
		}

		// Set labels
		if (isset($params['labels'])) {
			$formConfig['labels'] = $params['labels'];
		}

		// Set input attr
		if (isset($params['inputAttr'])) {
			$formConfig['inputAttr'] = array_merge(
				$formConfig['inputAttr'],
				$params['inputAttr']
			);
		}

		// Set submitAttr
		if (isset($params['submitAttr'])) {
			$formConfig['submitAttr'] = array_merge(
				$formConfig['submitAttr'],
				$params['submitAttr']
			);
		}

		// Loop through inputs and set default attr arrays
		foreach ($formConfig['inputs'] as $key => $val) {
			// Check for inputAttr
			if ($formConfig['inputAttr']) {
				$formConfig['inputs'][$key]['attr'] = array_merge(
					$formConfig['inputs'][$key]['attr'],
					$formConfig['inputAttr']
				);
			}

			// Check for type attr
			if (isset($params[$val['type'] . 'Attr'])) {
				$formConfig['inputs'][$key]['attr'] = array_merge(
					$formConfig['inputs'][$key]['attr'],
					$params[$val['type'] . 'Attr']
				);
			}

			// Check for name attr
			if (isset($params[$key . 'Attr'])) {
				$formConfig['inputs'][$key]['attr'] = array_merge(
					$formConfig['inputs'][$key]['attr'],
					$params[$key . 'Attr']
				);
			}
		}

		// Save the original templates path
		$oldPath = craft()->templates->getTemplatesPath();

		// Set tempaltes path to our plugin
		$newPath = craft()->path->getPluginsPath() . 'simplemailer/templates';

		// Tell Craft to look in our plugin directory for the template
		craft()->templates->setTemplatesPath($newPath);

		// Render the template
		$html = craft()->templates->render('form', array(
			'formConfig' => $formConfig,
			'params' => $params
		));

		// Reset Craft's template path
		craft()->templates->setTemplatesPath($oldPath);

		// Return the rendered HTML
		return $html;
	}

	/**
	 * Open a form
	 *
	 * @param array $params
	 * @return string
	 */
	public function getFormOpen($params = array())
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
	 * @return array
	 */
	public function getFormConfig($name = null)
	{
		// Make sure name is defined
		if (! $name) {
			return array();
		}

		// Get forms config
		$formConfigs = craft()->config->get('forms', 'simplemailer');

		// Check for the specified form config
		if (! isset($formConfigs[$name])) {
			return array();
		}

		// Return the form config
		return $this->formatFormConfig($formConfigs[$name]);
	}

	/**
	 * Format a form config
	 *
	 * @param array $conf
	 * @return array
	 */
	private function formatFormConfig($conf)
	{
		// Make sure attr is set
		$conf['attr'] = isset($conf['attr']) ?
			$conf['attr'] :
			array();

		// Make sure labelAttr is set
		$conf['labelAttr'] = isset($conf['labelAttr']) ?
			$conf['labelAttr'] :
			array();

		// Make sure fieldsetAttr is set
		$conf['fieldsetAttr'] = isset($conf['fieldsetAttr']) ?
			$conf['fieldsetAttr'] :
			true;

		// Make sure labels is set
		$conf['labels'] = isset($conf['labels']) ?
			$conf['labels'] :
			true;

		// Make sure inputAttr is set
		$conf['inputAttr'] = isset($conf['inputAttr']) ?
			$conf['inputAttr'] :
			array();

		// Make sure inputs is set
		$conf['inputs'] = isset($conf['inputs']) ?
			$conf['inputs'] :
			array();

		// Make sure submitAttr is set
		$conf['submitAttr'] = isset($conf['submitAttr']) ?
			$conf['submitAttr'] :
			array();

		// Loop through inputs
		foreach ($conf['inputs'] as $key => $val) {
			// Make sure label is set
			if (! isset($val['label'])) {
				$conf['inputs'][$key]['label'] = $key;
			}

			// Make sure type is set
			if (! isset($val['type'])) {
				$conf['inputs'][$key]['type'] = 'text';
			}

			// Make sure required is set
			if (! isset($val['required'])) {
				$conf['inputs'][$key]['required'] = false;
			}

			// Make sure attr is set
			if (! isset($val['attr'])) {
				$conf['inputs'][$key]['attr'] = array();
			}

			// Check if type is select
			if ($val['type'] === 'select' and ! isset($val['values'])) {
				$config['inputs'][$key]['values'] = array();
			}
		}

		// Return the config
		return $conf;
	}
}
