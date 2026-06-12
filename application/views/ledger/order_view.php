<div class="container mt-4">
    <!-- Order Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Order #<?= $order->order_no ?></h5>
                    <p><strong>Date:</strong> <?= date("d-m-Y", strtotime($order->order_date)) ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Customer:</strong> <?= $order->customer_name ?></p>
                    <p><strong>Email:</strong> <?= $order->customer_email ?></p>
                    <p><strong>Phone:</strong> <?= $order->customer_mobile ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Order Details</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; $grand_total = 0; ?>
                    <?php foreach($order_items as $item): ?>
                        <?php $line_total = $item->qty * $item->price_per_item; $grand_total += $line_total; ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $item->item_name ?></td>
                            <td><?= $item->qty ?></td>
                            <td><?= number_format($item->price_per_item, 2) ?></td>
                            <td><?= number_format($line_total, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php
                        if(!empty($order->delivery_charges)){ ?>
                            <tr>
                                <th colspan="4" class="text-end">Delivery Charges</th>
                                <th><?= number_format($order->delivery_charges, 2) ?></th>
                            </tr>
                    <?php } ?>
                    <?php
                        if(!empty($order->dry_ice_box_charges)){ ?>
                            <tr>
                                <th colspan="4" class="text-end">Dry Ice Box Charges</th>
                                <th><?= number_format($order->dry_ice_box_charges, 2) ?></th>
                            </tr>
                    <?php } ?>
                    <?php
                        if(!empty($order->other_charges)){ ?>
                            <tr>
                                <th colspan="4" class="text-end">Other Charges</th>
                                <th><?= number_format($order->other_charges, 2) ?></th>
                            </tr>
                    <?php } ?>
                    <?php
                        if(!empty($order->tax_amount) && $order->tax_amount>0){ ?>
                            <tr>
                                <th colspan="4" class="text-end">CGST (2.5%)</th>
                                <th><?= number_format($order->tax_amount / 2, 2) ?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">SGST (2.5%)</th>
                                <th><?= number_format($order->tax_amount / 2, 2) ?></th>
                            </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="4" class="text-end">Grand Total</th>
                        <th><?= number_format($order->amount, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Order Actions -->
    <!-- <div class="d-flex justify-content-between">
        <a href="<?= base_url('orders') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <div>
            <a href="<?= base_url('orders/print/'.$order->order_id) ?>" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="<?= base_url('orders/pdf/'.$order->order_id) ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>
        </div>
    </div> -->
</div>
