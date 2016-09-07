# Simple Mailer for Craft CMS

Simple Mailer makes it easy to set up simple forms and have them sent via the mail settings specified in Craft.

## Usage

### Config

Each form must be set up via config. In the Craft config directory, create a file called `simplemailer.php`. This file should return an array with the key `forms` which should also be an array.

```
return array(
    'forms' => array()
);
```

Each key in the `forms` array represents the handle with which you will refer to your form in your templates. Each key should return an array. Below is an example. Pretty much all keys are optional — although your form would be pretty useless without inputs. If you plan to let SimpleMailer build your form for you, you’ll probably want to specify a lot of options for the inputs and classes. If you’re building your own form, you may not have to specify as much.

```
return array(
	'forms' => array(
		'basicContactForm' => array(
			'to' => array(
				'me@domain.com',
				'another@domain.com'
			),
			'subject' => 'My Contact Form',
			'subjectInput' => 'subject',
			'fromNameInput' => 'name',
			'fromEmailInput' => 'email',
			'messageSentMessage' => 'Your message was sent successfully',
			'messageSentWrapperClass' => 'message-sent',
			'errorClass' => 'my-error-class',
			'formAttr' => array(
				'id' => 'myForm',
				'class' => 'my-form'
			),
			'labelAttr' => array(
				'class' => 'label'
			),
			'fieldsetAttr' => array(
				'class' => 'fieldset'
			),
			'labels' => false, // Not a good idea, but disables the output of labels
			'inputAttr' => array(
				'class' => 'input'
			),
			'submitAttr' => array(
				'class' => 'submit',
				'value' => 'Send your message'
			),
			'errorWrapperClass' => 'contact-form__error-wrapper',
			'inputs' => array(
				'name' => array (
					'label' => 'Name',
					'type' => 'text',
					'required' => true,
					'attr' => array(
						'class' => 'my-class'
					),
					'labelAttr' => array(
						'class' => 'my-class'
					),
					'errorMessage' => '{{label}} is required',
					'errorWrapperClass' => 'my-wrapper-error-class'
				),
				'email' => array (
					'label' => 'Email Address',
					'type' => 'email',
					'required' => true,
				),
				'subject' => array(
					'label' => 'Subject',
					'type' => 'select',
					'values' => array(
						'Getting in touch' => 'Getting in touch',
						'Other' => 'Other'
					)
				),
				'message' => array(
					'label' => "What's on your mind?",
					'type' => 'textarea',
					'required' => true
				)
			)
		)
	)
);
```

### getForm

In your templates, you can use the pre-packaged template by using the following tag:

```
{{ craft.simpleMailer.getForm({
	form: 'basicContactForm'
})|raw }}
```

Please note because of the way Twig escapes tag output, you have to use the `raw` filter.

You can also override or set pretty much any value you can define in the form config like so:

```
{{ craft.simpleMailer.getForm({
	form: 'basicContactForm',
	formAttr: {
		class: 'myClass myClass--thing',
		id: 'whatever',
		novalidate: '' {# Empty string will output the key only #}
	},
	labelAttr: {
		class: 'myLabels'
	},
	fieldsetAttr: {
		class: 'myFieldsets'
	},
	inputAttr: {
		class: 'myInputs'
	},
	submitAttr: {
		id: 'myId'
	},
	textAttr: {
		class: 'myTextInputsClass'
	},
	messageAttr: {
		class: 'specialClassForMessageInput'
	}
	labels: false
})|raw }}
```

### Build your own form

#### getFormConfig

`craft.simpleMailer.getFormConfig('basicContactForm')` returns an array of the form config.

#### getFormOpen

`{{ craft.simpleMailer.getFormOpen('basicContactForm', {class: 'myClass'})|raw }}` outputs an opening form tag and required hidden inputs.

#### getFormClose

`{{ craft.simpleMailer.getFormClose()|raw }}` closes the form tag.

#### Example

See the template in `simplemailer/templates/form.twig` for an idea of how to build your own form.
