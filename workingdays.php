<?php
/*
 * @package   Joomla - Working days
 * @version    1.0.0
 * @author    Artem Vasilev - webmasterskaya.xyz
 * @copyright  Copyright (c) 2018 - 2022 Webmasterskaya. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link       https://webmasterskaya.xyz/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Webmasterskaya\ProductionCalendar\Calendar as Calendar;

/**
 * Workingdays plugin.
 *
 * @package   workingdays
 * @since     1.0.0
 */
class plgSystemWorkingdays extends CMSPlugin
{
	/**
	 * Application object
	 *
	 * @var    CMSApplication
	 * @since  1.0.0
	 */
	protected $app;

	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0.0
	 */
	protected $db;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * onAfterCompileHead.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onBeforeCompileHead()
	{
		JLoader::registerNamespace(
			'Webmasterskaya\\ProductionCalendar',
			JPATH_LIBRARIES . '/lib_production_calendar/ProductionCalendar',
			false,
			false,
			'psr4'
		);

		if (!class_exists('Webmasterskaya\\ProductionCalendar\\Calendar'))
		{
			return;
		}

		if (!Factory::getApplication()->isClient('site'))
		{
			return;
		}

		$today = new Date('TODAY');

		if (Calendar::isPreHoliday($today))
		{
			$message = Text::_(
				$this->params->get(
					'pre_holiday_message',
					'PLG_SYSTEM_WORKINGDAYS_PARAMS_PRE_HOLIDAY_MESSAGE'
				)
			);
		}

		if (!Calendar::isWorking($today))
		{
			$message = Text::sprintf(
				$this->params->get(
					'not_working_day_message',
					'PLG_SYSTEM_WORKINGDAYS_PARAMS_NOT_WORKING_DAY_MESSAGE'
				),
				Calendar::find($today)->working()->format(
					Text::_('DATE_FORMAT_LC1')
				)
			);
		}

		if (isset($message))
		{
			$document = Factory::getDocument();
			$document->addScriptOptions('workingdays', ['message' => $message]);
		}
	}
}
