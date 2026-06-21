<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-boxes text-primary mr-3"></i>
                        Medicine Batches
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item active">Batches</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="medicine/expiringMedicines" class="btn btn-warning btn-lg mr-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Expiring Medicines
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
                            <h3 class="card-title text-black font-weight-800">All Medicine Batches</h3>
                        </div>

                        <div class="card-body bg-light">
                            <table class="table table-hover" id="editable-sample1">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase">Medicine Name</th>
                                        <th class="font-weight-bold text-uppercase">Generic</th>
                                        <th class="font-weight-bold text-uppercase">Batch Number</th>
                                        <th class="font-weight-bold text-uppercase">Supplier</th>
                                        <th class="font-weight-bold text-uppercase">Manufacturing Date</th>
                                        <th class="font-weight-bold text-uppercase">Expiry Date</th>
                                        <th class="font-weight-bold text-uppercase">Current Stock</th>
                                        <th class="font-weight-bold text-uppercase">Unit Cost</th>
                                        <th class="font-weight-bold text-uppercase">Selling Price</th>
                                        <th class="font-weight-bold text-uppercase">Status</th>
                                        <th class="font-weight-bold text-uppercase">Days to Expiry</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($batches as $batch) { 
                                        $expiry_date = new DateTime($batch->expiry_date);
                                        $today = new DateTime();
                                        $days_to_expiry = $today->diff($expiry_date)->days;
                                        $is_expired = $expiry_date < $today;
                                        
                                        if ($is_expired) {
                                            $days_to_expiry = -$days_to_expiry;
                                        }
                                    ?>
                                        <tr class="<?php 
                                            if ($is_expired) {
                                                echo 'table-danger';
                                            } elseif ($days_to_expiry <= 30) {
                                                echo 'table-warning';
                                            } elseif ($days_to_expiry <= 90) {
                                                echo 'table-info';
                                            }
                                        ?>">
                                            <td class="font-weight-bold"><?php echo $batch->medicine_name; ?></td>
                                            <td><?php echo $batch->generic; ?></td>
                                            <td>
                                                <span class="badge badge-primary"><?php echo $batch->batch_number; ?></span>
                                            </td>
                                            <td><?php echo $batch->supplier_name; ?></td>
                                            <td>
                                                <?php echo $batch->manufacturing_date ? date('d M Y', strtotime($batch->manufacturing_date)) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d M Y', strtotime($batch->expiry_date)); ?>
                                            </td>
                                            <td>
                                                <?php if ($batch->current_stock <= 0) { ?>
                                                    <span class="text-danger font-weight-bold">Out of Stock</span>
                                                <?php } else { ?>
                                                    <span class="font-weight-bold"><?php echo $batch->current_stock; ?></span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $settings->currency . number_format($batch->unit_cost, 2); ?></td>
                                            <td><?php echo $settings->currency . number_format($batch->selling_price, 2); ?></td>
                                            <td>
                                                <?php 
                                                if ($is_expired) {
                                                    echo '<span class="badge badge-danger">Expired</span>';
                                                } elseif ($batch->current_stock <= 0) {
                                                    echo '<span class="badge badge-dark">Out of Stock</span>';
                                                } elseif ($days_to_expiry <= 30) {
                                                    echo '<span class="badge badge-warning">Expiring Soon</span>';
                                                } else {
                                                    echo '<span class="badge badge-success">Active</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($is_expired) {
                                                    echo '<span class="text-danger font-weight-bold">Expired ' . abs($days_to_expiry) . ' days ago</span>';
                                                } elseif ($days_to_expiry <= 0) {
                                                    echo '<span class="text-danger font-weight-bold">Expires Today</span>';
                                                } elseif ($days_to_expiry <= 30) {
                                                    echo '<span class="text-warning font-weight-bold">' . $days_to_expiry . ' days</span>';
                                                } else {
                                                    echo '<span class="text-success">' . $days_to_expiry . ' days</span>';
                                                }
                                                ?>
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
