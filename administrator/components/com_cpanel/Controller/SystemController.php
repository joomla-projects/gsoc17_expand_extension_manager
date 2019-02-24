<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Cpanel\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Updater\Updater;

/**
 * Cpanel System Controller
 *
 * @since  __DEPLOY_VERSION__
 */
class SystemController extends BaseController
{
	/**
	 * Ajax target point for reading the specific information
	 *
	 * @throws \Exception
	 *
	 * @return void
	 * 
	 * @since  __DEPLOY_VERSION__
	 */
	public function loadSystemInfo()
	{
		$type = $this->input->get('type');

		$count = 0;

		switch ($type)
		{
			case 'postinstall':
				$count = $this->countItems('com_postinstall', 'Messages');
				break;

			case 'installationwarnings':
				$count = $this->countItems('com_installer', 'Warnings');
				break;

			case 'checkins':
				$count = $this->countItems('com_checkin', 'Checkin');
				break;

			case 'databaseupdate':
				$count = $this->countDatabaseUpdates();
				break;

			case 'systemupdate':
				$count = $this->countSystemUpdates();
				break;

			case 'extensionupdate':
				$count = $this->countExtensionUpdates();
				break;

			case 'extensiondiscover':
				$count = $this->countExtensionDiscover();
				break;

			default:
				/**
				 * @TODO: Plugin event to allow custom sections to be added (see SystemModel)
				 */
				throw new \Exception(Text::_('COM_CPANEL_ERROR_DASHBOARD_TYPE_NOT_SUPPORTED'));
		}

		echo new JsonResponse($count);
	}

	/**
	 * Returns the existing database errors of the table structur
	 *
	 * @return integer  Number of database table errors
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
	 */
	protected function countDatabaseUpdates()
	{
		if (!Factory::getUser()->authorise('core.manage', 'com_installer'))
		{
			throw new \Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}

		$boot      = Factory::getApplication()->bootComponent('com_installer');
		$model     = $boot->getMVCFactory()->createModel('Database', 'Administrator', ['ignore_request' => true]);

		$changeSet = $model->getItems();

		$changeSetCount = 0;

		foreach ($changeSet as $item)
		{
			$changeSetCount += $item['errorsCount'];
		}

		return $changeSetCount;
	}

	/**
	 * Returns the version number of the latest update or empty string if system is uptodate
	 *
	 * @return string  The version number or empty string
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
	 */
	protected function countSystemUpdates()
	{
		if (!Factory::getUser()->authorise('core.manage', 'com_joomlaupdate'))
		{
			throw new \Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}

		$boot    = Factory::getApplication()->bootComponent('com_joomlaupdate');
		$model   = $boot->getMVCFactory()->createModel('Update', 'Administrator', ['ignore_request' => true]);

		$model->refreshUpdates(true);

		$joomlaUpdate = $model->getUpdateInformation();

		$hasUpdate = $joomlaUpdate['hasUpdate'] ? '&#x200E;' . $joomlaUpdate['latest'] : '';

		return $hasUpdate;
	}

	/**
	 * Returns the number of outdates extensions installed in the system
	 *
	 * @return integer  Number of available updates
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
	 */
	protected function countExtensionUpdates()
	{
		if (!Factory::getUser()->authorise('core.manage', 'com_installer'))
		{
			throw new \Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}

		$boot    = Factory::getApplication()->bootComponent('com_installer');
		$model   = $boot->getMVCFactory()->createModel('Update', 'Administrator', ['ignore_request' => true]);

		$model->findUpdates();

		$items   = count($model->getItems());

		return $items;
	}

	/**
	 * Returns the number of available extensions for installation
	 *
	 * @return integer  Number of available updates
	 *
	 * @throws \Exception
	 */
	protected function countExtensionDiscover()
	{
		if (!Factory::getUser()->authorise('core.manage', 'com_installer'))
		{
			throw new \Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}

		$boot    = Factory::getApplication()->bootComponent('com_installer');
		$model   = $boot->getMVCFactory()->createModel('Discover', 'Administrator', ['ignore_request' => true]);

		$model->discover();

		$items   = count($model->getItems());

		return $items;
	}

	/**
	 * Generic getItems counter for different calls
	 *
	 * @param   type  $extension  The extension to check and authorise for
	 * @param   type  $model      The Model to load
	 *
	 * @return integer The number of items
	 *
	 * @throws \Exception
	 * @since  __DEPLOY_VERSION__
	 */
	protected function countItems($extension, $modelname)
	{
		if (!Factory::getUser()->authorise('core.manage', $extension))
		{
			throw new \Exception(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'));
		}

		$boot    = Factory::getApplication()->bootComponent($extension);
		$model   = $boot->getMVCFactory()->createModel($modelname, 'Administrator', ['ignore_request' => true]);

		$items   = count($model->getItems());

		return $items;
	}
}
