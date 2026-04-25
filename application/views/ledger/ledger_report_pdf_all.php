<!DOCTYPE html>
<html>

<head>
    <title>Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 class="center">Summary Report</h2>
    <p>
        <?php
        if ($this->input->get('start_date') && $this->input->get('start_date') != '' && $this->input->get('end_date') && $this->input->get('end_date') != '') {
        ?>
            <strong>From:</strong> <?= date("d-m-Y", strtotime($this->input->get('start_date'))) ?>
            <strong>To:</strong> <?= date("d-m-Y", strtotime($this->input->get('end_date'))) ?>
        <?php

        }
        ?>
    </p>

    <?php foreach ($reports as $index => $rpt): ?>
    <h2 style="text-align:center; margin-bottom:10px;">
        <?= $rpt['customer']->customer_name; ?>
    </h2>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Payment / Order</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rpt['records'] as $row): ?>
            <tr>
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
                <td align="right"><?= number_format($row['debit'], 2); ?></td>
                <td align="right"><?= number_format($row['credit'], 2); ?></td>
                <td align="right"><?= number_format($row['balance'], 2); ?></td>
                <td><?= $row['remark']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($index < count($reports) - 1): ?>
        <div style="margin:40px 0; border-top:2px dashed #888;"></div>
        <!-- or page break between customers -->
        <!-- <div style="page-break-after:always;"></div> -->
    <?php endif; ?>

<?php endforeach; ?>


</body>

</html>