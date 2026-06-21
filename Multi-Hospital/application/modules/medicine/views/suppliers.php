<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-truck text-primary mr-3"></i>
                        Medicine Suppliers
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item active">Suppliers</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="medicine/addSupplierView" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus mr-2"></i>Add New Supplier
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
                            <h3 class="card-title text-black font-weight-800">All Medicine Suppliers</h3>
                        </div>

                        <div class="card-body bg-light">
                            <table class="table table-hover" id="editable-sample1">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase">ID</th>
                                        <th class="font-weight-bold text-uppercase">Supplier Name</th>
                                        <th class="font-weight-bold text-uppercase">Company</th>
                                        <th class="font-weight-bold text-uppercase">Contact Person</th>
                                        <th class="font-weight-bold text-uppercase">Phone</th>
                                        <th class="font-weight-bold text-uppercase">Email</th>
                                        <th class="font-weight-bold text-uppercase">City</th>
                                        <th class="font-weight-bold text-uppercase">Credit Limit</th>
                                        <th class="font-weight-bold text-uppercase">Status</th>
                                        <th class="font-weight-bold text-uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($suppliers as $supplier) { ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td class="font-weight-bold"><?php echo $supplier->name; ?></td>
                                            <td><?php echo $supplier->company_name; ?></td>
                                            <td><?php echo $supplier->contact_person; ?></td>
                                            <td><?php echo $supplier->phone; ?></td>
                                            <td><?php echo $supplier->email; ?></td>
                                            <td><?php echo $supplier->city; ?></td>
                                            <td><?php echo $settings->currency . number_format($supplier->credit_limit, 2); ?></td>
                                            <td>
                                                <?php if ($supplier->status == 'active') { ?>
                                                    <span class="badge badge-success">Active</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">Inactive</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="medicine/editSupplier?id=<?php echo $supplier->id; ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       title="Edit Supplier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="medicine/deleteSupplier?id=<?php echo $supplier->id; ?>" 
                                                       class="btn btn-danger btn-sm ml-1" 
                                                       onclick="return confirm('Are you sure you want to delete this supplier?');"
                                                       title="Delete Supplier">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
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
