<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Invoice - <?= $order->order_no ?></title>
    <style>
        @font-face {
            font-family: 'notosansgujarati';
            src: url('<?= base_url() ?>vendor/mpdf/mpdf/ttfonts/NotoSansGujarati-Regular.ttf') format('truetype');
        }
        
        body { 
            font-family: notosansgujarati, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 5px; 
            text-align: center; 
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo { 
            max-width: 150px; 
            height: auto; 
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 15px;
        }
        .stamp {
            border: 2px solid #000;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            text-align: center;
            line-height: 100px;
            font-weight: bold;
            font-size: 16px;
            transform: rotate(-15deg);
            position: absolute;
            right: 40px;
            bottom: 40px;
        }
        .customer-info {
            margin-bottom: 15px;
        }
         .amount-in-words {
            margin-top: 10px;
            padding: 5px;
            border: 1px dashed #000;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="logo-container">
    <img src="<?= base_url('assets/img/logo.jpeg') ?>" class="logo" alt="Company Logo">
</div>
<div class="header">
   
    <div style="text-align: right;">
        <p><strong>ORDER NO.:</strong> <?= $order->order_no ?></p>
        <p><strong>DATE:</strong> <?= date('d-m-Y', strtotime($order->order_date)) ?></p>
    </div>
</div>

<p><strong>NAME:</strong> <?= $order->customer_name ?></p>
<p><strong>CONCAT NO.:</strong> <?= $order->contact_no ?></p>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>ITEM</th>
            <th>PKT</th>
            <th>RATE / PKT</th>
            <th>NO OF PKT</th>
            <th>AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $i = 1;
        foreach ($order_items as $item):
            $item_data = $this->Common->get_info($item->item_id, 'tbl_item', 'item_id');
            $rate = $item_data->selling_price;
            $pkt = $item_data->size;
            $name = $item_data->item_name;
            $qty = $item->qty;
            $amount = $item->amount;
            $total += $amount;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $name ?></td>
            <td><?= $pkt ?></td>
            <td><?= number_format($rate, 2) ?></td>
            <td><?= $qty ?></td>
            <td><?= number_format($amount, 2) ?></td>
        </tr>
        <?php endforeach; ?>

       
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right;"><strong>TOTAL RS:</strong></td>
            <td><strong><?= number_format($total, 2) ?></strong></td>
        </tr>
    </tfoot>
</table>

<div class="amount-in-words">
    <strong>Amount in words:</strong> <?= convert_number_to_words($total) ?>
</div>
<!-- <div class="stamp">DELIVERED</div> -->

</body>
</html>
