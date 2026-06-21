<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-eye text-info mr-3"></i>
                        View Purchase Order
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item"><a href="medicine/purchases">Purchases</a></li>
                            <li class="breadcrumb-item active">View Purchase</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="medicine/purchases" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Purchases
                    </a>
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
                                            <td><strong>Invoice Number:</strong></td>
                                            <td><?php echo $purchase->invoice_number ? $purchase->invoice_number : 'Not provided'; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Invoice Date:</strong></td>
                                            <td>
                                                <?php echo $purchase->invoice_date ? date('d M Y', strtotime($purchase->invoice_date)) : 'Not provided'; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Terms:</strong></td>
                                            <td><?php echo $purchase->payment_terms ? $purchase->payment_terms : 'Not specified'; ?></td>
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
                                        <tr>
                                            <td><strong>Purchase Status:</strong></td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                switch($purchase->purchase_status) {
                                                    case 'pending':
                                                        $status_class = 'badge-warning';
                                                        break;
                                                    case 'ordered':
                                                        $status_class = 'badge-info';
                                                        break;
                                                    case 'received':
                                                        $status_class = 'badge-success';
                                                        break;
                                                    case 'cancelled':
                                                        $status_class = 'badge-danger';
                                                        break;
                                                    default:
                                                        $status_class = 'badge-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($purchase->purchase_status); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Status:</strong></td>
                                            <td>
                                                <?php 
                                                $payment_class = '';
                                                switch($purchase->payment_status) {
                                                    case 'pending':
                                                        $payment_class = 'badge-warning';
                                                        break;
                                                    case 'partial':
                                                        $payment_class = 'badge-info';
                                                        break;
                                                    case 'paid':
                                                        $payment_class = 'badge-success';
                                                        break;
                                                    default:
                                                        $payment_class = 'badge-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $payment_class; ?>"><?php echo ucfirst($purchase->payment_status); ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Financial Summary -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-calculator mr-2"></i>Financial Summary</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted">Total Amount</h6>
                                                        <h4 class="text-primary"><?php echo $settings->currency . number_format($purchase->total_amount, 2); ?></h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted">Tax Amount</h6>
                                                        <h4 class="text-info"><?php echo $settings->currency . number_format($purchase->tax_amount, 2); ?></h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted">Transport Charges</h6>
                                                        <h4 class="text-warning"><?php echo $settings->currency . number_format($purchase->transport_charges, 2); ?></h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted">Net Amount</h6>
                                                        <h4 class="text-success"><strong><?php echo $settings->currency . number_format($purchase->net_amount, 2); ?></strong></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Purchase Items -->
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-list mr-2"></i>Purchase Items
                            </h5>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Medicine Name</th>
                                            <th>Generic Name</th>
                                            <th>Category</th>
                                            <th>Ordered Qty</th>
                                            <th>Received Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Total Cost</th>
                                            <th>Batch Number</th>
                                            <th>Expiry Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchase_items as $item) { ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo $item->medicine_name; ?></strong>
                                                </td>
                                                <td><?php echo $item->generic; ?></td>
                                                <td><?php echo $item->category; ?></td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo $item->quantity_ordered; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($item->quantity_received > 0) { ?>
                                                        <span class="badge badge-success"><?php echo $item->quantity_received; ?></span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-warning">0</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo $settings->currency . number_format($item->unit_cost, 2); ?></strong>
                                                </td>
                                                <td>
                                                    <strong><?php echo $settings->currency . number_format($item->total_cost, 2); ?></strong>
                                                </td>
                                                <td><?php echo $item->batch_number ? $item->batch_number : 'Not assigned'; ?></td>
                                                <td>
                                                    <?php if ($item->expiry_date) { ?>
                                                        <?php echo date('d M Y', strtotime($item->expiry_date)); ?>
                                                    <?php } else { ?>
                                                        Not specified
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $item_status_class = '';
                                                    switch($item->received_status) {
                                                        case 'pending':
                                                            $item_status_class = 'badge-warning';
                                                            break;
                                                        case 'received':
                                                            $item_status_class = 'badge-success';
                                                            break;
                                                        case 'partial':
                                                            $item_status_class = 'badge-info';
                                                            break;
                                                        default:
                                                            $item_status_class = 'badge-secondary';
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $item_status_class; ?>"><?php echo ucfirst($item->received_status); ?></span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Notes Section -->
                            <?php if ($purchase->notes) { ?>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h5 class="mb-0"><i class="fas fa-sticky-note mr-2"></i>Notes</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-0"><?php echo nl2br($purchase->notes); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Action Buttons -->
                            <div class="text-center mt-4">
                                <?php if ($purchase->purchase_status == 'pending' || $purchase->purchase_status == 'ordered') { ?>
                                    <a href="medicine/receivePurchase?id=<?php echo $purchase->id; ?>" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-check mr-2"></i>Receive Purchase
                                    </a>
                                <?php } ?>
                                
                                <?php if ($purchase->purchase_status == 'pending') { ?>
                                    <a href="medicine/editPurchase?id=<?php echo $purchase->id; ?>" class="btn btn-primary btn-lg px-5 ml-3">
                                        <i class="fas fa-edit mr-2"></i>Edit Purchase
                                    </a>
                                <?php } ?>
                                
                                <a href="medicine/purchases" class="btn btn-secondary btn-lg px-5 ml-3">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Purchases
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--main content end-->
<!--footer start-->
