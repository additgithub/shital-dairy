<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #ffffff;
            color: #fff;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .total {
            background: #f8d7da;
            font-weight: bold;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="logo-container">
        <img src="<?= base_url('assets/img/logo.jpg') ?>" class="logo" alt="Company Logo">
    </div>
    <h2 style="text-align:center;">Item-wise Order Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Qty KG/PCS</th>
                <th>Total Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summary as $row): ?>
                <tr class="<?= $row->item_name == 'Total' ? 'total' : '' ?>">
                    <td><?= $row->item_name ?></td>
                     <td><?= $row->qty_kg ?></td>
                    <td><?= number_format($row->totalamt, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>