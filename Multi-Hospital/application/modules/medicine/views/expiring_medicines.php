<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-exclamation-triangle text-warning mr-3"></i>
                        Expiring Medicines
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item"><a href="medicine/batches">Batches</a></li>
                            <li class="breadcrumb-item active">Expiring Medicines</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="medicine/batches" class="btn btn-secondary btn-lg">
                        <i class="fas fa-boxes mr-2"></i>All Batches
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Expired</h6>
                                    <h3 class="mb-0">
                                        <?php 
                                        $expired_count = 0;
                                        foreach ($expiring_medicines as $medicine) {
                                            if (strtotime($medicine->expiry_date) < time()) {
                                                $expired_count++;
                                            }
                                        }
                                        echo $expired_count;
                                        ?>
                                    </h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Expiring in 30 days</h6>
                                    <h3 class="mb-0">
                                        <?php 
                                        $expiring_30_count = 0;
                                        foreach ($expiring_medicines as $medicine) {
                                            $days_diff = (strtotime($medicine->expiry_date) - time()) / (60 * 60 * 24);
                                            if ($days_diff > 0 && $days_diff <= 30) {
                                                $expiring_30_count++;
                                            }
                                        }
                                        echo $expiring_30_count;
                                        ?>
                                    </h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Expiring in 90 days</h6>
                                    <h3 class="mb-0">
                                        <?php 
                                        $expiring_90_count = 0;
                                        foreach ($expiring_medicines as $medicine) {
                                            $days_diff = (strtotime($medicine->expiry_date) - time()) / (60 * 60 * 24);
                                            if ($days_diff > 30 && $days_diff <= 90) {
                                                $expiring_90_count++;
                                            }
                                        }
                                        echo $expiring_90_count;
                                        ?>
                                    </h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Value at Risk</h6>
                                    <h3 class="mb-0">
                                        <?php 
                                        $total_value = 0;
                                        foreach ($expiring_medicines as $medicine) {
                                            $total_value += $medicine->current_stock * $medicine->unit_cost;
                                        }
                                        echo $settings->currency . number_format($total_value, 2);
                                        ?>
                                    </h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header">
                            <h3 class="card-title text-black font-weight-800">Medicines Expiring Within 90 Days</h3>
                            <div class="card-tools">
                                <span class="badge badge-info">
                                    Total: <?php echo count($expiring_medicines); ?> batches
                                </span>
                            </div>
                        </div>

                        <div class="card-body bg-light">
                            <table class="table table-hover" id="expiringMedicinesTable">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase">Medicine Name</th>
                                        <th class="font-weight-bold text-uppercase">Generic</th>
                                        <th class="font-weight-bold text-uppercase">Batch Number</th>
                                        <th class="font-weight-bold text-uppercase">Supplier</th>
                                        <th class="font-weight-bold text-uppercase">Expiry Date</th>
                                        <th class="font-weight-bold text-uppercase">Current Stock</th>
                                        <th class="font-weight-bold text-uppercase">Unit Cost</th>
                                        <th class="font-weight-bold text-uppercase">Total Value</th>
                                        <th class="font-weight-bold text-uppercase">Days to Expiry</th>
                                        <th class="font-weight-bold text-uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expiring_medicines as $medicine) { 
                                        $expiry_date = new DateTime($medicine->expiry_date);
                                        $today = new DateTime();
                                        $days_to_expiry = $today->diff($expiry_date)->days;
                                        $is_expired = $expiry_date < $today;
                                        
                                        if ($is_expired) {
                                            $days_to_expiry = -$days_to_expiry;
                                        }
                                        
                                        $total_value = $medicine->current_stock * $medicine->unit_cost;
                                    ?>
                                        <tr class="<?php 
                                            if ($is_expired) {
                                                echo 'table-danger';
                                            } elseif ($days_to_expiry <= 30) {
                                                echo 'table-warning';
                                            } else {
                                                echo 'table-info';
                                            }
                                        ?>">
                                            <td class="font-weight-bold"><?php echo $medicine->medicine_name; ?></td>
                                            <td><?php echo $medicine->generic; ?></td>
                                            <td>
                                                <span class="badge badge-secondary"><?php echo $medicine->batch_number; ?></span>
                                            </td>
                                            <td><?php echo $medicine->supplier_name; ?></td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    <?php echo date('d M Y', strtotime($medicine->expiry_date)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?php echo $medicine->current_stock; ?></span>
                                            </td>
                                            <td><?php echo $settings->currency . number_format($medicine->unit_cost, 2); ?></td>
                                            <td>
                                                <span class="font-weight-bold text-primary">
                                                    <?php echo $settings->currency . number_format($total_value, 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($is_expired) {
                                                    echo '<span class="badge badge-danger">Expired ' . abs($days_to_expiry) . ' days ago</span>';
                                                } elseif ($days_to_expiry <= 0) {
                                                    echo '<span class="badge badge-danger">Expires Today</span>';
                                                } elseif ($days_to_expiry <= 7) {
                                                    echo '<span class="badge badge-danger">' . $days_to_expiry . ' days</span>';
                                                } elseif ($days_to_expiry <= 30) {
                                                    echo '<span class="badge badge-warning">' . $days_to_expiry . ' days</span>';
                                                } else {
                                                    echo '<span class="badge badge-info">' . $days_to_expiry . ' days</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($is_expired) {
                                                    echo '<span class="badge badge-danger">Expired</span>';
                                                } elseif ($days_to_expiry <= 7) {
                                                    echo '<span class="badge badge-danger">Critical</span>';
                                                } elseif ($days_to_expiry <= 30) {
                                                    echo '<span class="badge badge-warning">Warning</span>';
                                                } else {
                                                    echo '<span class="badge badge-info">Watch</span>';
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

<script>
$(document).ready(function() {
    $('#expiringMedicinesTable').DataTable({
        "order": [[ 8, "asc" ]], // Sort by days to expiry (ascending)
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            {
                "targets": [8], // Days to expiry column
                "type": "num"
            }
        ]
    });
});
</script>

<!--main content end-->
<!--footer start-->
