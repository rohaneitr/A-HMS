<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-edit text-primary mr-3"></i>
                        Edit Purchase Order
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item"><a href="medicine/purchases">Purchases</a></li>
                            <li class="breadcrumb-item active">Edit Purchase</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-5">
                            <?php echo validation_errors(); ?>
                            <form role="form" action="medicine/addNewPurchase" method="post" id="purchaseForm">
                                <input type="hidden" name="id" value="<?php echo $purchase->id; ?>">
                                
                                <div class="row">
                                    <!-- Purchase Header Information -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-info-circle mr-2"></i>Purchase Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Purchase Order No. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" name="purchase_order_no" 
                                                   value="<?php echo $purchase->purchase_order_no; ?>" required="">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-lg select2" name="supplier_id" required="">
                                                <option value="">Select Supplier</option>
                                                <?php foreach ($suppliers as $supplier) { ?>
                                                    <option value="<?php echo $supplier->id; ?>" 
                                                            <?php echo ($supplier->id == $purchase->supplier_id) ? 'selected' : ''; ?>>
                                                        <?php echo $supplier->name . ' - ' . $supplier->company_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Purchase Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-lg" name="purchase_date" 
                                                   value="<?php echo $purchase->purchase_date; ?>" required="">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Expected Delivery Date</label>
                                            <input type="date" class="form-control form-control-lg" name="expected_delivery_date"
                                                   value="<?php echo $purchase->expected_delivery_date; ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-file-invoice mr-2"></i>Invoice Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Invoice Number</label>
                                            <input type="text" class="form-control form-control-lg" name="invoice_number"
                                                   value="<?php echo $purchase->invoice_number; ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Invoice Date</label>
                                            <input type="date" class="form-control form-control-lg" name="invoice_date"
                                                   value="<?php echo $purchase->invoice_date; ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Payment Terms</label>
                                            <input type="text" class="form-control form-control-lg" name="payment_terms" 
                                                   placeholder="e.g., Net 30 days" value="<?php echo $purchase->payment_terms; ?>">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Notes</label>
                                            <textarea class="form-control form-control-lg" name="notes" rows="3" 
                                                      placeholder="Additional notes or instructions"><?php echo $purchase->notes; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Purchase Items Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3"><i class="fas fa-list mr-2"></i>Purchase Items</h5>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="purchaseItemsTable">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Cost</th>
                                                        <th>Total</th>
                                                        <th>Batch Number</th>
                                                        <th>Expiry Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purchaseItemsBody">
                                                    <?php if (!empty($purchase_items)) { ?>
                                                        <?php foreach ($purchase_items as $index => $item) { ?>
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control select2" name="medicine_id[]" required="">
                                                                        <option value="">Select Medicine</option>
                                                                        <?php foreach ($medicines as $medicine) { ?>
                                                                            <option value="<?php echo $medicine->id; ?>" 
                                                                                    <?php echo ($medicine->id == $item->medicine_id) ? 'selected' : ''; ?>>
                                                                                <?php echo $medicine->name . ' - ' . $medicine->generic; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control quantity" name="quantity[]" 
                                                                           min="1" required="" value="<?php echo $item->quantity_ordered; ?>" 
                                                                           onchange="calculateRowTotal(this)">
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" class="form-control unit-cost" name="unit_cost[]" 
                                                                           min="0" required="" value="<?php echo $item->unit_cost; ?>" 
                                                                           onchange="calculateRowTotal(this)">
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" class="form-control row-total" 
                                                                           readonly="" value="<?php echo $item->total_cost; ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="batch_number[]" 
                                                                           placeholder="Batch number" value="<?php echo $item->batch_number; ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" name="expiry_date[]"
                                                                           value="<?php echo $item->expiry_date; ?>">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td>
                                                                <select class="form-control select2" name="medicine_id[]" required="">
                                                                    <option value="">Select Medicine</option>
                                                                    <?php foreach ($medicines as $medicine) { ?>
                                                                        <option value="<?php echo $medicine->id; ?>">
                                                                            <?php echo $medicine->name . ' - ' . $medicine->generic; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control quantity" name="quantity[]" 
                                                                       min="1" required="" onchange="calculateRowTotal(this)">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control unit-cost" name="unit_cost[]" 
                                                                       min="0" required="" onchange="calculateRowTotal(this)">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control row-total" 
                                                                       readonly="" value="0.00">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="batch_number[]" 
                                                                       placeholder="Batch number">
                                                            </td>
                                                            <td>
                                                                <input type="date" class="form-control" name="expiry_date[]">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-success" onclick="addRow()">
                                                <i class="fas fa-plus mr-2"></i>Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Purchase Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Purchase Summary</h6>
                                                
                                                <div class="row mb-2">
                                                    <div class="col-6">Subtotal:</div>
                                                    <div class="col-6 text-right">
                                                        <?php echo $settings->currency; ?><span id="subtotal"><?php echo number_format($purchase->total_amount, 2); ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <label>Transport Charges:</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" class="form-control form-control-sm text-right" 
                                                               name="transport_charges" value="<?php echo $purchase->transport_charges; ?>" onchange="calculateTotal()">
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <label>Other Charges:</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" class="form-control form-control-sm text-right" 
                                                               name="other_charges" value="<?php echo $purchase->other_charges; ?>" onchange="calculateTotal()">
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="row mb-2">
                                                    <div class="col-6"><strong>Total Amount:</strong></div>
                                                    <div class="col-6 text-right">
                                                        <strong><?php echo $settings->currency; ?><span id="totalAmount"><?php echo number_format($purchase->net_amount, 2); ?></span></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>Update Purchase Order
                                    </button>
                                    <a href="medicine/purchases" class="btn btn-secondary btn-lg px-5 ml-3">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function addRow() {
    const tbody = document.getElementById('purchaseItemsBody');
    const newRow = tbody.rows[0].cloneNode(true);
    
    // Clear the values in the new row
    const inputs = newRow.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.type === 'number') {
            input.value = '';
        } else if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        } else {
            input.value = '';
        }
    });
    
    // Clear readonly fields
    newRow.querySelector('.row-total').value = '0.00';
    
    tbody.appendChild(newRow);
}

function removeRow(button) {
    const tbody = document.getElementById('purchaseItemsBody');
    if (tbody.rows.length > 1) {
        button.closest('tr').remove();
        calculateTotal();
    }
}

function calculateRowTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitCost = parseFloat(row.querySelector('.unit-cost').value) || 0;
    const total = quantity * unitCost;
    
    row.querySelector('.row-total').value = total.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    const rowTotals = document.querySelectorAll('.row-total');
    
    rowTotals.forEach(total => {
        subtotal += parseFloat(total.value) || 0;
    });
    
    const transportCharges = parseFloat(document.querySelector('input[name="transport_charges"]').value) || 0;
    const otherCharges = parseFloat(document.querySelector('input[name="other_charges"]').value) || 0;
    const totalAmount = subtotal + transportCharges + otherCharges;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
}

// Initialize calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>

<!--main content end-->
<!--footer start-->
