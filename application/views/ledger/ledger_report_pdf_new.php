<!DOCTYPE html>
<html>
<head>
    <title>Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; }
        .center { text-align: center; }
    </style>
</head>
<body>

<h2 class="center">Ledger Summary Report</h2>
<p><strong>From:</strong> <?=date("d-m-Y", strtotime($start_date))?> 
   <strong>To:</strong> <?=date("d-m-Y", strtotime($end_date))?></p>

<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Transaction Date</th>
            <th>Payment / Order</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Balance</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($report)) { 
            foreach($report as $row) { ?>
            <tr>
                <td><?= $row['customer_name'] ?></td>
                <td><?= date("d/m/Y", strtotime($row['txn_date'])) ?></td>
                <td>
                    <?php 
                        if ($row['order_id'] > 0) {
                            echo "OD" . str_pad($row['order_id'], 4, "0", STR_PAD_LEFT);
                        } elseif ($row['payment_id'] > 0) {
                            echo "PAY" . str_pad($row['payment_id'], 4, "0", STR_PAD_LEFT);
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
                <td><?= ($row['credit'] > 0) ? number_format($row['credit'], 2) : '' ?></td>
                <td><?= ($row['debit'] > 0) ? number_format($row['debit'], 2) : '' ?></td>
                <td><?= number_format($row['balance'], 2) ?></td>
                <!-- <td><?= nl2br($row['remark']) ?></td> -->
                <td><?= str_replace('<br>', '<br />', nl2br($row['remark'])) ?></td>

            </tr>
        <?php } } else { ?>
            <tr><td colspan="7" class="center">No Records Found</td></tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
