<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Layout variables
 * ---------------------
 * None
 */

?>
<label id="batch-workflowstage-lbl" for="batch-workflowstage-id" class="modalTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'JLIB_HTML_BATCH_WORKFLOW_STAGE_LABEL', 'JLIB_HTML_BATCH_WORKFLOW_STAGE_LABEL_DESC'); ?>">
	<?php echo Text::_('JLIB_HTML_BATCH_WORKFLOW_STAGE_LABEL'); ?>
</label>

<?php

$attr = array(
	'id'        => 'batch-workflowstage-id',
	'group.label' => 'text',
	'group.items' => null,
	'list.attr' => [
		'class' => 'custom-select'
	]
);

$groups = HTMLHelper::_('workflowstage.existing', array('title' => Text::_('JLIB_HTML_BATCH_WORKFLOW_STAGE_NOCHANGE')));

echo HTMLHelper::_('select.groupedlist', $groups, 'batch[workflowstage_id]', $attr);
