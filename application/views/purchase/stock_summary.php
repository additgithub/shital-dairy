<style>
    .custom-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .custom-summary-table th {
        background-color: #ffffff;
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
                    <h3 class="text-center"><?= $page_title ?></h3>
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
                        <a href="<?= base_url('purchase/stock_summary') ?>" style="margin-bottom: 10px;margin-top: 35px;" class="btn btn-default">Reset</a>
                     
                    </form>
                   
                </div>
                <div class="grid-body">
                  
                        <table class="custom-summary-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <!-- <th>Qty (Pkt)</th>
                                    <th>Qty (Kg)</th>
                                    <th>Total Amt</th> -->
                                    <th>Purchased (Pkt)</th>
                                    <th>Sold (Pkt)</th>
                                    <th>Stock (Pkt)</th>
                                    <th>Purchased (Kg)</th>
                                    <th>Sold (Kg)</th>
                                    <th>Stock (Kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary as $row): ?>
                                    <tr>
                                        <td><?= $row->item_name ?></td>
                                        <!-- <td><?= $row->qty_pkt ?></td>
                                        <td><?= $row->qty_kg ?></td>
                                        <td><?= number_format($row->totalamt, 2) ?></td> -->
                                        <td><?= isset($row->total_purchase_qty_pkt) ? $row->total_purchase_qty_pkt : '-' ?></td>
                                        <td><?= isset($row->total_sells_qty_pkt) ? $row->total_sells_qty_pkt : '-' ?></td>
                                        <td><?= isset($row->stock_pkt) ? $row->stock_pkt : '-' ?></td>
                                        <td><?= isset($row->total_purchase_qty_kg) ? number_format($row->total_purchase_qty_kg,3) : '-' ?></td>
                                        <td><?= isset($row->total_sells_qty_kg) ? number_format($row->total_sells_qty_kg,3) : '-' ?></td>
                                        <td><?= isset($row->stock_kg) ? number_format($row->stock_kg,3) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>