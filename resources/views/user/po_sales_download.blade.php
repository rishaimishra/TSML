<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>

<div style="font-family: 'Roboto', sans-serif; width: 100%;">
	<div style="font-weight: bold; font-size: 18px; width: 100%;">
		Purchass Order
	</div>
	<div style="width: 100%;">
		<table style="width: 100%">
			<tr>
				<td>
					<table style="width: 100%;">
						<tr>
							<td>To</td>
						</tr>
						<tr>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">Company Tata Steel Mining Ltd</td>
						</tr>
					</table>
				</td>
				<td>
					<table style="width: 100%;">
						<tr>
							<td>Customer</td>
						</tr>
						<tr>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">{{$data['user_name']}}</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table style="width: 100%;">
						<tr>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">PO : {{$data['po_no']}}</td>
						</tr>
					</table>
				</td>
				<td>
					<table style="width: 100%;">
						<tr>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px;">PO Date : {{$data['po_date']}}</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<table style="width: 100%; border-collapse: collapse;">
						<tr>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left;">Product</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left; ">Category</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left; white-space: nowrap;">Size</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left; white-space: nowrap;">Quantity</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left;">Ship To</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left; white-space: nowrap;">Shipping Date</th>
							<th style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; text-align: left; white-space: nowrap;">Amount</th>
						</tr>
						 
						@foreach($result as $show)
						<tr>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">{{@$show['product_name']}}</td>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">{{@$show['cat_name']}}</td>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; white-space: nowrap;">{{@$show['sizes']}}</td>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">150</td>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">CF-345, Salt Lake City, Sector-1, Kolkata-700 064</td>
							<td style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">16-11-2022</td>
							<td align="right" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">107347.5</td>
						</tr>
						@endforeach
						<tr>
							<th colspan="6" align="right" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">Total</th>
							<th align="right" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse;">322042.5</th>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="vertical-align: top;">
					<table style="width: 100%; border-collapse: collapse;">
						<tr>
							<td rowspan="2" align="left" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; width: 50%; vertical-align: top;">Special Instructions</td>
							<td align="left" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; width: 50%; vertical-align: top;">Approval Signature<div style="height: 30px;"></div></td>
						</tr>
						<tr>
							<td align="left" style="border: 1px solid black; border-collapse: collapse; padding: 5px; border-collapse: collapse; width: 50%; vertical-align: top;">Purchaser Signature<div style="height: 30px;"></div></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>

</body>
</html>