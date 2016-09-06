<?php

namespace Craft;

class SimpleMailer_FormConfigService extends BaseApplicationComponent
{
	/**
	 * @var bool $hasErrors
	 */
	protected $hasErrors = false;

	/**
	 * @var string $errorClass
	 */
	protected $errorClass = 'error';

	/**
	 * @var string $form
	 */
	protected $form = '';

	/**
	 * @var array $attr
	 */
	protected $formAttr = array();

	/**
	 * @val array labelAttr
	 */
	protected $labelAttr = array();

	/**
	 * @var array fieldsetAttr
	 */
	protected $fieldsetAttr = array();

	/**
	 * @var bool labels
	 */
	protected $labels = true;

	/**
	 * @var array inputAttr
	 */
	protected $inputAttr = array();

	/**
	 * @var array submitAttr
	 */
	protected $submitAttr = array();

	/**
	 * @var array inputs
	 */
	protected $inputs = array();

	/**
	 * Constructor
	 *
	 * @param string $form
	 * @param array $overrideParams
	 */
	public function __construct($form, $overrideParams = array())
	{
		// Get forms config
		$formConfigs = craft()->config->get('forms', 'simplemailer');

		// Check for the specified form config
		if (! isset($formConfigs[$form])) {
			return;
		}

		// Set the form
		$this->form = $form;

		// Form config
		$formConfig = $formConfigs[$form];

		// Set form config
		$this->setConfig($formConfig);

		// Set overrides
		$this->setConfig($overrideParams);
	}

	/**
	 * Get magic method
	 *
	 * @param $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->{$name})) {
			return $this->{$name};
		}

		return null;
	}

	/**
	 * Getter
	 *
	 * @param $name
	 * @return mixed
	 */
	public function get($name)
	{
		return $this->__get($name);
	}

	/**
	 * Set config
	 *
	 * @param array $params
	 */
	public function setConfig($params = array())
	{
		// Check for error class
		if (isset($params['errorClass'])) {
			$this->errorClass = (string) $params['errorClass'];
		}

		// Check for formAttr params
		if (isset($params['formAttr'])) {
			$this->formAttr = array_merge(
				$this->formAttr,
				(array) $params['formAttr']
			);
		}

		// Check for labelAttr params
		if (isset($params['labelAttr'])) {
			$this->labelAttr = array_merge(
				$this->labelAttr,
				(array) $params['labelAttr']
			);
		}

		// Check for fieldsetAttr params
		if (isset($params['fieldsetAttr'])) {
			$this->fieldsetAttr = array_merge(
				$this->fieldsetAttr,
				(array) $params['fieldsetAttr']
			);
		}

		// Check for labels boolean
		if (isset($params['labels'])) {
			$this->labels = (bool) $params['labels'];
		}

		// Check for submitAttr params
		if (isset($params['submitAttr'])) {
			$this->submitAttr = array_merge(
				$this->submitAttr,
				(array) $params['submitAttr']
			);
		}

		// Check for inputAttr params
		if (isset($params['inputAttr'])) {
			$this->inputAttr = array_merge(
				$this->inputAttr,
				(array) $params['inputAttr']
			);
		}

		// Check for inputs
		if (! isset($params['inputs'])) {
			return;
		}

		// Make sure it's an array
		$inputs = (array) $params['inputs'];

		// Loop through inputs and set
		foreach ($inputs as $key => $val) {
			$this->inputs[] = new SimpleMailer_FormInputService($key, $val);
		}
	}

	/**
	 * Process from post
	 */
	public function processFromPost()
	{
		// Loop through inputs and tell them to validate
		foreach ($this->inputs as $input) {
			// Process input from post and validate
			$input->processFromPost();

			// Check for error
			if ($input->hasError) {
				$this->hasErrors = true;
			}
		}
	}

	/**
	 * Return error json
	 */
	public function getJsonArray()
	{
		// Start json array
		$jsonArray = array(
			'hasErrors' => $this->hasErrors,
			'errorClass' => $this->errorClass,
			'inputs' => array()
		);

		// Loop through inputs
		foreach ($this->inputs as $input) {
			$jsonArray['inputs'][$input->name] = $input->getJsonArray();
		}

		// Return the json array
		return $jsonArray;
	}
}
