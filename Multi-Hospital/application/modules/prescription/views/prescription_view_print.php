<!--main content start-->
<!-- <link href="common/extranal/css/prescription/prescription_view_print.css" rel="stylesheet"> -->

<?php
$doctor = $this->doctor_model->getDoctorById($prescription->doctor);
$patient = $this->patient_model->getPatientById($prescription->patient);
?>
<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-prescription mr-2"></i><?php echo lang('prescription'); ?> : (<?php echo lang('id'); ?>: <?php echo $prescription->id; ?>)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('prescription') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('print') ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-9">
                    <div class="">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12 card bg_container margin_top" id="prescription">
                                <div class="bg_prescription">
                                    <div class="card-body row">
                                        <div class="col-md-8 float-left top_title">
                                            <h2 class='doctor'><?php
                                                                if (!empty($doctor)) {
                                                                    echo $doctor->name;
                                                                } else {
                                                                ?>
                                                    <?php echo $settings->title; ?>
                                                    <h5><?php echo $settings->address; ?></h5>
                                                    <h5><?php echo $settings->phone; ?></h5>
                                                <?php }
                                                ?>
                                            </h2>
                                            <h4>
                                                <?php
                                                if (!empty($doctor)) {
                                                    echo $doctor->profile;
                                                }
                                                ?>
                                            </h4>
                                        </div>
                                        <div class="col-md-4 float-right text-right top_logo"> <img src="<?php echo $settings->logo; ?>" height="150"></div>
                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <h5 class="col-md-4 prescription"><?php echo lang('date'); ?> : <?php echo date('d-m-Y', $prescription->date); ?></h5>
                                            <h5 class="col-md-3 prescription"><?php echo lang('prescription'); ?> <?php echo lang('id'); ?> : <?php echo $prescription->id; ?></h5>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <h5 class="col-md-4 patient_name"><?php echo lang('patient'); ?>: <?php
                                                                                                                if (!empty($patient)) {
                                                                                                                    echo $patient->name;
                                                                                                                }
                                                                                                                ?>
                                            </h5>
                                            <h5 class="col-md-3 patient"><?php echo lang('patient_id'); ?>: <?php
                                                                                                            if (!empty($patient)) {
                                                                                                                echo $patient->id;
                                                                                                            }
                                                                                                            ?></h5>
                                            <h5 class="col-md-3 patient"><?php echo lang('age'); ?>:
                                                <?php
                                                if (!empty($patient)) {
                                                    if (!empty($patient->birthdate)) {
                                                        $birthDate = strtotime($patient->birthdate);
                                                        $birthDate = date('m/d/Y', $birthDate);
                                                        $birthDate = explode("/", $birthDate);
                                                        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
                                                        echo $age . ' ' . lang('years');
                                                    } elseif (!empty($patient->age)) {
                                                        $age = explode('-', $patient->age);
                                                        echo $age[0] . 'Y ' . $age[1] . 'M ' . $age[2] . 'D';
                                                    }
                                                }
                                                ?>
                                            </h5>
                                            <h5 class="col-md-2 patient text-right"><?php echo lang('gender'); ?>: <?php echo $patient->sex; ?></h5>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="col-md-12 clearfix prescription_history row">



                                        <div class="col-md-5 left_card">

                                            <div class="card-body">
                                                <div class="float-left">
                                                    <h5><strong><?php echo lang('history'); ?>: </strong> <br> <br> <?php echo $prescription->symptom; ?></h5>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="card-body">
                                                <div class="float-left">
                                                    <h5><strong><?php echo lang('note'); ?>:</strong> <br> <br> <?php echo $prescription->note; ?></h5>
                                                </div>
                                            </div>




                                            <hr>

                                            <div class="card-body">
                                                <div class="float-left">
                                                    <h5><strong><?php echo lang('advice'); ?>: </strong> <br> <br> <?php echo $prescription->advice; ?></h5>
                                                </div>
                                            </div>




                                        </div>

                                        <div class="col-md-7">

                                            <div class="card-body">
                                                <div class="medicine_pres">
                                                    <strong class="medicine_pres1"> Rx </strong>
                                                </div>
                                                <?php
                                                if (!empty($prescription->medicine)) {
                                                ?>
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <th><?php echo lang('medicine'); ?></th>
                                                            <th><?php echo lang('instruction'); ?></th>
                                                            <th class="text-right"><?php echo lang('frequency'); ?></th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $medicine = $prescription->medicine;
                                                            $medicine = explode('###', $medicine);
                                                            foreach ($medicine as $key => $value) {
                                                            ?>
                                                                <tr>
                                                                    <?php $single_medicine = explode('***', $value); ?>

                                                                    <td class=""><?php echo $this->medicine_model->getMedicineById($single_medicine[0])->name . ' - ' . $single_medicine[1]; ?> </td>
                                                                    <td class=""><?php echo $single_medicine[3] . ' - ' . $single_medicine[4]; ?> </td>
                                                                    <td class="text-right"><?php echo $single_medicine[2] ?> </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                            </div>


                                        </div>

                                    </div>


                                </div>









                                <div class="card-body prescription_footer row">
                                    <div class="col-md-4 float-left">
                                        <hr> <?php echo lang('signature'); ?>
                                    </div>
                                    <div class="col-md-8 float-right text-right">
                                        <h3 class='hospital'><?php echo $settings->title; ?></h3>
                                        <h5><?php echo $settings->address; ?></h5>
                                        <h5><?php echo $settings->phone; ?></h5>
                                    </div>
                                </div>


                            </div>



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-3">
                    <div class="">

                        <!-- /.card-header -->
                        <div class="card-body">




                            <!-- invoice start-->
                            <section class="col-md-12 margin_top">
                                <div class="card-primary clearfix">

                                    <div class="card_button clearfix mb-2">
                                        <div class="text-center invoice-btn no-print float-left">
                                            <a class="btn btn-sm btn-secondary" onclick="javascript:window.print();"><i class="fa fa-print"></i> <?php echo lang('print'); ?> </a>
                                        </div>
                                    </div>

                                    <div class="card_button clearfix mb-2">
                                        <div class="text-center invoice-btn no-print float-left download_button">
                                            <a class="btn btn-sm btn-warning download" id="download"><i class="fa fa-download"></i> <?php echo lang('download'); ?> </a>
                                        </div>
                                    </div>
                                    <div class="card_button clearfix mb-2">
                                        <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
                                            <div class="text-center invoice-btn no-print float-left">
                                                <a class="btn btn-sm btn-info" href='prescription/all'><i class="fa fa-medkit"></i> <?php echo lang('all'); ?> <?php echo lang('prescription'); ?> </a>
                                            </div>
                                        <?php } ?>
                                        <?php if ($this->ion_auth->in_group(array('Doctor'))) { ?>
                                            <div class="text-center invoice-btn no-print float-left">
                                                <a class="btn btn-sm btn-info" href='prescription'><i class="fa fa-medkit"></i> <?php echo lang('all'); ?> <?php echo lang('prescriptions'); ?> </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card_button  mb-2">
                                        <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                            <div class="text-center invoice-btn no-print float-left">
                                                <a class="btn btn-sm btn-success" href="prescription/addPrescriptionView"><i class="fa fa-plus-circle"></i> <?php echo lang('add_prescription'); ?> </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>



<script src="common/js/codearistos.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript">
    var id_pres = "<?php echo $prescription->id; ?>";
</script>
<script src="common/extranal/js/prescription/prescription_print.js"></script>


<!--main content end-->
<!--footer start-->