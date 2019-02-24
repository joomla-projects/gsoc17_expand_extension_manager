<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.core');
HTMLHelper::_('behavior.tabstate');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$this->useCoreUI = true;

Text::script('ERROR');
Text::script('JGLOBAL_VALIDATION_FORM_FAILED');

Factory::getDocument()->addScriptOptions('menu-item', ['itemId' => (int) $this->item->id]);
HTMLHelper::_('script', 'com_menus/admin-item-edit.min.js', ['version' => 'auto', 'relative' => true]);

// Ajax for parent items
$script = "
jQuery(document).ready(function ($){
	// Menu type Login Form specific
	$('#item-form').on('submit', function() {
		if ($('#jform_params_login_redirect_url') && $('#jform_params_logout_redirect_url')) {
			// Login
			if ($('#jform_params_login_redirect_url').closest('.control-group').css('display') === 'block') {
				$('#jform_params_login_redirect_menuitem_id').val('');
			}
			if ($('#jform_params_login_redirect_menuitem_name').closest('.control-group').css('display') === 'block') {
				$('#jform_params_login_redirect_url').val('');

			}

			// Logout
			if ($('#jform_params_logout_redirect_url').closest('.control-group').css('display') === 'block') {
				$('#jform_params_logout_redirect_menuitem_id').val('');
			}
			if ($('#jform_params_logout_redirect_menuitem_id').closest('.control-group').css('display') === 'block') {
				$('#jform_params_logout_redirect_url').val('');
			}
		}
	});
});
";

$assoc = Associations::isEnabled();
$input = Factory::getApplication()->input;

// In case of modal
$isModal  = $input->get('layout') == 'modal' ? true : false;
$layout   = $isModal ? 'modal' : 'edit';
$tmpl     = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
$clientId = $this->state->get('item.client_id', 0);
$lang     = Factory::getLanguage()->getTag();

// Load mod_menu.ini file when client is administrator
if ($clientId === 1)
{
	Factory::getLanguage()->load('mod_menu', JPATH_ADMINISTRATOR, null, false, true);
}
?>
<form action="<?php echo Route::_('index.php?option=com_menus&view=item&client_id=' . $clientId . '&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<?php // Add the translation of the menu item title when client is administrator ?>
	<?php if ($clientId === 1 && $this->item->id != 0) : ?>
		<div class="form-inline form-inline-header">
			<div class="control-group">
				<div class="control-label">
					<label for="menus_title_translation"><?php echo Text::sprintf('COM_MENUS_TITLE_TRANSLATION', $lang); ?></label>
				</div>
				<div class="controls">
					<input id="menus_title_translation" class="form-control" value="<?php echo Text::_($this->item->title); ?>" readonly="readonly" type="text">
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div>

		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_MENUS_ITEM_DETAILS')); ?>
		<div class="row">
			<div class="col-md-9">
				<?php
				echo $this->form->renderField('type');

				if ($this->item->type == 'alias')
				{
					echo $this->form->renderFieldset('aliasoptions');
				}

				if ($this->item->type == 'separator')
				{
					echo $this->form->renderField('text_separator', 'params');
				}

				echo $this->form->renderFieldset('request');

				if ($this->item->type == 'url')
				{
					$this->form->setFieldAttribute('link', 'readonly', 'false');
					$this->form->setFieldAttribute('link', 'required', 'true');
				}

				echo $this->form->renderField('link');

				echo $this->form->renderField('browserNav');
				echo $this->form->renderField('template_style_id');

				if (!$isModal && $this->item->type == 'container')
				{
					echo $this->loadTemplate('container');
				}
				?>
			</div>
			<div class="col-md-3">
				<div class="card card-light">
					<div class="card-body">
						<?php
						// Set main fields.
						$this->fields = array(
							'id',
							'client_id',
							'menutype',
							'parent_id',
							'menuordering',
							'published',
							'home',
							'access',
							'language',
							'note',
						);

						if ($this->item->type != 'component')
						{
							$this->fields = array_diff($this->fields, array('home'));
						}

						echo LayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php
		$this->fieldsets = array();
		$this->ignore_fieldsets = array('aliasoptions', 'request', 'item_associations');
		echo LayoutHelper::render('joomla.edit.params', $this);
		?>

		<?php if (!$isModal && $assoc && $this->state->get('item.client_id') != 1) : ?>
			<?php if ($this->item->type !== 'alias' && $this->item->type !== 'url'
				&& $this->item->type !== 'separator' && $this->item->type !== 'heading') : ?>
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
				<?php echo $this->loadTemplate('associations'); ?>
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<?php endif; ?>
		<?php elseif ($isModal && $assoc && $this->state->get('item.client_id') != 1) : ?>
			<div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
		<?php endif; ?>

		<?php if (!empty($this->modules)) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'modules', Text::_('COM_MENUS_ITEM_MODULE_ASSIGNMENT')); ?>
			<?php echo $this->loadTemplate('modules'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="">
	<input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
	<?php echo $this->form->getInput('component_id'); ?>
	<?php echo HTMLHelper::_('form.token'); ?>
	<input type="hidden" id="fieldtype" name="fieldtype" value="">
</form>
