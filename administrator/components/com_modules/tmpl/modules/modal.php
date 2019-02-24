<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

if (Factory::getApplication()->isClient('site'))
{
	Session::checkToken('get') or die(Text::_('JINVALID_TOKEN'));
}

// Load needed scripts
HTMLHelper::_('behavior.core');
HTMLHelper::_('bootstrap.popover', '.hasPopover', array('placement' => 'bottom'));

// Scripts for the modules xtd-button
HTMLHelper::_('script', 'com_modules/admin-modules-modal.min.js', array('version' => 'auto', 'relative' => true));

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$editor    = Factory::getApplication()->input->get('editor', '', 'cmd');
$link      = 'index.php?option=com_modules&view=modules&layout=modal&tmpl=component&' . JSession::getFormToken() . '=1';

if (!empty($editor))
{
	$link .= '&editor=' . $editor;
}
?>
<div class="container-popup">

	<form action="<?php echo Route::_($link); ?>" method="post" name="adminForm" id="adminForm">

		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<?php if ($this->total > 0) : ?>
		<table class="table" id="moduleList">
			<caption id="captionTable" class="sr-only">
				<?php echo Text::_('COM_MODULES_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
			</caption>
			<thead>
				<tr>
					<th scope="col" style="width:1%" class="text-center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" class="title">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:15%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_MODULES_HEADING_POSITION', 'a.position', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:10%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:10%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_MODULES_HEADING_PAGES', 'pages', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:10%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'ag.title', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:10%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'l.title', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:1%" class="d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$iconStates = array(
					-2 => 'icon-trash',
					0  => 'icon-unpublish',
					1  => 'icon-publish',
					2  => 'icon-archive',
				);
				foreach ($this->items as $i => $item) :
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="text-center">
						<span class="<?php echo $iconStates[$this->escape($item->published)]; ?>" aria-hidden="true"></span>
					</td>
					<th scope="row" class="has-context">
						<a class="js-module-insert btn btn-sm btn-block btn-success" href="#" data-module="<?php echo $this->escape($item->module); ?>" data-title="<?php echo $this->escape($item->title); ?>" data-editor="<?php echo $this->escape($editor); ?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</td>
					<td class="small d-none d-md-table-cell">
						<?php if ($item->position) : ?>
						<a class="js-position-insert btn btn-sm btn-block btn-warning" href="#" data-position="<?php echo $this->escape($item->position); ?>" data-editor="<?php echo $this->escape($editor); ?>"><?php echo $this->escape($item->position); ?></a>
						<?php else : ?>
						<span class="btn btn-sm btn-block btn-secondary"><?php echo Text::_('JNONE'); ?></span>
						<?php endif; ?>
					</td>
					<td class="small d-none d-md-table-cell">
						<?php echo $item->name; ?>
					</td>
					<td class="small d-none d-md-table-cell">
						<?php echo $item->pages; ?>
					</td>
					<td class="small d-none d-md-table-cell">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="small d-none d-md-table-cell">
						<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
					</td>
					<td class="d-none d-md-table-cell">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php // load the pagination. ?>
		<?php echo $this->pagination->getListFooter(); ?>

		<?php endif; ?>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
        <input type="hidden" name="editor" value="<?php echo $editor; ?>" />
		<?php echo HTMLHelper::_('form.token'); ?>

	</form>
</div>
