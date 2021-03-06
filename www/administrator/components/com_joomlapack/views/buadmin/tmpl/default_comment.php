<?php
/**
 * @package JoomlaPack
 * @copyright Copyright (c)2006-2009 JoomlaPack Developers
 * @license GNU General Public License version 2, or later
 * @version $id$
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

?>
<div id="jpcontainer">
<table class="adminlist">
	<tr>
		<td><?php echo JText::_('STATS_LABEL_DESCRIPTION'); ?></td>
		<td><?php echo $this->record->description ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('STATS_LABEL_COMMENT'); ?></td>
		<td><?php echo $this->record->comment ?></td>
	</tr>
</table>
</div>
