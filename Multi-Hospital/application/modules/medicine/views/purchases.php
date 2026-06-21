<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-shopping-cart text-primary mr-3"></i>
                        Medicine Purchases
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item active">Purchases</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="medicine/addPurchaseView" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus mr-2"></i>Create Purchase Order
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header">
                            <h3 class="card-title text-black font-weight-800">All Medicine Purchase Orders</h3>
                        </div>

                        <div class="card-body bg-light">
                            <table class="table table-hover" id="editable-sample1">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase">Purchase Order</th>
                                        <th class="font-weight-bold text-uppercase">Supplier</th>
                                        <th class="font-weight-bold text-uppercase">Purchase Date</th>
                                        <th class="font-weight-bold text-uppercase">Invoice No.</th>
                                        <th class="font-weight-bold text-uppercase">Total Amount</th>
                                        <th class="font-weight-bold text-uppercase">Paid Amount</th>
                                        <th class="font-weight-bold text-uppercase">Balance</th>
                                        <th class="font-weight-bold text-uppercase">Purchase Status</th>
                                        <th class="font-weight-bold text-uppercase">Payment Status</th>
                                        <th class="font-weight-bold text-uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchases as $purchase) { ?>
                                        <tr>
                                            <td class="font-weight-bold"><?php echo $purchase->purchase_order_no; ?></td>
                                            <td><?php echo $purchase->supplier_name; ?></td>
                                            <td><?php echo date('d M Y', strtotime($purchase->purchase_date)); ?></td>
                                            <td><?php echo $purchase->invoice_number; ?></td>
                                            <td><?php echo $settings->currency . number_format($purchase->net_amount, 2); ?></td>
                                            <td><?php echo $settings->currency . number_format($purchase->paid_amount, 2); ?></td>
                                            <td><?php echo $settings->currency . number_format($purchase->balance_amount, 2); ?></td>
                                            <td>
                                                <?php 
                                                $status_colors = [
                                                    'pending' => 'warning',
                                                    'ordered' => 'info',
                                                    'received' => 'success',
                                                    'partial' => 'secondary',
                                                    'cancelled' => 'danger'
                                                ];
                                                $color = isset($status_colors[$purchase->purchase_status]) ? $status_colors[$purchase->purchase_status] : 'secondary';
                                                ?>
                                                <span class="badge badge-<?php echo $color; ?>"><?php echo ucfirst($purchase->purchase_status); ?></span>
                                            </td>
                                            <td>
                                                <?php 
                                                $payment_colors = [
                                                    'pending' => 'warning',
                                                    'partial' => 'info',
                                                    'paid' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $color = isset($payment_colors[$purchase->payment_status]) ? $payment_colors[$purchase->payment_status] : 'secondary';
                                                ?>
                                                <span class="badge badge-<?php echo $color; ?>"><?php echo ucfirst($purchase->payment_status); ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($purchase->purchase_status == 'pending' || $purchase->purchase_status == 'ordered') { ?>
                                                        <a href="medicine/receivePurchase?id=<?php echo $purchase->id; ?>" 
                                                           class="btn btn-success btn-sm" 
                                                           title="Receive Purchase">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <a href="medicine/viewPurchase?id=<?php echo $purchase->id; ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="View Purchase">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <?php if ($purchase->purchase_status == 'pending') { ?>
                                                        <a href="medicine/editPurchase?id=<?php echo $purchase->id; ?>" 
                                                           class="btn btn-primary btn-sm" 
                                                           title="Edit Purchase">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--main content end-->
<!--footer start-->
