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
        const qty = existing ? existing.qty : 1;
        const amount = existing ? parseFloat(existing.total_amount).toFixed(2) : '0.00';

        const html = `<div class="row custom_form_row item_row" >
         <div class="form-group col-md-4 ">
         Item
         </div>
         <div class="form-group col-md-2 ">
         Qty In KG/PCS
         </div>
         <div class="form-group col-md-2 ">
         Price In KG/PCS
         </div>
         <div class="form-group col-md-2 ">
         PKT Total
         </div>
         <div class="form-group col-md-2 ">
         PKT Total
         </div>
        </div>
        
        <div class="row custom_form_row item_row" data-index="${itemIndex}">
        <div class="form-group col-md-4 cus_filds">
            <select name="item_id[]" class="form-control select2 item_select" required onchange="updateRowPrice(this)">
                <option value="">Select Item</option>
                ${items.map(it => `<option value="${it.item_id}" data-price="${it.selling_price}" data-factor="${it.factor}" ${it.item_id == item_id ? 'selected' : ''}>
                    ${it.item_name} (${it.size}) 
                </option>`).join('')}
            </select>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="number" name="item_qty[]" class="form-control item_qty" min="1" value="${qty}" data-price="0" oninput="updateRowPrice(this)" required>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="number" name="item_price[]" min="1" class="form-control item_price " value="">
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="text" name="item_total_pkt[]" class="form-control item_total_pkt" value="" readonly>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <input type="text" name="item_total[]" class="form-control item_total" value="${amount}" readonly>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <button type="button" class="btn btn-danger removeItemBtn">Remove</button>
        </div>
    </div>`;

        $('#items_container').append(html);
        itemIndex++;
        calculateTotal();
    }

    function updateRowPrice(el) {
        const row = $(el).closest('.item_row');
        const select = row.find('.item_select');
        const qtyInput = row.find('.item_qty');
        const totalInput = row.find('.item_total');
        const totalInputPkt = row.find('.item_total_pkt');
        let factor = parseFloat(select.find('option:selected').data('factor')) || 0;
        const price = parseFloat(select.find('option:selected').data('price')) || 0;
        const qty = parseFloat(qtyInput.val()) || 0;
        const lineTotal = price * qty;

        qtyInput.data('price', price);
        totalInput.val(lineTotal.toFixed(2));
        totalInputPkt.val((lineTotal * factor).toFixed(2));

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
    });
    $(document).on('click', '.removeItemBtn', function() {
        $(this).closest('.item_row').remove();
        calculateTotal();
    });

    $(document).ready(function() {
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