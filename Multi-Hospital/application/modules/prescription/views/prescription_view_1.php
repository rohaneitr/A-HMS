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
                <div class="col-12 col-md-9 prescription_full_width">
                    <div class="card">
                        <div class="card-body">
                            <div id="prescription" class="prescription-container m-4">
                                <div class="prescription-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h2 class="doctor-name h4 mb-3"><?php echo !empty($doctor) ? $doctor->name : $settings->title; ?></h2>
                                            <h4 class="doctor-profile h5 mb-2"><?php echo !empty($doctor) ? $doctor->profile : ''; ?></h4>
                                            <?php if (empty($doctor)) { ?>
                                                <h5 class="h6 mb-2"><?php echo $settings->address; ?></h5>
                                                <h5 class="h6 mb-2"><?php echo $settings->phone; ?></h5>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <img src="<?php echo $settings->logo; ?>" class="img-fluid" alt="Logo" style="max-height: 100px;">
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="prescription-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><?php echo lang('date'); ?>:</strong> <?php echo date('d-m-Y', $prescription->date); ?></p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <p class="mb-1"><strong><?php echo lang('prescription'); ?> <?php echo lang('id'); ?>:</strong> <?php echo $prescription->id; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="patient-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><?php echo lang('patient'); ?>:</strong> <?php echo !empty($patient) ? $patient->name : ''; ?></p>
                                            <p class="mb-1"><strong><?php echo lang('patient_id'); ?>:</strong> <?php echo !empty($patient) ? $patient->id : ''; ?></p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <p class="mb-1"><strong><?php echo lang('age'); ?>:</strong>
                                                <?php
                                                if (!empty($patient)) {
                                                    if (!empty($patient->birthdate)) {
                                                        $birthDate = strtotime($patient->birthdate);
                                                        $age = (date("md", date("U", mktime(0, 0, 0, date('m', $birthDate), date('d', $birthDate), date('Y', $birthDate)))) > date("md")
                                                            ? ((date("Y") - date('Y', $birthDate)) - 1)
                                                            : (date("Y") - date('Y', $birthDate)));
                                                        echo $age . ' ' . lang('years');
                                                    } elseif (!empty($patient->age)) {
                                                        $age = explode('-', $patient->age);
                                                        echo $age[0] . 'Y ' . $age[1] . 'M ' . $age[2] . 'D';
                                                    }
                                                }
                                                ?>
                                            </p>
                                            <p class="mb-1"><strong><?php echo lang('gender'); ?>:</strong> <?php echo $patient->sex; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="row prescription-content">
                                    <div class="col-md-5 prescription-left">
                                        <h5 class="h5 mb-3"><?php echo lang('history'); ?></h5>
                                        <p class="mb-4"><?php echo $prescription->symptom; ?></p>

                                        <h5 class="h5 mb-3"><?php echo lang('note'); ?></h5>
                                        <p class="mb-4"><?php echo $prescription->note; ?></p>

                                        <h5 class="h5 mb-3"><?php echo lang('advice'); ?></h5>
                                        <p class="mb-4"><?php echo $prescription->advice; ?></p>
                                    </div>
                                    <div class="col-md-7 prescription-right">
                                        <h5 class="h5 mb-3"><span class="rx-symbol">℞</span> <?php echo lang('medicine'); ?></h5>
                                        <?php if (!empty($prescription->medicine)) { ?>
                                            <table class="table table-bordered mb-4">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo lang('medicine'); ?></th>
                                                        <th><?php echo lang('instruction'); ?></th>
                                                        <th class="text-right"><?php echo lang('frequency'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $medicine = explode('###', $prescription->medicine);
                                                    foreach ($medicine as $value) {
                                                        $single_medicine = explode('***', $value);
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $this->medicine_model->getMedicineById($single_medicine[0])->name . ' - ' . $single_medicine[1]; ?></td>
                                                            <td><?php echo $single_medicine[3] . ' - ' . $single_medicine[4]; ?></td>
                                                            <td class="text-right"><?php echo $single_medicine[2] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="prescription-footer mt-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="doctor-name h5 mb-2"><?php echo $doctor->name; ?></p>
                                            <?php if (!empty($doctor->signature) && file_exists($doctor->signature)): ?>
                                                <img src="<?php echo $doctor->signature; ?>" alt="Doctor's Signature" class="img-fluid mb-2" style="max-height: 50px;">
                                            <?php endif; ?>
                                            <p class="signature-label small mt-5"><?php echo lang('signature'); ?></p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <h4 class="hospital-name h4 mb-2"><?php echo $settings->title; ?></h4>
                                            <p class="mb-1"><?php echo $settings->address; ?></p>
                                            <p class="mb-1"><?php echo $settings->phone; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <footer class="text-center text-muted small">
                                    <p>This prescription is valid for 30 days from the date of issue. Please consult your doctor for any changes or concerns.</p>
                                    <p>© <?php echo date('Y'); ?> <?php echo $settings->title; ?>. All rights reserved.</p>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 no-print">
                    <div class="card">
                        <div class="card-body">
                            <div class="action-buttons">
                                <button class="btn btn-primary btn-block mb-2" onclick="window.print();"><i class="fa fa-print"></i> <?php echo lang('print'); ?></button>
                                <button class="btn btn-warning btn-block mb-2" id="download"><i class="fa fa-download"></i> <?php echo lang('download'); ?></button>
                                <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
                                    <a href='prescription/all' class="btn btn-info btn-block mb-2"><i class="fa fa-list"></i> <?php echo lang('all'); ?> <?php echo lang('prescription'); ?></a>
                                <?php } ?>
                                <?php if ($this->ion_auth->in_group(array('Doctor'))) { ?>
                                    <a href='prescription' class="btn btn-info btn-block mb-2"><i class="fa fa-list"></i> <?php echo lang('all'); ?> <?php echo lang('prescriptions'); ?></a>
                                <?php } ?>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                    <a href="prescription/addPrescriptionView" class="btn btn-success btn-block"><i class="fa fa-plus-circle"></i> <?php echo lang('add_prescription'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>

<script>
    document.getElementById('download').addEventListener('click', function() {
        // Create a new jsPDF instance
        var pdf = new jsPDF('p', 'mm', 'a4');

        // Get the prescription div
        var element = document.getElementById('prescription');

        // Use html2canvas to render the element
        html2canvas(element, {
            scale: 2, // Increase scale for better quality
            useCORS: true, // Enable CORS to load external images
            backgroundColor: '#FFFFFF', // Set white background
            onrendered: function(canvas) {
                var imgData = canvas.toDataURL('image/png');
                var pageWidth = 210; // A4 width in mm
                var pageHeight = 297; // A4 height in mm
                var margin = 10; // Margin in mm
                var imgWidth = pageWidth - (2 * margin);
                var imgHeight = canvas.height * imgWidth / canvas.width;
                var heightLeft = imgHeight;
                var position = margin;

                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= (pageHeight - (2 * margin));

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight + margin;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                    heightLeft -= (pageHeight - (2 * margin));
                }

                pdf.save('prescription_' + id_pres + '.pdf');
            }
        });
    });
</script>



<style>
    @media print {
        .prescription_full_length {
            width: 100% !important;
        }
    }
</style>






<!--main content end-->
<!--footer start-->




<script src="common/js/codearistos.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript">
    var id_pres = "<?php echo $prescription->id; ?>";
</script>
<script src="common/extranal/js/prescription/prescription_view_1.js"></script>