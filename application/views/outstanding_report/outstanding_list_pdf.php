<!DOCTYPE html>
<html>

<head>
    <title>Outstanding Report</title>
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

    <h2 class="center">Outstanding Report</h2>
    <p><strong>Month:</strong> <?=date("F, Y", strtotime($month))?></p>


    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Outstanding Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_opening_bal = $total_credit = $total_debit = $total_closing_bal = 0;
                
                foreach ($reports as $row){ 
                    $total_closing_bal += $row->closing_bal;
            ?>
                <tr>
                    <td ><?= $row->customer_name; ?></td>
                    <td align="right"><?= number_format($row->closing_bal, 2); ?></td>
                </tr>
            <?php } ?>

            <tr>
                <th >Total</th>
                <th align="right"><?= number_format($total_closing_bal, 2); ?></th>
            </tr>
        </tbody>
    </table>

    <?php if (count($reports) == 0): ?>
        <div style="margin:40px 0; border-top:2px dashed #888;"></div>
        <!-- or page break between customers -->
        <!-- <div style="page-break-after:always;"></div> -->
    <?php endif; ?>



</body>

</html>