<?php

namespace Craft;

class SimpleMailer_FormConfigModel extends BaseModel
{
	/**
	 * Define model attributes
	 *
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'processed' => AttributeType::Bool,
			'hasErrors' => AttributeType::Bool,
			'to' => AttributeType::Mixed,
			'subject' => AttributeType::String,
			'subjectInput' => AttributeType::String,
			'fromNameInput' => AttributeType::String,
			'fromEmailInput' => AttributeType::String,
			'errorClass' => AttributeType::String,
			'errorWrapperClass' => AttributeType::String,
			'form' => AttributeType::String,
			'formAttr' => AttributeType::Mixed,
			'labelAttr' => AttributeType::Mixed,
			'fieldsetAttr' => AttributeType::Mixed,
			'labels' => AttributeType::Bool,
			'inputAttr' => AttributeType::Mixed,
			'submitAttr' => AttributeType::Mixed,
			'inputs' => AttributeType::Mixed,
			'messageSentMessage' => AttributeType::String,
			'messageSentWrapperClass' => AttributeType::String
		);
	}

	/**
	 * Constructor
	 *
	 * @param string $form
	 * @param array $overrideParams
	 */
	public function __construct($form, $overrideParams = array())
	{
		// Run the parent constructor
		parent::__construct();

		// Set arrays since models do not have an array type
		$this->to = array();
		$this->formAttr = array();
		$this->labelAttr = array();
		$this->fieldsetAttr = array();
		$this->inputAttr = array();
		$this->submitAttr = array();
		$this->inputs = array();

		// Set defaults
		$this->labels = true;
		$this->subject = 'Simple Mailer Form';
		$this->errorClass = 'error';
		$this->errorWrapperClass = 'error-wrapper';
		$this->messageSentMessage = Craft::t('MessageSentMessage');
		$this->messageSentWrapperClass = 'message-sent';

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
		// Check for to array
		if (isset($params['to'])) {
			$this->to = (array) $params['to'];
		}

		// Check for from name input
		if (isset($params['subject'])) {
			$this->subject = (string) $params['subject'];
		}

		// Check for from name input
		if (isset($params['subjectInput'])) {
			$this->subjectInput = (string) $params['subjectInput'];
		}

		// Check for from name input
		if (isset($params['fromNameInput'])) {
			$this->fromNameInput = (string) $params['fromNameInput'];
		}

		// Check for from email input
		if (isset($params['fromEmailInput'])) {
			$this->fromEmailInput = (string) $params['fromEmailInput'];
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

		// Check for from name input
		if (isset($params['messageSentWrapperClass'])) {
			$this->messageSentWrapperClass = (string) $params['messageSentWrapperClass'];
		}

		// Check for from name input
		if (isset($params['errorClass'])) {
			$this->errorClass = (string) $params['errorClass'];
		}

		// Check for from name input
		if (isset($params['errorWrapperClass'])) {
			$this->errorWrapperClass = (string) $params['errorWrapperClass'];
		}

		// Check for inputs
		if (! isset($params['inputs'])) {
			return;
		}

		// Make sure it's an array
		$inputs = (array) $params['inputs'];

		// Inputs array
		$inputArray = array();

		// Loop through inputs and set
		foreach ($inputs as $key => $val) {
			$inputArray[$key] = new SimpleMailer_FormInputModel($key, $val);
		}

		$this->inputs = $inputArray;
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

		// Set processed to true
		$this->processed = true;
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
