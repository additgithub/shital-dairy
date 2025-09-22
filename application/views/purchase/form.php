<?php
$DataID = $this->PrimaryKey;
$edit_mode = isset($data_info) && isset($data_info->$DataID) && $data_info->$DataID > 0;
$purchase_id = $edit_mode ? $data_info->$DataID : '';

$purchase_date = array(
    'name' => 'purchase_date',
    'id' => 'purchase_date',
    'type' => 'date',
    'value' => $edit_mode ? $data_info->purchase_date : (set_value('purchase_date') ?: date('Y-m-d')),
    'class' => "form-control",
    'required' => true
);
$suppiler_id = $edit_mode ? $data_info->suppiler_id : 0;
$remarks = array(
    'name' => 'remarks',
    'id' => 'remarks',
    'value' => $edit_mode ? $data_info->remarks : set_value('remarks'),
    'class' => "form-control"
);
$total_amount = $edit_mode ? $data_info->amount : 0;

$form_attr = array('class' => 'default_form', 'id' => 'order_frm', 'autocomplete' => "off");
$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons');
?>

<div class="page-title">
    <h3><?= $page_title ?></h3>
</div>

<div class="grid simple">
    <div class="grid-title no-border">
        <h4>Purchase Information</h4>
    </div>
    <div class="grid-body no-border">
        <?= form_open_multipart(base_url($this->controllers . '/submit-form'), $form_attr); ?>
        <?php if ($edit_mode) echo form_hidden($DataID, $purchase_id); ?>

        <div class="row">
            <!-- <div class="form-group col-md-4">
                <label>Order No</label> -->
            <!-- </div> -->
            <div class="form-group col-md-4">
                <label>Purchase Date<span class="spn_required">*</span></label>
                <?= form_input($purchase_date); ?>
            </div>
            <div class="form-group col-md-4">
                <label>Supplier Name<span class="spn_required">*</span></label>
                <select name="suppiler_id" class="form-control select2 item_select" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['suppiler_id'] ?>" <?= $s['suppiler_id'] == $suppiler_id ? 'selected' : '' ?>>
                            <?= $s['suppiler_name'] ?> - <?= $s['suppiler_mobile'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <hr>
        <h4>Order Items</h4>
        <div id="items_container"></div>
        <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">+ Add Item</button>

        <hr>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Total Amount</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control" value="<?= $total_amount ?>" readonly>
            </div>
            <div class="form-group col-md-8">
                <label>Remarks</label>
                <?= form_textarea($remarks); ?>
            </div>
        </div>

        <div class="form-actions pull-right">
            <?= form_submit($submit_btn); ?>
            <a class="btn btn-white cancel_button" href="javascript:;">Cancel</a>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<script>
    const items = <?= isset($items) && !empty($items) ? json_encode($items) : '[]'; ?>;
    const old_items = <?= isset($order_items) ? json_encode($order_items) : '[]'; ?>;
    let itemIndex = 0;

function addItemRow(existing = null) {
    const item_id = existing ? existing.item_id : '';
    const qty = existing ? existing.qty_kg : 1;
    const price = existing ? existing.purchase_price_per_kg : '';
    const amount = existing ? parseFloat(existing.total_amount).toFixed(2) : '0.00';
    const total_pkt = existing ? parseFloat(existing.qty).toFixed(2) : '0.00';

    // Add header once
    if ($('#items_container .header_row').length === 0) {
        const headerHtml = `
            <div class="row custom_form_row header_row font-weight-bold" style="margin-bottom: 5px;">
                <div class="form-group col-md-4 cus_filds">Item</div>
                <div class="form-group col-md-2 cus_filds">Qty (KG/PCS)</div>
                <div class="form-group col-md-2 cus_filds">Price per KG/PCS</div>
                <div class="form-group col-md-2 cus_filds">Total Pkts</div>
                <div class="form-group col-md-2 cus_filds">Amount</div>
                <div class="form-group col-md-2 cus_filds">Action</div>
            </div>`;
        $('#items_container').append(headerHtml);
    }

    const html = `<div class="row custom_form_row item_row">
        <div class="form-group col-md-4 cus_filds">
            <select name="item_id[]" class="form-control select2 item_select" required onchange="updateRowPrice(this)">
                <option value="">Select Item</option>
                ${items.map(it => `<option value="${it.item_id}" data-price="${it.selling_price}" data-factor="${it.factor}" ${it.item_id == item_id ? 'selected' : ''}>
                    ${it.item_name} (${it.size})
                </option>`).join('')}
            </select>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="number" name="item_qty[]" class="form-control item_qty" min="0" step="any" value="${qty}" oninput="updateRowPrice(this)" required>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="number" name="item_price[]" class="form-control item_price" min="0" value="${price}" oninput="updateRowPrice(this)" required>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="text" name="item_total_pkt[]" class="form-control item_total_pkt" value="${total_pkt}" readonly>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="text" name="item_total[]" class="form-control item_total" value="${amount}" readonly>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <button type="button" class="btn btn-danger removeItemBtn">Remove</button>
        </div>
    </div>`;

    $('#items_container').append(html);
  $('select.select2').select2();
    itemIndex++;
    calculateTotal();
}



    function updateRowPrice(el) {
    const row = $(el).closest('.item_row');
    const select = row.find('.item_select');
    const qtyInput = row.find('.item_qty');
    const priceInput = row.find('.item_price');
    const pktInput = row.find('.item_total_pkt');
    const totalInput = row.find('.item_total');

    const qty = parseFloat(qtyInput.val()) || 0;
    const price = parseFloat(priceInput.val()) || 0;
    const factor = parseFloat(select.find(':selected').data('factor')) || 0;

    // Calculations
    const totalAmount = qty * price;
    const packetQty = factor > 0 ? qty / factor : 0;
    const pricePerPkt = factor > 0 ? price * factor : 0;

    pktInput.val(packetQty.toFixed(2));
    totalInput.val(totalAmount.toFixed(2));

    calculateTotal();
}

    function calculateTotal() {
        let total = 0;
        $('.item_total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_amount').val(total.toFixed(2));
    }

    //$(document).on('click', '#addItemBtn', () => addItemRow());
    let addItemTimeout;
    $(document).on('click', '#addItemBtn', function() {
        clearTimeout(addItemTimeout);
        addItemTimeout = setTimeout(() => addItemRow(), 100); // delay just enough to avoid rapid double trigger
          $("select.select2").select2();
    });
    $(document).on('click', '.removeItemBtn', function() {
        $(this).closest('.item_row').remove();
        calculateTotal();
    });

    $(document).ready(function() {
       $("select.select2").select2();
        $('#order_frm').on('keypress', function(e) {
            // Prevent form submission on Enter key press
            if (e.key === 'Enter' && e.target.tagName.toLowerCase() !== 'textarea') {
                e.preventDefault();
                return false;
            }
        });
        if (old_items.length > 0) {
            old_items.forEach(it => addItemRow(it));
        } else {
            addItemRow();
        }
    });
</script>