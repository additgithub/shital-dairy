<div class="container mt-4">
    <!-- Order Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Payment</h5>
                    <p><strong>Date:</strong> <?= date("d-m-Y", strtotime($payment->payment_date)) ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>Customer:</strong> <?= $payment->customer_name ?></p>
                    <p><strong>Payment Date:</strong> <?= date("d-m-Y", strtotime($payment->payment_date)) ?></p>
                    <p><strong>Payment Type:</strong> <?= $payment->payment_type ?></p>
                    <p><strong>Amount:</strong> <?= $payment->amount ?></p>
                    <p><strong>Remark:</strong> <?= $payment->remark ?></p>
                </div>
            </div>
        </div>
    </div>

</div>