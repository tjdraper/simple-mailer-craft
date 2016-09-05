<?php

namespace Craft;

class SimpleMailerPlugin extends BasePlugin
{
	private $addonJson;

	/**
	 * Plugin constructor
	 */
	public function __construct()
	{
		$this->addonJson = json_decode(
			file_get_contents(CRAFT_PLUGINS_PATH . 'simplemailer/addon.json')
		);
	}

	/**
	 * Get the plugin name
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('SimpleMailerName');
	}

	/**
	 * Get the plugin Description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return Craft::t('SimpleMailerDescription');
	}

	/**
	 * Get the plugin version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->addonJson->version;
	}

	/**
	 * Get the plugin schema version
	 *
	 * @return string
	 */
	public function getSchemaVersion()
	{
		return $this->addonJson->schemaVersion;
	}

	/**
	 * Get release feed url
	 *
	 * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return $this->addonJson->releaseFeedUrl;
	}

	/**
	 * Get the plugin developer
	 *
	 * @return string
	 */
	public function getDeveloper()
	{
		return $this->addonJson->author;
	}

	/**
	 * Get the plugin developer URL
	 *
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return $this->addonJson->authorUrl;
	}

	/**
	 * Get the plugin developer URL
	 *
	 * @return string
	 */
	public function getDocumentationUrl()
	{
		return $this->addonJson->docsUrl;
	}
}
