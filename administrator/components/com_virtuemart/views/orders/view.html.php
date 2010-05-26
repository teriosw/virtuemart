<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewOrders extends JView {

	function display($tpl = null) {

		$mainframe = JFactory::getApplication();
		$option = JRequest::getVar('option');
		$lists = array();

		/* Load helpers */
		$this->loadHelper('adminMenu');
		$this->loadHelper('currencydisplay');
		$this->loadHelper('shopFunctions');
		$this->loadHelper('html');

		$curTask = JRequest::getVar('task');
		if ($curTask == 'edit') {

			// Load addl models
			$userFieldsModel = $this->getModel('userfields');

			/* Get the data */
			$order = $this->get('Order');
			$orderbt = $order['details']['BT'];
			$orderst = (array_key_exists('ST', $order['details'])) ? $order['details']['ST'] : $orderbt;

			$_userFields = $userFieldsModel->getUserFields(
					 'registration'
					, array('captcha' => true, 'delimiters' => true) // Ignore these types
					, array('delimiter_userinfo','user_is_vendor' ,'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
			);
			$userfields = $userFieldsModel->getUserFieldsByUser(
					 $_userFields
					,$orderbt
			);
			$_userFields = $userFieldsModel->getUserFields(
					 'shipping'
					, array() // Default switches
					, array('delimiter_userinfo', 'username', 'email', 'password', 'password2', 'agreed', 'address_type') // Skips
			);
			$shippingfields = $userFieldsModel->getUserFieldsByUser(
					 $_userFields
					,$orderst
			);

			// Create an array to allow orderlinestatuses to be translated
			// We'll probably want to put this somewhere in ShopFunctions...
			$_orderStats = $this->get('OrderStatusList');
			$_orderStatusList = array();
			foreach ($_orderStats as $_ordStat) {
				$_orderStatusList[$_ordStat->value] = $_ordStat->text;
			}
			
			/* Assign the data */
			$this->assignRef('order', $order);
			$this->assignRef('userfields', $userfields);
			$this->assignRef('shippingfields', $shippingfields);
			$this->assignRef('orderstatuslist', $_orderStatusList);
			$this->assignRef('orderbt', $orderbt);
			$this->assignRef('orderst', $orderst);

			JHTML::_('behavior.modal');
			$this->setLayout('orders_edit');

			/* Toolbar */
			JToolBarHelper::title(JText::_( 'VM_ORDER_EDIT_LBL' ), 'vm_orders_48');
			JToolBarHelper::cancel();
		}
		else if ($curTask == 'editOrderStatus') {
			/* Set the layout */
			$this->setLayout('orders_editstatus');

			/* Get the data */
			$order = $this->get('Order');

			/* Get order statuses */
			$orderstatuses = $this->get('OrderStatusList');
			$this->assignRef('orderstatuses', $orderstatuses);
			$this->assignRef('order_id', $order['details']['BT']->order_id);
			$this->assignRef('cur_order_status', $order['details']['BT']->order_status);
			$_lo = 0; // Use a var; must be passed by reference
			$this->assignRef('line_only', $_lo);
					}
		else if ($curTask == 'editOrderItem') {
			$this->loadHelper('calculationHelper');

			/* Get order statuses */
			$orderstatuses = $this->get('OrderStatusList');
			$this->assignRef('orderstatuses', $orderstatuses);

			$model = $this->getModel();
			$orderId = JRequest::getVar('orderId', '');
			$orderLineItem = JRequest::getVar('orderLineId', '');
			$this->assignRef('order_id', $orderId);
			$this->assignRef('order_item_id', $orderLineItem);
			
			$orderItem = $model->getOrderLineDetails($orderId, $orderLineItem);
			$this->assignRef('orderitem', $orderItem);
		}
		else if ($curTask == 'updateOrderItemStatus') {
			$this->setLayout('orders_editstatus');

			/* Get order statuses */
			$orderstatuses = $this->get('OrderStatusList');
			$this->assignRef('orderstatuses', $orderstatuses);

			$model = $this->getModel();
			$orderId = JRequest::getVar('orderId', '');
			$orderLineItem = JRequest::getVar('orderLineId', '');
			$this->assignRef('order_id', $orderId);
			$this->assignRef('order_item_id', $orderLineItem);

			$orderItem = $model->getOrderLineDetails($orderId, $orderLineItem);
			$this->assignRef('orderitem', $orderItem);
			// Following is here for syntactical reasons only (allows us to reuse the same template) 
			$this->assignRef('cur_order_status', $orderItem->order_status);
			$_lo = 1; // Use a var; must be passed by reference
			$this->assignRef('line_only', $_lo);
		}
		else {
			$this->setLayout('orders');

			/* Get the data */
			$orderslist = $this->get('OrdersList');

			/* Get order statuses */
			$orderstatuses = $this->get('OrderStatusList');
			$this->assignRef('orderstatuses', $orderstatuses);

			/* Apply currency */
			$currencydisplay = new CurrencyDisplay();
			foreach ($orderslist as $order_id => $order) {
				$order->order_total = $currencydisplay->getValue($order->order_total);
			}

			/* Get the pagination */
			$pagination = $this->get('Pagination');
			$lists['filter_order'] = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', '', 'cmd');
			$lists['filter_order_Dir'] = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', '', 'word');

			/* Toolbar */
			JToolBarHelper::title(JText::_( 'VM_ORDER_LIST_LBL' ), 'vm_orders_48');
			/*
			 * UpdateStatus removed from the toolbar; don't understand how this was intented to work but
			 * the order ID's aren't properly passed. Might be readded later; the controller needs to handle
			 * the arguments.
			 */
//			JToolBarHelper::save('editOrderStatus', JText::_('VM_UPDATE_STATUS'));
			JToolBarHelper::deleteListX();

			/* Assign the data */
			$this->assignRef('orderslist', $orderslist);
			$this->assignRef('pagination',	$pagination);
			$this->assignRef('lists',	$lists);
		}

		/* Assign general statuses */


		parent::display($tpl);
	}

}

