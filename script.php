<?php
/*
 * @package   Joomla - Working days
 * @version    1.0.0
 * @author    Artem Vasilev - webmasterskaya.xyz
 * @copyright  Copyright (c) 2018 - 2022 Webmasterskaya. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link       https://webmasterskaya.xyz/
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Adapter\PackageAdapter;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

/**
 * Workingdays script file.
 *
 * @package   workingdays
 * @since     1.0.0
 */
class plgSystemWorkingdaysInstallerScript
{
	/**
	 * Minimum PHP version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumPhp = '7.1';

	/**
	 * Minimum Joomla version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumJoomla = '3.9.0';


	/**
	 * Runs right before any installation action.
	 *
	 * @param   string                           $type    Type of PostFlight action.
	 * @param   InstallerAdapter|PackageAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	function preflight($type, $parent)
	{
		// Check compatible
		if (!$this->checkCompatible('PLG_SYSTEM_WORKINGDAYS_'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to check compatible.
	 *
	 * @param   string  $prefix  Language constants prefix.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function checkCompatible($prefix = null)
	{
		// Check old Joomla
		if (!class_exists('Joomla\CMS\Version'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					$prefix . 'ERROR_COMPATIBLE_JOOMLA',
					$this->minimumJoomla
				), 'error'
			);

			return false;
		}

		$app = Factory::getApplication();

		// Check PHP
		if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
		{
			$app->enqueueMessage(
				Text::sprintf(
					$prefix . 'ERROR_COMPATIBLE_PHP', $this->minimumPhp
				),
				'error'
			);

			return false;
		}

		// Check joomla version
		if (!(new Version())->isCompatible($this->minimumJoomla))
		{
			$app->enqueueMessage(
				Text::sprintf(
					$prefix . 'ERROR_COMPATIBLE_JOOMLA', $this->minimumJoomla
				),
				'error'
			);

			return false;
		}

		return true;
	}

	/**
	 * Runs right after any installation action.
	 *
	 * @param   string            $type    Type of PostFlight action. Possible values are:
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	function postflight($type, $parent)
	{
		// Enable plugin
		if ($type == 'install')
		{
			$this->enablePlugin($parent);
		}

		// Refresh media
		if ($type === 'update')
		{
			(new Version())->refreshMediaVersion();
		}

		return true;
	}

	/**
	 * Enable plugin after installation.
	 *
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function enablePlugin($parent)
	{
		// Prepare plugin object
		$plugin          = new stdClass();
		$plugin->type    = 'plugin';
		$plugin->element = $parent->getElement();
		$plugin->folder  = (string) $parent->getParent()->manifest->attributes(
		)['group'];
		$plugin->enabled = 1;

		// Update record
		Factory::getDbo()->updateObject(
			'#__extensions', $plugin, array('type', 'element', 'folder')
		);
	}

	/**
	 * This method is called after extension is uninstalled.
	 *
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function uninstall($parent)
	{
	}


}
