<?php

namespace Craft;

class SimpleMailer_FormInputModel extends BaseModel
{
	/**
	 * Define model attributes
	 *
	 * @return array
	 */
	protected function defineAttributes()
	{
		return array(
			'name' => AttributeType::String,
			'label' => AttributeType::String,
			'type' => AttributeType::String,
			'required' => AttributeType::Bool,
			'attr' => AttributeType::Mixed,
			'labelAttr' => AttributeType::Mixed,
			'errorMessage' => AttributeType::String,
			'hasError' => AttributeType::Bool,
			'errorClass' => AttributeType::String,
			'errorWrapperClass' => AttributeType::String,
			'value' => AttributeType::String,
			'values' => AttributeType::Mixed
		);
	}

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param array $config
	 */
	public function __construct($name, $config = array())
	{
		// Run the parent constructor
		parent::__construct();

		// Set arrays since models do not have an array type
		$this->attr = array();
		$this->labelAttr = array();
		$this->values = array();

		// Set defaults
		$this->type = 'text';
		$this->errorClass = 'error';

		// Set input name
		$this->name = $this->label = (string) $name;

		// Set config
		$this->setConfig($config);
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
	 * @param array $config
	 */
	public function setConfig($config = array())
	{
		// Check for label
		if (isset($config['label'])) {
			$this->label = (string) $config['label'];
		}

		// Check for type
		if (isset($config['type'])) {
			$this->type = (string) $config['type'];
		}

		// Check for required
		if (isset($config['required'])) {
			$this->required = (bool) $config['required'];

			if ($this->required) {

				if ($this->type === 'email') {
					$this->errorMessage = Craft::t('EmailMustBeValid');
				} else {
					$this->errorMessage = str_replace(
						'{{label}}',
						$this->label,
						Craft::t('RequiredInput')
					);
				}
			}
		}

		// Check for attr
		if (isset($config['attr'])) {
			$this->attr = (array) $config['attr'];
		}

		// Check for labelAttr
		if (isset($config['labelAttr'])) {
			$this->labelAttr = (array) $config['labelAttr'];
		}

		// Check for error message
		if (isset($config['errorMessage'])) {
			$this->errorMessage = str_replace(
				'{{label}}',
				$this->label,
				(string) $config['errorMessage']
			);
		}

		// Check for hasError
		if (isset($config['hasError'])) {
			$this->hasError = (bool) $config['hasError'];
		}

		// Check for errorClass
		if (isset($config['errorClass'])) {
			$this->errorClass = (string) $config['errorClass'];
		}

		// Check for errorWrapperClass
		if (isset($config['errorWrapperClass'])) {
			$this->errorWrapperClass = (string) $config['errorWrapperClass'];
		}

		// Check for value
		if (isset($config['value'])) {
			$this->value = (string) $config['value'];
		}

		// Check for values
		if (isset($config['values'])) {
			$this->values = (array) $config['values'];
		}
	}

	/**
	 * Validate post value
	 */
	public function processFromPost()
	{
		// Set the value from post
		$this->value = craft()->request->getPost($this->name);

		// Check if the input is required
		if ($this->required && ! $this->value) {
			$this->hasError = true;
		}

		// Check if this is an email input and there is a value
		if (
			$this->type === 'email' &&
			$this->value &&
			! filter_var($this->value, FILTER_VALIDATE_EMAIL)
		) {
			$this->hasError = true;
		}
	}

	/**
	 * Get json
	 */
	public function getJsonArray()
	{
		return array(
			'name' => $this->name,
			'errorMessage' => $this->errorMessage,
			'hasError' => $this->hasError,
			'errorClass' => $this->errorClass,
			'value' => $this->value
		);
	}
}
