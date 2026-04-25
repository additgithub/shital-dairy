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

<h2 class="center">Summary Report</h2>
<p><strong>From:</strong> <?=date("d-m-Y", strtotime($this->input->get('start_date')))?> 
   <strong>To:</strong> <?=date("d-m-Y", strtotime($this->input->get('end_date')))?></p>

<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Order No</th>
            <th>Total Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($report)) { 
            foreach($report as $row) { ?>
            <tr>
                <td><?= $row['customer_name'] ?></td>
                <td><?= date("d/m/Y", strtotime($row['order_date'])) ?></td>
                <td>
                    <?php echo $row['order_no']; ?>
                </td>
                <td>
                    <?php echo number_format($row['amount'],2); ?>
                </td>
                <td><?= str_replace('<br>', '<br />', nl2br($row['remark'])) ?></td>

            </tr>
        <?php } } else { ?>
            <tr><td colspan="7" class="center">No Records Found</td></tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
