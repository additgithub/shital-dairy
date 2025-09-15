<style>
    .custom-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .custom-summary-table th {
        background-color: #00acf0;
        color: #fff;
        padding: 12px;
        text-align: center;
        font-weight: bold;
    }

    .custom-summary-table td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .custom-summary-table tr.total-row {
        background-color: #f8d7da;
        /* light red background */
        font-weight: bold;
    }
</style>

<div class="content">
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple">
                <?php $this->load->view("includes/messages"); ?>
                <div class="grid-title">
                    <h4><?= $page_title; ?></h4>
                    <br>
                    <form method="get" class="form-inline" style="margin-bottom: 15px;">
                        <div class="form-group">
                            <label>Start Date:</label>
                            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                        </div>
                        <div class="form-group" style="margin-left: 10px;">
                            <label>End Date:</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;margin-top: 35px;margin-left: 50px;" >Filter</button>
                        <a href="<?= base_url('orders/item_summary') ?>" style="margin-bottom: 10px;margin-top: 35px;" class="btn btn-default">Reset</a>
                        <a href="<?= base_url('orders/download_item_summary_pdf') ?>" class="btn btn-danger" style="margin-bottom: 10px;margin-top: 35px;" target="_blank">
                            <i class="fa fa-download"></i> Download PDF
                        </a>
                    </form>


                </div>
                <div class="grid-body">
                    <table class="custom-summary-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Qty PKT</th>
                                <th>Qty KG</th>
                                <th>Total Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($summary)): ?>
                                <?php foreach ($summary as $row): ?>
                                    <tr class="<?= ($row->item_name == 'Total') ? 'total-row' : '' ?>">
                                        <td><?= $row->item_name ?></td>
                                        <td><?= $row->qty_pkt ?></td>
                                        <td><?= $row->qty_kg ?></td>
                                        <td><?= number_format($row->totalamt, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>