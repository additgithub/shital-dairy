<style>
    .form-group:focus-within input,
    .form-group:focus-within select,
    .form-group:focus-within textarea,
    .form-group:focus-within button,
    .btn-sm:focus {
        border: 3px solid #1d5e75ff;
    }

    .select2-container-active .select2-choice {
        border: 3px solid #1d5e75ff !important;

    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        /* spacing between checkbox, submit and cancel */
    }

    .me-3 {
        display: inline-flex !important;
    }

    .me-3 input {
        margin-right: 12px !important;
    }

    .me-3 .form-check-label {
        font-size: 15px;
    }
</style>
<?php
$DataID = $this->PrimaryKey;
$edit_mode = isset($data_info) && isset($data_info->$DataID) && $data_info->$DataID > 0;
$order_id = $edit_mode ? $data_info->$DataID : '';

$date = array(
    'name' => 'date',
    'id' => 'date',
    'type' => 'date',
    'value' => $edit_mode ? $data_info->date : (set_value('date') ?: ''),
    'class' => "form-control",
    'required' => true
);
$customer_name = array(
    'name' => 'customer_name',
    'id' => 'customer_name',
    'value' => $edit_mode ? $data_info->customer_name : set_value('customer_name'),
    'class' => "form-control",
    'required' => true
);
$contact_no = array(
    'name' => 'contact_no',
    'id' => 'contact_no',
    'value' => $edit_mode ? $data_info->contact_no : set_value('contact_no'),
    'class' => "form-control customer_contact_no",
    'required' => true,
    'readonly' => 'readonly'
);
$remarks = array(
    'name' => 'remarks',
    'id' => 'remarks',
    'value' => $edit_mode ? $data_info->remarks : set_value('remarks'),
    'class' => "form-control"
);
$delivery_charges = array(
    'name' => 'delivery_charges',
    'id' => 'delivery_charges',
    'value' => $edit_mode ? $data_info->delivery_charges : set_value('delivery_charges', 0),
    'class' => "form-control delivery_charges"
);
$dry_ice_box_charges = array(
    'name' => 'dry_ice_box_charges',
    'id' => 'dry_ice_box_charges',
    'value' => $edit_mode ? $data_info->dry_ice_box_charges : set_value('dry_ice_box_charges', 0),
    'class' => "form-control dry_ice_box_charges"
);
$other_charges = array(
    'name' => 'other_charges',
    'id' => 'other_charges',
    'value' => $edit_mode ? $data_info->other_charges : set_value('other_charges', 0),
    'class' => "form-control other_charges"
);
$total_amount = $edit_mode ? $data_info->amount : 0;

$form_attr = array('class' => 'default_form', 'id' => 'bid_edit_frm', 'autocomplete' => "off");
$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons');
?>

<div class="content">

    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <?php
                $this->load->view("includes/messages");
                ?>
                <div class="grid-title">
                    <h4><?php echo $page_title; ?></h4>
                </div>

                <div class="grid-body no-border">
                    <?= form_open_multipart(base_url($this->controllers . '/submit-form'), $form_attr); ?>
                    <?php if ($edit_mode) echo form_hidden($DataID, $order_id); ?>

                    <div class="row">
                        <!-- <div class="form-group col-md-4">
                                    <label>Order No</label> -->
                        <!-- </div> -->
                        <div class="form-group col-md-4">
                            <label>Customer<span class="spn_required">*</span></label>
                            <select name="customer_name" class="form-control select2 customer_select" required>
                                <option value="">Select Customer</option>
                                <?php
                                if (isset($customers) && !empty($customers)) {
                                    foreach ($customers as $customer) {
                                        $selected = ($edit_mode && $data_info->customer_name == $customer->customer_id) ? 'selected' : '';
                                        echo "<option data-id=\"{$customer->customer_id}\" value=\"{$customer->customer_id}\" {$selected}>{$customer->customer_name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Date<span class="spn_required">*</span></label>
                            <?= form_input($date); ?>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Type<span class="spn_required">*</span></label>
                            <select name="type" class="form-control select2 customer_select" required>
                                <option value="">Select Type</option>
                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Total Amount</label>
                            <input type="text" name="total_amount" id="total_amount" class="form-control" value="<?= $total_amount ?>">
                        </div>
                    </div>



                    <div class="form-actions pull-right d-flex align-items-center">

                        <?= form_submit($submit_btn); ?>
                        <a class="btn btn-white cancel_button ms-2" href="javascript:;">Cancel</a>
                    </div>

                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const items = <?= isset($items) && !empty($items) ? json_encode($items) : '[]'; ?>;
    const old_items = <?= isset($order_items) ? json_encode($order_items) : '[]'; ?>;
    let itemIndex = 0;
    console.log('Items:', items);

    function addItemRow_old(existing = null) {
        console.log(existing, 'existing')
        const item_id = existing ? existing.item_id : '';
        const qty = existing ? existing.qty : 1;
        const return_qty = existing ? existing.return_qty : 0;
        const price_per_item = existing ? existing.price_per_item : '';
        const available_pkt = existing ? (existing.total_purchase_qty_pkt - existing.total_sells_qty_pkt) : 0;
        console.log('Available PKT:', available_pkt);
        const amount = existing ? parseFloat(existing.amount).toFixed(2) : '0.00';
        let availableAttr2 = available_pkt > 0 ? available_pkt : '0 Avalaible PKT';
        const html = `<div class="row custom_form_row item_row" data-index="${itemIndex}">
        <div class="form-group col-md-4 cus_filds">
            <label>Item</label>
            <select name="item_id[]" class="form-control select2 item_select" required onchange="updateRowPrice(this)">
                <option value="">Select Item</option>
                ${items.map(it => {
                        const availableAttr = `data-available="${it.total_purchase_qty_pkt - it.total_sells_qty_pkt}"`;
                        const availableLalAttr = it.reorder == 0 ? `data-availableLbl="${it.total_purchase_qty_pkt - it.total_sells_qty_pkt}"` : '';
                    availableAttr2 = it.total_purchase_qty_pkt - it.total_sells_qty_pkt
                    return `<option value="${it.item_id}" data-price="${it.selling_price}" ${availableAttr} ${availableLalAttr} data-reorder="${it.reorder}" ${it.item_id == item_id ? 'selected' : ''}>
                        ${it.item_name} ${it.selling_price}
                    </option>`;
                }).join('')}
            </select>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <label>Qty (KG)</label>
            <input type="number" name="item_qty[]" class="form-control item_qty" min="1"  value="${qty}" data-price="0" oninput="updateRowPrice(this)" required>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <label>Return Qty (KG)</label>
            <input type="number" name="return_qty[]" class="form-control return_qty" min="0"  value="${return_qty}" data-price="0" oninput="updateRowPrice(this)" required>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <label>Price Per KG</label>
            <input type="number" name="price_per_kg[]" class="form-control price_per_kg" value="${price_per_item}" min="1">
        </div>
        <div class="form-group col-md-2 cus_filds">
            <label>Total Amount</label>
            <input type="text" name="item_total[]" class="form-control item_total" value="${amount}" readonly>
        </div>
        <div class="form-group col-md-2 cus_filds">
            <button type="button" class="btn btn-danger removeItemBtn">Remove</button>
        </div>
    </div>`;

        $('#items_container').append(html);
        $("#items_container select.select2").select2();
        itemIndex++;
        calculateTotal();

    }

    function addItemRow(existing = null) {
        console.log(existing, 'existing');
        const item_id = existing ? existing.item_id : '';
        const qty = existing ? existing.qty : 1;
        const return_qty = existing ? existing.return_qty : 0;
        const price_per_item = existing ? existing.price_per_item : '';
        const available_pkt = existing ? (existing.total_purchase_qty_pkt - existing.total_sells_qty_pkt) : 0;
        console.log('Available PKT:', available_pkt);
        const amount = existing ? parseFloat(existing.amount).toFixed(2) : '0.00';
        let availableAttr2 = available_pkt > 0 ? available_pkt : '0 Avalaible PKT';

        const html = $(`
            <div class="row custom_form_row item_row" data-index="${itemIndex}">
                <div class="form-group col-md-4 cus_filds">
                    <label>Item</label>
                    <select name="item_id[]" class="form-control select2 item_select" required onchange="updateRowPrice(this)">
                        <option value="">Select Item</option>
                        ${items.map(it => {
                            const availableAttr = `data-available="${it.total_purchase_qty_pkt - it.total_sells_qty_pkt}"`;
                            const availableLalAttr = it.reorder == 0 ? `data-availableLbl="${it.total_purchase_qty_pkt - it.total_sells_qty_pkt}"` : '';
                            availableAttr2 = it.total_purchase_qty_pkt - it.total_sells_qty_pkt;
                            return `<option value="${it.item_id}" data-price="${it.selling_price}" ${availableAttr} ${availableLalAttr} data-reorder="${it.reorder}" ${it.item_id == item_id ? 'selected' : ''}>
                                ${it.item_name} ${it.selling_price}
                            </option>`;
                        }).join('')}
                    </select>
                </div>
                <div class="form-group col-md-2 cus_filds">
                    <label>Qty (KG)</label>
                    <input type="number" name="item_qty[]" class="form-control item_qty" min="1" value="${qty}" data-price="0" oninput="updateRowPrice(this)" required>
                </div>
                <div class="form-group col-md-2 cus_filds">
                    <label>Return Qty (KG)</label>
                    <input type="number" name="return_qty[]" class="form-control return_qty" min="0" value="${return_qty}" data-price="0" oninput="updateRowPrice(this)" required>
                </div>
                <div class="form-group col-md-2 cus_filds">
                    <label>Price Per KG</label>
                    <input type="number" name="price_per_kg[]" class="form-control price_per_kg" value="${price_per_item}" min="1">
                </div>
                <div class="form-group col-md-2 cus_filds">
                    <label>Total Amount</label>
                    <input type="text" name="item_total[]" class="form-control item_total" value="${amount}" readonly>
                </div>
                <div class="form-group col-md-2 cus_filds">
                    <button type="button" class="btn btn-danger removeItemBtn">Remove</button>
                </div>
            </div>
        `);

        // append new row
        $('#items_container').append(html);

        // init select2 on new row only
        html.find(".select2").select2();

        itemIndex++;
        calculateTotal();

        return html; // 🔑 return jQuery object, not string
    }


    function updateRowPrice(el) {
        // const row = $(el).closest('.item_row');
        // const select = row.find('.item_select');
        // const qtyInput = row.find('.item_qty');
        // const totalInput = row.find('.item_total');
        // const price_per_kg = row.find('.price_per_kg');
        // const sellingPrice = parseFloat(select.find('option:selected').data('price')) || 0;

        const row = $(el).closest('.item_row');
        const select = row.find('.item_select');
        const qtyInput = row.find('.item_qty');
        const priceInput = row.find('.price_per_kg');
        const totalInput = row.find('.item_total');

        // const price = parseFloat(select.find('option:selected').data('price')) || 0;
        const selectedPrice = parseFloat(select.find('option:selected').data('price')) || 0;
        let price = parseFloat(priceInput.val()) || 0;
        if (!price) {
            price = selectedPrice;
            priceInput.val(price.toFixed(2));
        }

        const qty = parseFloat(qtyInput.val()) || 0;
        const return_qty = parseFloat(row.find('.return_qty').val()) || 0;
        const net_qty = qty - return_qty;
        const lineTotal = price * net_qty;
        // console.log('Line Total:', lineTotal);
        // const available = parseInt(select.find('option:selected').data('available')) || 1;
        // qtyInput.attr('max', available);
        // const available = select.find('option:selected').data('available') || 0;
        // const availableLbl = select.find('option:selected').data('availableLbl') || 0;
        // const reorder = parseInt(select.find('option:selected').data('reorder')) || 0;

        // console.log('Available PKT:', available);
        // if (available !== undefined && parseInt(available) > 0 && reorder === 0) {
        //     console.log('Setting max qty:', available);
        //     qtyInput.attr('max', parseInt(available));
        //     qtyInput.attr('title', 'Available: ' + parseInt(available) + ' pkt');
        //     if (parseInt(available) > 0) {
        //         if (qty > parseInt(available)) {
        //             qtyInput.val(parseInt(available)); // Reset to max if current qty exceeds available
        //             alert('Quantity exceeds available stock. Resetting to maximum available.');

        //         }
        //     }
        // } else {
        //     qtyInput.removeAttr('max'); // Always allow if no limit
        // }
        priceInput.val(price);
        qtyInput.data('price', price);
        console.log(lineTotal.toFixed(2), 'lineTotal');
        row.find('.item_total').val(lineTotal.toFixed(2));
        // totalInput.val(lineTotal.toFixed(2));
        // availble_qty.val(available + ' Available PKT');
        // availble_qty.val(available + ' Available PKT');

        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        // $('.price_per_kg').each(function() {
        //     $('.item_total').val(parseFloat($(this).val()));
        // });
        $('.item_total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_amount').val(total.toFixed(2));

        let deliveryCharges = $('#delivery_charges').val();
        let dryIceBoxCharges = $('#dry_ice_box_charges').val();
        let otherCharges = $('#other_charges').val();
        deliveryCharges = parseFloat(deliveryCharges) || 0;
        dryIceBoxCharges = parseFloat(dryIceBoxCharges) || 0;
        otherCharges = parseFloat(otherCharges) || 0;
        total += deliveryCharges + dryIceBoxCharges + otherCharges;
        $('#total_amount').val(total.toFixed(2));
    }

    //$(document).on('click', '#addItemBtn', () => addItemRow());
    let addItemTimeout;
    $(document).off('click', '#addItemBtn').on('click', '#addItemBtn', function() {
        let $btn = $(this);
        $btn.prop("disabled", true); // better than attr for buttons

        clearTimeout(addItemTimeout);
        addItemTimeout = setTimeout(() => {
            // addItemRow();
            let $newRow = addItemRow();

            // Focus first input or select2 in new row
            let $firstField = $newRow.find("input, select, textarea").first();

            if ($firstField.hasClass("select2-hidden-accessible")) {
                // If it's a select2, trigger focus on container
                $firstField.select2("open");
            } else {
                $firstField.focus();
            }

            $btn.prop("disabled", false);
            $btn.prop("disabled", false); // re-enable after row is added
        }, 100);
    });
    $(document).on('click', '.removeItemBtn', function() {
        $(this).closest('.item_row').remove();
        calculateTotal();
    });

    $(document).ready(function() {
        // $('#order_frm').on('keypress', function(e) {
        //     // Prevent form submission on Enter key press
        //     if (e.key === 'Enter' && e.target.tagName.toLowerCase() !== 'textarea') {
        //         e.preventDefault();
        //         return false;
        //     }
        // });
        if (old_items.length > 0) {
            old_items.forEach(it => addItemRow(it));
        } else {
            addItemRow();
        }

        $('.price_per_kg').on('keyup', function() {
            calculateTotal();
        });
        $('.delivery_charges, .dry_ice_box_charges, .other_charges').on('keyup', function() {
            calculateTotal();
        });
        $(document).on('input', '.price_per_kg, .item_qty', function() {
            updateRowPrice(this);
        });
    });
</script>