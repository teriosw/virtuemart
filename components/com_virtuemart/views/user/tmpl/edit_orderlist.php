<?php
/**
*
* User details, Orderlist
*
* @package	VirtueMart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 2302 2010-02-07 19:57:37Z rolandd $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>

<div id="editcell">
	<table class="adminlist">
	<thead>
	<tr>
		<th>
			<?php echo JText::_('VM_ORDER_LIST_ID'); ?>
		</th>
		<th>
			<?php echo JText::_('VM_ORDER_LIST_CDATE'); ?>
		</th>
		<th>
			<?php echo JText::_('VM_ORDER_LIST_MDATE'); ?>
		</th>
		<th>
			<?php echo JText::_('VM_ORDER_LIST_STATUS'); ?>
		</th>
		<th>
			<?php echo JText::_('VM_ORDER_LIST_TOTAL'); ?>
		</th>
	</thead>
	<?php
		$k = 0;
		for ($i = 1, $n = count($this->orderlist); $i <= $n; $i++) {
			$row =& $this->orderlist[$i];
			$editlink = JROUTE::_('index.php?option=com_virtuemart&view=orders&task=details&order_id=' . $row->order_id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="left">
					<a href="<?php echo $editlink; ?>"><?php echo $row->order_id; ?></a>
				</td>
				<td align="left">
					<?php echo JHTML::_('date', $row->cdate); ?>
				</td>
				<td align="left">
					<?php echo JHTML::_('date', $row->mdate); ?>
				</td>
				<td align="left">
					<?php echo ShopFunctions::getOrderStatusName($row->order_status); ?>
				</td>
				<td align="left">
					<?php echo $this->currency->getFullValue($row->order_total); ?>
				</td>
			</tr>
	<?php
			$k = 1 - $k;
		}
	?>
	</table>
</div>
