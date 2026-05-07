<!DOCTYPE html>
<html>

<head>
    <title>Ledger Report</title>
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

    <h2 class="center">Ledger Report</h2>
    <p><strong>Month:</strong> <?=date("F, Y", strtotime($month))?></p>


    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Opening Bal</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Closing Bal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $row): ?>
                <tr>
                    <td ><?= $row->customer_name; ?></td>
                    <td align="right"><?= number_format($row->opening_bal, 2); ?></td>
                    <td align="right"><?= number_format($row->credit, 2); ?></td>
                    <td align="right"><?= number_format($row->debit, 2); ?></td>
                    <td align="right"><?= number_format($row->closing_bal, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (count($reports) == 0): ?>
        <div style="margin:40px 0; border-top:2px dashed #888;"></div>
        <!-- or page break between customers -->
        <!-- <div style="page-break-after:always;"></div> -->
    <?php endif; ?>



</body>

</html>