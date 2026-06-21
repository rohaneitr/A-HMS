<!--sidebar end-->
<!--main content start-->

<div class="content-wrapper bg-gradient-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-truck text-primary mr-3"></i>
                        <?php
                        if (!empty($supplier->id)) {
                            echo 'Edit Supplier';
                        } else {
                            echo 'Add New Supplier';
                        }
                        ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                            <li class="breadcrumb-item"><a href="medicine">Medicine</a></li>
                            <li class="breadcrumb-item"><a href="medicine/suppliers">Suppliers</a></li>
                            <li class="breadcrumb-item active">
                                <?php
                                if (!empty($supplier->id)) {
                                    echo 'Edit Supplier';
                                } else {
                                    echo 'Add Supplier';
                                }
                                ?>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-5">
                            <?php echo validation_errors(); ?>
                            <form role="form" action="medicine/addNewSupplier" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <!-- Basic Information -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-info-circle mr-2"></i>Basic Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Supplier Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" name="name" value='<?php
                                                                                                                        if (!empty($supplier->name)) {
                                                                                                                            echo $supplier->name;
                                                                                                                        }
                                                                                                                        ?>' required="">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Company Name</label>
                                            <input type="text" class="form-control form-control-lg" name="company_name" value='<?php
                                                                                                                                if (!empty($supplier->company_name)) {
                                                                                                                                    echo $supplier->company_name;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Contact Person</label>
                                            <input type="text" class="form-control form-control-lg" name="contact_person" value='<?php
                                                                                                                                    if (!empty($supplier->contact_person)) {
                                                                                                                                        echo $supplier->contact_person;
                                                                                                                                    }
                                                                                                                                    ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Email</label>
                                            <input type="email" class="form-control form-control-lg" name="email" value='<?php
                                                                                                                            if (!empty($supplier->email)) {
                                                                                                                                echo $supplier->email;
                                                                                                                            }
                                                                                                                            ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Phone</label>
                                            <input type="text" class="form-control form-control-lg" name="phone" value='<?php
                                                                                                                            if (!empty($supplier->phone)) {
                                                                                                                                echo $supplier->phone;
                                                                                                                            }
                                                                                                                            ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Mobile</label>
                                            <input type="text" class="form-control form-control-lg" name="mobile" value='<?php
                                                                                                                            if (!empty($supplier->mobile)) {
                                                                                                                                echo $supplier->mobile;
                                                                                                                            }
                                                                                                                            ?>'>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-map-marker-alt mr-2"></i>Address Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Address</label>
                                            <textarea class="form-control form-control-lg" name="address" rows="3"><?php
                                                                                                                    if (!empty($supplier->address)) {
                                                                                                                        echo $supplier->address;
                                                                                                                    }
                                                                                                                    ?></textarea>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">City</label>
                                            <input type="text" class="form-control form-control-lg" name="city" value='<?php
                                                                                                                        if (!empty($supplier->city)) {
                                                                                                                            echo $supplier->city;
                                                                                                                        }
                                                                                                                        ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">State</label>
                                            <input type="text" class="form-control form-control-lg" name="state" value='<?php
                                                                                                                            if (!empty($supplier->state)) {
                                                                                                                                echo $supplier->state;
                                                                                                                            }
                                                                                                                            ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Country</label>
                                            <input type="text" class="form-control form-control-lg" name="country" value='<?php
                                                                                                                            if (!empty($supplier->country)) {
                                                                                                                                echo $supplier->country;
                                                                                                                            }
                                                                                                                            ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Postal Code</label>
                                            <input type="text" class="form-control form-control-lg" name="postal_code" value='<?php
                                                                                                                                if (!empty($supplier->postal_code)) {
                                                                                                                                    echo $supplier->postal_code;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>
                                    </div>

                                    <!-- Financial Information -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-dollar-sign mr-2"></i>Financial Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Tax Number</label>
                                            <input type="text" class="form-control form-control-lg" name="tax_number" value='<?php
                                                                                                                                if (!empty($supplier->tax_number)) {
                                                                                                                                    echo $supplier->tax_number;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">GST Number</label>
                                            <input type="text" class="form-control form-control-lg" name="gst_number" value='<?php
                                                                                                                                if (!empty($supplier->gst_number)) {
                                                                                                                                    echo $supplier->gst_number;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Bank Name</label>
                                            <input type="text" class="form-control form-control-lg" name="bank_name" value='<?php
                                                                                                                                if (!empty($supplier->bank_name)) {
                                                                                                                                    echo $supplier->bank_name;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Bank Account</label>
                                            <input type="text" class="form-control form-control-lg" name="bank_account" value='<?php
                                                                                                                                if (!empty($supplier->bank_account)) {
                                                                                                                                    echo $supplier->bank_account;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Credit Limit</label>
                                            <input type="number" step="0.01" class="form-control form-control-lg" name="credit_limit" value='<?php
                                                                                                                                                if (!empty($supplier->credit_limit)) {
                                                                                                                                                    echo $supplier->credit_limit;
                                                                                                                                                }
                                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Payment Terms</label>
                                            <input type="text" class="form-control form-control-lg" name="payment_terms" value='<?php
                                                                                                                                    if (!empty($supplier->payment_terms)) {
                                                                                                                                        echo $supplier->payment_terms;
                                                                                                                                    }
                                                                                                                                    ?>' placeholder="e.g., Net 30 days">
                                        </div>
                                    </div>

                                    <!-- License Information -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3"><i class="fas fa-certificate mr-2"></i>License Information</h5>
                                        
                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">License Number</label>
                                            <input type="text" class="form-control form-control-lg" name="license_number" value='<?php
                                                                                                                                    if (!empty($supplier->license_number)) {
                                                                                                                                        echo $supplier->license_number;
                                                                                                                                    }
                                                                                                                                    ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Drug License</label>
                                            <input type="text" class="form-control form-control-lg" name="drug_license" value='<?php
                                                                                                                                if (!empty($supplier->drug_license)) {
                                                                                                                                    echo $supplier->drug_license;
                                                                                                                                }
                                                                                                                                ?>'>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Status</label>
                                            <select class="form-control form-control-lg" name="status">
                                                <option value="active" <?php echo (!empty($supplier->status) && $supplier->status == 'active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo (!empty($supplier->status) && $supplier->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="text-uppercase text-sm">Notes</label>
                                            <textarea class="form-control form-control-lg" name="notes" rows="4"><?php
                                                                                                                if (!empty($supplier->notes)) {
                                                                                                                    echo $supplier->notes;
                                                                                                                }
                                                                                                                ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value='<?php
                                                                        if (!empty($supplier->id)) {
                                                                            echo $supplier->id;
                                                                        }
                                                                        ?>'>

                                <div class="text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>Save Supplier
                                    </button>
                                    <a href="medicine/suppliers" class="btn btn-secondary btn-lg px-5 ml-3">
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

<!--main content end-->
<!--footer start-->
