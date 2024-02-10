<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Order Details # 1 - RMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			#invoice-POS h1 {
				font-size: 1.5em;  
			}
			#invoice-POS h2 {
				font-size: 0.9em;
			}
			#invoice-POS h3 {
				font-size: 1.2em;
				font-weight: 300;
				line-height: 2em;
			}
			#invoice-POS p {
				font-size: 0.7em;
				line-height: 1.2em;
			}
			#invoice-POS table {
				width: 100%;
				border-collapse: collapse;
			}
			#invoice-POS .text-left {
				text-align: left;
			}
			#invoice-POS .text-right {
				text-align: right;
			}
			#invoice-POS .text-center {
				text-align: center;
			}
			body  
			{ 
				/* this affects the margin on the content before sending to printer */ 
				margin: 0px;  
			} 
			@page  
			{ 
				size: auto;   /* auto is the initial value */ 

				/* this affects the margin in the printer settings */ 
				margin: 0mm 0 0mm 0;  
			} 

			@media screen {
				.header, .footer {
					display: none;
				}
			} 
			@media all {
				.page-break { display: none; }
			}
			@media print {
				.page-break { display: block; page-break-after: always; }
			}
        </style>
    </head>
    <body onload="window.print();">
		<div id="invoice-POS" style="width:80mm; margin:0 auto;overflow: hidden;padding:0 2mm; font-size: 16px;color: #000; ">
			<table width="100%">
				<tbody>
					<tr>
						<td style="border-bottom:1px #333 solid;" align="center" colspan="5">
							<img class="img img-responsive" src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-height: 80px; max-width: 200px;" alt="logo"><br>
							<h1><strong><?= $companyName; ?></strong></h1>
							<p><?= $footerContactAddr; ?><br>
								Phone: <?= $footerContactPhone; ?></p>
							<?= $order['order_type'] == 'sale_return' ? '<h4>Sales Return</h4>' : ''; ?>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="5"><b><?= $customer_info->name; ?></b><?= $customer_info->phone ? '<br>'.$customer_info->phone : ''; ?></td>
					</tr>
					<tr>
						<td align="center" colspan="5">Date:<?= $order['date']; ?></td>
					</tr>
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>
					<tr>
						<th class="text-left">SL#</th>
						<th class="text-left" style="max-width:120px">Item</th>         
						<th class="text-center">Qty</th>
						<th class="text-center">Price</th>
						<th class="text-right">Total</th>
					</tr>
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>
					<?php $i=1; $tq=0; $st=0; foreach(unserialize($order['products']) as $line=>$item) { 
						$tq += $item['product_info']['quantity'];
						$st += $item['product_info']['total'];
					?>
						<tr>
							<td class="text-left"><?= $i++; ?></td>
							<td class="text-left" style="max-width:120px"><?= $item['product_info']['name']; ?></td>
							<td class="text-center"><?= $item['product_info']['quantity']; ?></td>
							<td class="text-center"><?= number_format($item['product_info']['price'], $nf); ?></td>
							<td class="text-right"><?= number_format($item['product_info']['total'], $nf); ?></td>
						</tr>
					<?php }?>
					
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>  
					<tr>
						<td colspan="3" class="text-left"><strong>Sub Total</strong></td>
						<td colspan="2" class="text-right"><strong><?= number_format($st, $nf); ?></strong></td>
					</tr>
					<?php if($order['shipping_cost']>0): ?>
					<tr>              
						<td colspan="3" class="text-left">Shipping Cost</td>
						<td colspan="2" class="text-right">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
					</tr>
					<?php endif; ?>
					<?php if($order['labour_cost']>0): ?>
					<tr>              
						<td colspan="3" class="text-left">Labour Cost</td>
						<td colspan="2" class="text-right">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
					</tr>
					<?php endif; ?>
					<?php if($order['carrying_cost']>0): ?>
					<tr>              
						<td colspan="3" class="text-left">Carrying Cost</td>
						<td colspan="2" class="text-right">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
					</tr>
					<?php endif; ?>
					<?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
					<tr>              
						<td colspan="3" class="text-left">Discount Amount</td>
						<td colspan="2" class="text-right">(-)<?= number_format($order['discount_code'], $nf); ?></td>
					</tr>
					<?php endif; ?>
					<?php if($order['referrer'] == "POS"): 
						foreach(unserialize($order['payment_type']) as $pay){
							if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
							<tr>
								<td colspan="3"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
								<td colspan="2" class="text-right"><?= number_format($pay['payment_amount'], $nf); ?></td>
							</tr><?php
						}
					endif; ?>
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>
					<?php if($order['total']>0): ?>
					<tr>
						<td colspan="2" class="text-left"><h1><strong>Total</strong></h1></td>
						<td colspan="3" align="right"><h1><strong><?= number_format($order['total'], $nf); ?></strong></h1></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>
					<?php if($order['asof_date_due']>0): ?>
					<tr>
						<td colspan="3" style="color:#F00;">Total Dues</td>
						<td colspan="2" class="text-right" style="color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
					</tr>
					<?php endif; ?>
					<?php if($order['asof_date_due']<0): ?>
					<tr>
						<td colspan="3" style="color:#F00;">Total Advance</td>
						<td colspan="2" class="text-right" style="color:#F00;"><?= number_format($order['asof_date_due'] * -1, $nf); ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td colspan="5" style="border-top:#333 1px dashed;"></td>
					</tr>
					<tr>
						<td colspan="5" class="text-center"><br><img src="data:image/png;base64,<?= $barcode; ?>"></td>               
					</tr>  
					<tr>
						<td colspan="5" class="text-center"><p>Thank you...</p></td>               
					</tr> 
				</tbody>
			</table>
		</div>   
		<div class="page-break"></div>
	</body>
</html>