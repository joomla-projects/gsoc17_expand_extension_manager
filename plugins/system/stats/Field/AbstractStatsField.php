<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.stats
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\System\Stats\Field;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

/**
 * Base field for the Stats Plugin.
 *
 * @since  3.5
 */
abstract class AbstractStatsField extends FormField
{
	/**
	 * Get the layouts paths
	 *
	 * @return  array
	 *
	 * @since   3.5
	 */
	protected function getLayoutPaths()
	{
		$template = Factory::getApplication()->getTemplate();

		return array(
			JPATH_ADMINISTRATOR . '/templates/' . $template . '/html/layouts/plugins/system/stats',
			dirname(__DIR__) . '/layouts',
			JPATH_SITE . '/layouts'
		);
	}
}
