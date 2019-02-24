<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('script', 'com_content/admin-articles-default-stage-footer.js', ['version' => 'auto', 'relative' => true]);
?>
<a class="btn btn-secondary" type="button" data-dismiss="modal">
	<?php echo Text::_('JCANCEL'); ?>
</a>
<button id="stage-submit-button-id" class="btn btn-success" type="button" data-submit-task="">
	<?php echo Text::_('JGLOBAL_STAGE_PROCESS'); ?>
</button>
