<?php

namespace Craft;

class SimpleMailer_MailService extends BaseApplicationComponent
{
	/**
	 * Send a form
	 *
	 * @param SimpleMailer_FormConfigModel $formConfig
	 */
	public function sendForm($formConfig)
	{
		// Set up the email model
		$email = new EmailModel();

		// Specify who from
		if ($formConfig->fromNameInput) {
			$email->fromName = $formConfig->inputs[$formConfig->fromNameInput]->value;
		}

		// Reply to sender
		if ($formConfig->fromEmailInput) {
			$email->replyTo = $formConfig->inputs[$formConfig->fromEmailInput]->value;
		}

		// Specify a subject
		$email->subject = $formConfig->subject;

		// Check for subject input
		if ($formConfig->subjectInput) {
			$email->subject = $formConfig->inputs[$formConfig->subjectInput]->value;
		}

		// Start email body
		$email->body = $email->htmlBody = '';

		// Loop through inputs
		foreach ($formConfig->inputs as $input) {
			// Get the input value
			$value = $input->value;

			// Check if this is a textarea and add line breaks if so
			if ($input->type === 'textarea') {
				$value = nl2br($value);
			}

			// Add input to email body
			$email->body .= "{$input->label}: {$input->value}\n\n";
			$email->htmlBody .= "<strong>{$input->label}:</strong> {$value}<br>";
		}

		// Loop through to emails and send
		foreach ($formConfig->to as $emailAddress) {
			// Set the to email
			$email->toEmail = $emailAddress;

			// Send the email
			craft()->email->sendEmail($email);
		}
	}
}
