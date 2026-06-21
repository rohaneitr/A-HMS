<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-check-circle text-success mr-3"></i>
                        Receive Purchase Order
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item"><a href="medicine/purchases">Purchases</a></li>
                            <li class="breadcrumb-item active">Receive Purchase</li>
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
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Purchase Order: <?php echo $purchase->purchase_order_no; ?>
                            </h4>
                            <p class="mb-0">Supplier: <?php echo $purchase->supplier_name; ?></p>
                        </div>
                        
                        <div class="card-body p-5">
                            <!-- Purchase Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Purchase Date:</strong></td>
                                            <td><?php echo date('d M Y', strtotime($purchase->purchase_date)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Expected Delivery:</strong></td>
                                            <td>
                                                <?php echo $purchase->expected_delivery_date ? date('d M Y', strtotime($purchase->expected_delivery_date)) : 'Not specified'; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Amount:</strong></td>
                                            <td><strong><?php echo $settings->currency . number_format($purchase->net_amount, 2); ?></strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Supplier Contact:</strong></td>
                                            <td><?php echo $purchase->contact_person; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td><?php echo $purchase->phone; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?php echo $purchase->email; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <!-- Receive Items Form -->
                            <form role="form" action="medicine/processReceivePurchase" method="post" id="receiveForm">
                                <input type="hidden" name="purchase_id" value="<?php echo $purchase->id; ?>">
                                
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-list mr-2"></i>Items to Receive
                                </h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Ordered Qty</th>
                                                <th>Received Qty</th>
                                                <th>Unit Cost</th>
                                                <th>Batch Number</th>
                                                <th>Manufacturing Date</th>
                                                <th>Expiry Date</th>
                                                <th>Manufacturer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_items as $item) { ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo $item->medicine_name; ?></strong><br>
                                                        <small class="text-muted"><?php echo $item->generic; ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo $item->quantity_ordered; ?></span>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="item_id[]" value="<?php echo $item->id; ?>">
                                                        <input type="number" class="form-control" 
                                                               name="received_quantity[]" 
                                                               min="0" 
                                                               max="<?php echo $item->quantity_ordered; ?>"
                                                               value="<?php echo $item->quantity_ordered; ?>"
                                                               required="">
                                                    </td>
                                                    <td>
                                                        <strong><?php echo $settings->currency . number_format($item->unit_cost, 2); ?></strong>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" 
                                                               name="batch_number[]" 
                                                               value="<?php echo $item->batch_number; ?>"
                                                               placeholder="Enter batch number"
                                                               required="">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control" 
                                                               name="manufacturing_date[]"
                                                               value="<?php echo $item->manufacturing_date; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control" 
                                                               name="expiry_date[]" 
                                                               value="<?php echo $item->expiry_date; ?>"
                                                               required="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" 
                                                               name="manufacturer[]" 
                                                               value="<?php echo $item->manufacturer; ?>"
                                                               placeholder="Manufacturer name">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase text-sm">Delivery Date</label>
                                            <input type="date" class="form-control form-control-lg" 
                                                   name="delivery_date" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase text-sm">Received By</label>
                                            <input type="text" class="form-control form-control-lg" 
                                                   name="received_by" value="<?php echo $this->session->userdata('user_name'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-uppercase text-sm">Receiving Notes</label>
                                    <textarea class="form-control form-control-lg" name="receiving_notes" rows="3" 
                                              placeholder="Any notes about the received items (condition, damages, etc.)"></textarea>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-check mr-2"></i>Process Receipt
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
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate batch numbers if empty
    const batchInputs = document.querySelectorAll('input[name="batch_number[]"]');
    batchInputs.forEach((input, index) => {
        if (!input.value) {
            const today = new Date();
            const dateStr = today.getFullYear().toString() + 
                           (today.getMonth() + 1).toString().padStart(2, '0') + 
                           today.getDate().toString().padStart(2, '0');
            input.value = 'BATCH-' + dateStr + '-' + (index + 1).toString().padStart(3, '0');
        }
    });

    // Validate expiry dates
    const expiryInputs = document.querySelectorAll('input[name="expiry_date[]"]');
    expiryInputs.forEach(input => {
        input.addEventListener('change', function() {
            const expiryDate = new Date(this.value);
            const today = new Date();
            
            if (expiryDate <= today) {
                alert('Warning: Expiry date is in the past or today!');
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#28a745';
            }
        });
    });

    // Form submission validation
    document.getElementById('receiveForm').addEventListener('submit', function(e) {
        const receivedQtys = document.querySelectorAll('input[name="received_quantity[]"]');
        let totalReceived = 0;
        
        receivedQtys.forEach(input => {
            totalReceived += parseFloat(input.value) || 0;
        });
        
        if (totalReceived === 0) {
            e.preventDefault();
            alert('Please enter at least one item to receive.');
            return false;
        }
        
        return confirm('Are you sure you want to process this receipt? This action cannot be undone.');
    });
});
</script>

<!--main content end-->
<!--footer start-->
