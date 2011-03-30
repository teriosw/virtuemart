<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers
 * @author RolandD
 * @todo handle child products
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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// addon for joomla modal Box
JHTML::_( 'behavior.modal' );
/* Let's see if we found the product */
if (empty ( $this->product )) {
	echo JText::_( 'VM_PRODUCT_NOT_FOUND' );
	echo '<br /><br />  ' . $this->continue_link_html;
	return ;
}  ?>
<div class="productdetails-view">
	<?php // Product Navigation
	if (VmConfig::get ( 'product_navigation', 1 )) {
		?>
	<div class="marginbottom25">
		<?php
		if (! empty ( $this->product->neighbours ['previous'] )) {
			$prev_link = JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&product_id=' . $this->product->neighbours ['previous'] ['product_id'] . '&category_id=' . $this->product->category_id );
			echo JHTML::_ ( 'link', $prev_link, $this->product->neighbours ['previous'] ['product_name'], array ('class' => 'previous_page' ) );
		}
		if (! empty ( $this->product->neighbours ['next'] )) {
			$next_link = JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&product_id=' . $this->product->neighbours ['next'] ['product_id'] . '&category_id=' . $this->product->category_id );
			echo JHTML::_ ( 'link', $next_link, $this->product->neighbours ['next'] ['product_name'], array ('class' => 'next_page' ) );
		}
		echo '<br style="clear: both;" />';
		?>
	</div>
	<?php
	}
	?>

	<div>
		<div class="width30 floatleft center">

		<?php // Product Image
		echo $this->product->images[0]->displayMediaFull('class="product-image"'); //'class="modal"'
		?>
		</div>

		<div class="width70 floatleft">

			<div class="width15 floatright center paddingtop5">
			<?php // PDF - Print - Email Icon
			$link = 'index2.php?tmpl=component&option=com_virtuemart&view=productdetails&product_id='.$this->product->product_id; ?>
			<?php echo shopFunctionsF::PdfIcon( $link.'&output=pdf' ); ?>
			<?php echo shopFunctionsF::PrintIcon($link.'&print=1'); ?>
			<?php echo shopFunctionsF::EmailIcon($this->product->product_id); ?>
			<br style="clear:both;" />
			</div>

			<h1><?php echo $this->product->product_name.' '.$this->edit_link; ?></h1>

			<?php // Product Short Description
			if (!empty($this->product->product_s_desc)) { ?>
			<p class="short-description">
				<?php
				echo '<span class="bold">'.JText::_('VM_PRODUCT_DETAILS_SHORT_DESC_LBL').'</span><br />';
				echo $this->product->product_s_desc ?>
			</p>
			<?php } // Product Short Description END ?>

			<?php // Ask a question about this product
			$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&product_id='.$this->product->product_id.'&category_id='.$this->product->category_id.'&tmpl=component');
			 ?>
			<a class="ask-a-question modal" rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="<?php echo $url ?>"><?php echo JText::_('VM_PRODUCT_ENQUIRY_LBL') ?></a>
			<br style="clear:both;" />
			<div class="margintop8">

			<?php // TO DO in Multi-Vendor not needed at the moment and just would lead to confusion
/*			$link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&vendor_id='.$this->product->vendor_id);
			$text = JText::_('VM_VENDOR_FORM_INFO_LBL');
			echo '<span class="bold">'. JText::_('VM_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
*/ ?>
			<?php // Manufacturer of the Product
			$link = JRoute::_('index.php?option=com_virtuemart&view=manufacturer&manufacturer_id='.$this->product->manufacturer_id.'&tmpl=component');
			$text = $this->product->mf_name;
			/* Avoid JavaScript on PDF Output */
			if (strtolower(JRequest::getVar('output')) == "pdf") echo JHTML::_('link', $link, $text);
			else { ?>
			<?php echo '<span class="bold">'. JText::_('VM_PRODUCT_DETAILS_MANUFACTURER_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a>
			<?PHP } ?>

			</div>
		</div>

	<div class="clear"></div>
	</div>

	<div class="horizontal-separator margintop15 marginbottom15"></div>

	<div>
		<div class="width45 floatleft">

			<div class="product-price marginbottom12" id="productPrice<?php echo $this->product->product_id ?>">
		<?php
				/** @todo format price */
				if ($this->show_prices) {
					if( $this->product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
						echo "<strong>". JText::_('VM_CART_PRICE_PER_UNIT').' ('.$this->product->product_unit."):</strong>";
					} else echo "<strong>". JText::_('VM_CART_PRICE'). ": </strong>";


					if( $this->showBasePrice ){
						echo shopFunctionsF::createPriceDiv('basePrice','VM_PRODUCT_BASEPRICE',$this->product->prices);
						echo shopFunctionsF::createPriceDiv('basePriceVariant','VM_PRODUCT_BASEPRICE_VARIANT',$this->product->prices);
					}
					echo shopFunctionsF::createPriceDiv('variantModification','VM_PRODUCT_VARIANT_MOD',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('basePriceWithTax','VM_PRODUCT_BASEPRICE_WITHTAX',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('discountedPriceWithoutTax','VM_PRODUCT_DISCOUNTED_PRICE',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('salesPriceWithDiscount','VM_PRODUCT_SALESPRICE_WITH_DISCOUNT',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('salesPrice','VM_PRODUCT_SALESPRICE',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('priceWithoutTax','VM_PRODUCT_SALESPRICE_WITHOUT_TAX',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('discountAmount','VM_PRODUCT_DISCOUNT_AMOUNT',$this->product->prices);
					echo shopFunctionsF::createPriceDiv('taxAmount','VM_PRODUCT_TAX_AMOUNT',$this->product->prices);

				}
				?>
			</div>
		</div>

		<div class="width55 floatleft">

			<?php // Add To Cart Button
			if (VmConfig::get('use_as_catalogue') != '1') { ?>
			<div class="addtocart-area">
				<form  method="post" class="product" action="index.php" id="addtocartproduct<?php echo $this->product->product_id ?>">

					<?php // Product Variants Drop Down Box
					$variantExist=false;
					/* Show the variants */
					foreach ($this->product->variants as $variant_name => $variant) {

						$variantExist=true;
						$options = array();
						foreach ($variant as $name => $price) {
							if (!empty($price)){
								$name .= ' ('.$price.')';
							}
							$options[] = JHTML::_('select.option', $name, $name);
						}
						if (!empty($options)) {
							// genericlist have ID and whe want only class ( future use in jQuery, may be)
							$html    = '<select name="'. $variant_name .'" class="variant">';
							$html    .= JHTMLSelect::Options( $options, 'value', 'text', NULL, false );
							$html    .= '</select>';
						echo '<span class="variant-name">'.$variant_name.'</span> <span class="variant-dropdown">'.$html.'</span><br style="clear:left;" />';
						}
					} // Product Variants Drop Down Box END ?>

					<?php // Show the custom attributes
					foreach($this->product->customvariants as $ckey => $customvariant) { ?>
					<span class="custom-variant-name">
						<label for="<?php echo $customvariant ?>_field"><?php echo $customvariant ?></label>:
					</span> <span class="custom-variant-inputbox">
						<input type="text" class="custom-attribute" id="<?php echo $customvariant ?>_field" name="<?php echo $this->product->product_id.$customvariant; ?>" />
					</span>
					<br style="clear:left;" />
					<?php } // Show the custom attributes END ?>

					<?php // Display the quantity box ?>
					<!-- <label for="quantity<?php echo $this->product->product_id;?>" class="quantity_box"><?php echo JText::_('VM_CART_QUANTITY'); ?>: </label> -->
					<span class="quantity-box">
						<input type="text" class="quantity-input" name="quantity[]" value="1" />
					</span>
					<span class="quantity-controls">
						<input type="button" class="quantity-controls quantity-plus" />
						<input type="button" class="quantity-controls quantity-minus" />
					</span>
					<?php // Display the quantity box END ?>

					<?php // Add the button
					$button_lbl = JText::_('VM_CART_ADD_TO');
					$button_cls = ''; //$button_cls = 'addtocart_button';
					if (VmConfig::get('check_stock') == '1' && !$this->product->product_in_stock) {
						$button_lbl = JText::_('VM_CART_NOTIFY');
						$button_cls = 'notify_button';
					}
					?>

					<?php // Display the add to cart button ?>
					<span class="addtocart-button">
						<input type="submit" name="addtocart"  class="addtocart" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" />
					</span>
					<?php // Display the add to cart button END ?>
					<input type="hidden" class="pname" value="<?php echo $this->product->product_name ?>">
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
			<noscript><input type="hidden" name="task" value="add" /> </noscript>
					<input type="hidden" name="product_id[]" value="<?php echo $this->product->product_id ?>" />

					<?php /** @todo Handle the manufacturer view */ ?>
					<input type="hidden" name="manufacturer_id" value="<?php echo $this->product->manufacturer_id ?>" />
					<input type="hidden" name="category_id[]" value="<?php echo $this->product->category_id ?>" />
				</form>

			<div class="clear"></div>
			</div>
			<?php }  // Add To Cart Button END ?>
		</div>

	<div class="clear"></div>
	</div>

	<div class="horizontal-separator margintop15 marginbottom15"></div>

	<?php // Product Description
	if (!empty($this->product->product_desc)) { ?>
	<div class="product-description">
		<?php /** @todo Test if content plugins modify the product description */
		echo '<span class="bold">'. JText::_('VM_PRODUCT_DESC_TITLE'). '</span><br />';
		echo $this->product->product_desc; ?>
	</div>
	<?php } // Product Description END ?>

	<?php // Product Packaging
	$product_packaging = '';
	if ($this->product->packaging || $this->product->box) { ?>
	<div class="product-packaging margintop15">
		<?php
		echo '<span class="bold">'. JText::_('VM_PRODUCT_PACKAGING2'). '</span><br />';
		if ($this->product->packaging) {
			$product_packaging .= JText::_('VM_PRODUCT_PACKAGING1').$this->product->packaging;
			if ($this->product->box) $product_packaging .= '<br />';
		}
		if ($this->product->box) $product_packaging .= JText::_('VM_PRODUCT_PACKAGING2').$this->product->box;
		echo str_replace("{unit}",$this->product->product_unit ? $this->product->product_unit : JText::_('VM_PRODUCT_FORM_UNIT_DEFAULT'), $product_packaging); ?>
	</div>
	<?php } // Product Packaging END ?>



<?php // Product Files
	foreach ($this->product->files as $fkey => $file) {
		if( $file->filesize > 0.5) $filesize_display = ' ('. number_format($file->filesize, 2,',','.')." MB)";
		else $filesize_display = ' ('. number_format($file->filesize*1024, 2,',','.')." KB)";

		/* Show pdf in a new Window, other file types will be offered as download */
		$target = stristr($file->file_mimetype, "pdf") ? "_blank" : "_self";
		$link = JRoute::_('index.php?view=productdetails&task=getfile&file_id='.$file->file_id.'&product_id='.$this->product->product_id);
		echo JHTMl::_('link', $link, $file->file_title.$filesize_display, array('target' => $target));
	}
	// Related Products
	if ($this->product->related && !empty($this->product->related)) {
		foreach ($this->product->related as $rkey => $related) {
			?>
			<hr />
			<h3><?php echo JText::_('VM_RELATED_PRODUCTS_HEADING') ?></h3>

			<table width="100%" align="center">
				<tr>
					<td valign="top">
						<!-- The product name DIV. -->
						<div style="height:77px; float:left; width: 100%;line-height:14px;">
							<?php echo JHTML::_('link', $related->link, $related->product_name); ?>
							<br />
						</div>
						<!-- The product image DIV. -->
						<div style="height:90px;width: 100%;float:left;margin-top:-15px;">
								<?php
								echo JHTML::link($related->link, $related->images[0]->displayMediaThumb('title="'.$related->product_name.'"'));
								?>
						</div>
						<!-- The product price DIV. -->
						<div style="width: 100%; float: left; text-align: center;">
							<?php /** @todo Format pricing */ ?>
							<?php if (is_array($related->price)) echo $related->price['salesPrice']; ?>
						</div>
						<!-- The add to cart DIV. -->
						<div>
						</div>
					</td>
				</tr>
			</table>
			<?php
		}
	}

	/* Child categories */
	if (!empty($this->category->children)) {
			$iTopTenCol = 1;
		if (empty($this->category->categories_per_row)) {
			$this->category->categories_per_row = 4;
		}
		$TopTen_cellwidth = intval( 100 / $this->category->categories_per_row );
		?>
		<br />
		<table width="100%" cellspacing="0" cellpadding="0">
			<?php
			foreach($this->category->children as $category ) {
				if ($iTopTenCol == 1) { // this is an indicator wether a row needs to be opened or not
					echo "<tr>\n";
				}
				?>
				<td align="center" width="<?php echo $TopTen_cellwidth ?>%" >
					<br />
					<?php //TODO
					$url = JRoute::_('index.php?option=com_virtuemart&view=category&task=browse&category_id='.$category->category_id);
					//Todo add this 'alt="'.$category->category_name.'"', false).'<br /><br />'.$category->category_name.' ('.$category->number_of_products.')');
					echo JHTML::link($url, $category->images[0]->displayMediaThumb());
					?>
					<br />
				</td>
				<?php
				// Do we need to close the current row now?
				if ($iTopTenCol == $this->category->categories_per_row) { // If the number of products per row has been reached
					echo "</tr>\n";
					$iTopTenCol = 1;
				} else {
				$iTopTenCol++;
				}
			}
			// Do we need a final closing row tag?
			if ($iTopTenCol != 1) {
				echo "</tr>\n";
			}
			?>
		</table>
	<?php
	}

	/**
	* Reviews
	* Author max Milbers ?
	* Author Kohl Patrick
	* Available indexes:
	* $review->userid => The user ID of the comment author
	* $review->username => The username of the comment author
	* $review->name => The name of the comment author
	* $review->time => The UNIX timestamp of the comment ("when" it was posted)
	* $review->user_rating => The rating; an integer from 1 - 5
	*
	*/
	if ( VmConfig::get('allow_reviews') ){
		$maxrating = VmConfig::get('vm_maximum_rating_scale',5);
		$ratingsShow = VmConfig::get('vm_num_ratings_show',2);// TODO add  vm_num_ratings_show in vmConfig
		$starsPath = JURI::root().VmConfig::get('assets_general_path').'images/stars/';
		$stars = array();
		$showall = JRequest::getBool('showall', false);
		for ($num=0 ; $num <= $maxrating; $num++  ) {
			$title = (JText::_("VM_RATING_TITLE").' : '. $num . '/' . $maxrating) ;
			$stars[] = JHTML::image($starsPath.$num.'.gif', JText::_($num.'_STARS'), array("title" => $title) );
		}

		?>
		<table border="0" align="center" style="width: 100%;">
			<tr>
				<td colspan="2">
				<!-- List of product reviews -->
				<h4><?php echo JText::_('VM_REVIEWS') ?>:</h4>
				<?php
				$alreadycommented = false;

				$i=0 ;
				foreach($this->product_reviews as $review ) { // Loop through all reviews
					/* Check if user already commented */
					if ($review->userid == $this->user->id) {
					$alreadycommented = true;
					}

					echo $stars[ $review->user_rating ];
					?>
					<div><?php echo $review->comment; ?></div>
					<strong><?php echo $review->username.'&nbsp;&nbsp;('.JHTML::date($review->time, JText::_('DATE_FORMAT_LC')).')'; ?></strong>
					<br />
					<br />
					<?php
					$i++ ;
					if ( $i == $ratingsShow && !$showall) break;
				}
				if (count($this->product_reviews) < 1) echo JText::_('VM_NO_REVIEWS'); // "There are no reviews for this product"
				else {
					/* Show all reviews */
					if (!$showall && count($this->product_reviews) >=$ratingsShow ) {
						echo JHTML::link($this->more_reviews, JText::_('VM_MORE_REVIEWS'));
					}
				}
				?>
				<br />
				<?php

				if (!empty($this->user->id)) {
					if (!$alreadycommented) {
						echo JText::_('VM_WRITE_FIRST_REVIEW'); // "Be the first to write a review!"
						$reviewJavascript = "
							function check_reviewform() {
							var form = document.getElementById('reviewform');

							var ausgewaehlt = false;
							for (var i=0; i<form.user_rating.length; i++)
								if (form.user_rating[i].checked)
								  ausgewaehlt = true;
								if (!ausgewaehlt)  {
								  alert('".JText::_('VM_REVIEW_ERR_RATE',false)."');
								  return false;
								}
								else if (form.comment.value.length < ". VmConfig::get('reviews_minimum_comment_length', 100).") {
									alert('". JText::sprintf('VM_REVIEW_ERR_COMMENT1', VmConfig::get('reviews_minimum_comment_length', 100))."');
									return false;
								}
								else if (form.comment.value.length > ". VmConfig::get('reviews_maximum_comment_length', 2000).") {
									alert('". JText::sprintf('VM_REVIEW_ERR_COMMENT2', VmConfig::get('reviews_maximum_comment_length', 2000))."');
									return false;
								}
								else {
									return true;
								}
							}

						function refresh_counter() {
							var form = document.getElementById('reviewform');
							form.counter.value= form.comment.value.length;
						}";
						$document = &JFactory::getDocument();
						$document->addScriptDeclaration($reviewJavascript);
						?>

						<h4><?php echo JText::_('VM_WRITE_REVIEW')  ?></h4>
						<br /><?php echo JText::_('VM_REVIEW_RATE')  ?>
						<form method="post" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&product_id='.$this->product->product_id.'&category_id='.$this->product->category_id) ; ?>" name="reviewForm" id="reviewform">
							<table cellpadding="5" summary="<?php echo JText::_('VM_REVIEW_RATE') ?>">
								<tr>
								<?php
								for ($num=$maxrating ; $num>=0; $num--  ) {
									?>
										<th id="<?php echo $num ?>_stars">
											<label for="user_rating<?php echo $num ?>"><?php echo $stars[ $num ]; ?></label>
										</th>
									<?php
								} ?>
								</tr>
								<tr>
								<?php
								for ($num=$maxrating ; $num>=0; $num--  ) { ?>
									<td headers="<?php echo $num ?>_stars" style="text-align:center;">
										<input type="radio" id="user_rating<?php echo $num ?>" name="user_rating" value="<?php echo $num ?>" />
									</td>
								<?php
								} ?>
								</tr>
							</table>
							<br /><br />
							<?php
							echo JText::sprintf('VM_REVIEW_COMMENT', VmConfig::get('reviews_minimum_comment_length', 100), VmConfig::get('reviews_maximum_comment_length', 2000));
							?>
							<br />
							<textarea title="<?php echo JText::_('VM_WRITE_REVIEW') ?>" class="inputbox" id="comment" onblur="refresh_counter();" onfocus="refresh_counter();" onkeyup="refresh_counter();" name="comment" rows="5" cols="60"></textarea>
							<br />
							<input class="button" type="submit" onclick="return( check_reviewform());" name="submit_review" title="<?php echo JText::_('VM_REVIEW_SUBMIT')  ?>" value="<?php echo JText::_('VM_REVIEW_SUBMIT')  ?>" />
							<div align="right"><?php echo JText::_('VM_REVIEW_COUNT')  ?>
								<input type="text" value="0" size="4" class="inputbox" name="counter" maxlength="4" readonly="readonly" />
							</div>
							<input type="hidden" name="product_id" value="<?php echo $this->product->product_id; ?>" />
							<input type="hidden" name="option" value="com_virtuemart" />
							<input type="hidden" name="category_id" value="<?php echo JRequest::getInt('category_id'); ?>" />
							<input type="hidden" name="review_id" value="0" />
							<input type="hidden" name="task" value="review" />
						</form>
						<?php
					}
					else {
						echo '<strong>'.JText::_('VM_DEAR').$this->user->name.',</strong><br />' ;
						echo JText::_('VM_REVIEW_ALREADYDONE');
					}
				}
				else echo JText::_('VM_REVIEW_LOGIN'); // Login to write a review!
				?>
				</td>
			</tr>
		</table>
<?php
	} ?>
</div>
