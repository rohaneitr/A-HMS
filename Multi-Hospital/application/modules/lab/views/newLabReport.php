<!--sidebar end-->
<!--main content start-->

<!-- <link href="common/extranal/css/lab/lab.css" rel="stylesheet"> -->


<!-- <style>
    @media print {
        .print-full-width {
            width: 100% !important;
        }

        .flex-wrapper{
            border: none;
        }
    }
</style> -->

<?php
$patient = $this->db->get_where('patient', array('id' => $lab->patient))->row();
$invoice_details = "";
$invoice_details = $this->db->get_where('payment', array('id' => $lab->invoice_id))->row();
?>

<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-md-8">
                    <h1 class="font-weight-bold"><i class="fas fa-flask mr-2"></i><?php echo lang('lab_report') ?> : <?php echo $patient->name; ?> (<?php echo lang('id') ?>: <?php echo $patient->id; ?>), <?php echo lang('invoice_id') ?>: <?php echo $invoice_details->id; ?></h1>
                </div>
                <div class="col-md-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('lab_report') ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <section class="col-md-7 print-full-width">
                                    <div class="flex-wrapper" style="border: 1px solid #000; background: #fff;">
                                        <?php if ($redirect != 'download1') { ?>
                                            <div id="invoice_header">
                                                <table style="width: 100%">
                                                    <tr>
                                                        <td style="width: 25%">
                                                            <img alt="" src="<?php echo site_url($this->settings_model->getSettings()->logo); ?>" width="150" height="auto" style="margin-top:-45px; margin-left: 5px;">
                                                        </td>
                                                        <td>
                                                            <h4 style="margin-bottom: 10px; font-weight: 800; margin-top: -20px;"><?php echo $settings->title; ?></h4>
                                                            <h6 style="margin-bottom: 10px;"><?php echo $settings->address; ?></h6>
                                                            <h4 style="line-height: 20px"><?php echo lang('phone'); ?>: <br><?php echo $settings->phone; ?></h4>
                                                        </td>
                                                        <td>
                                                            <table style="margin-top: 10px;">
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <label class="control_label"><?php echo lang('name'); ?></label> <span class="info_text">: <?php echo $patient->name; ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <?php
                                                                    $age = explode('-', $patient->age);
                                                                    if (count($age) == 3) {
                                                                    ?>
                                                                        <td style="padding-right: 10px;"><label class="control_label"><?php echo lang('age'); ?></label> <span class="info_text">: <?php echo $age[0] . " Y " . $age[1] . " M " . $age[2] . " D"; ?></td></span>
                                                                    <?php } else { ?>
                                                                        <td style="padding-right: 10px;"><label class="control_label"><?php echo lang('age'); ?></label> <span class='info_text'>: </span></td>
                                                                    <?php }
                                                                    ?>
                                                                    <td>
                                                                        <label class="control_label"><?php echo lang('gender'); ?></label> <span class="info_text">: <?php echo $patient->sex; ?></span>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="padding-right: 10px;"><label class="control_label">HN</label> <span class="info_text">: 0000000<?php echo $patient->id; ?></span></td>
                                                                    <td><label class="control_label"><?php echo lang('phone'); ?></label> <span class="info_text">: <?php echo $patient->phone; ?></span></td>
                                                                </tr>
                                                                <?php
                                                                $doctor_details = "";
                                                                if ($invoice_details) {
                                                                    if ($invoice_details->doctor) {
                                                                        $doctor_details = $this->db->get_where('doctor', array('id' => $invoice_details->doctor))->row();
                                                                    }
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td style="padding-right: 10px;"><label class="control_label">VN</label> <span class="info_text">: 0000000<?php echo $lab->invoice_id; ?></span></td>
                                                                    <td><label class="control_label">VN <?php echo lang('date'); ?></label> <span class="info_text">: <?php
                                                                                                                                                                        if ($invoice_details) {
                                                                                                                                                                            echo date('d/m/Y h:i A', $invoice_details->date);
                                                                                                                                                                        }
                                                                                                                                                                        ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <label class="control_label"><?php echo lang('doctor'); ?></label>
                                                                        <?php if ($doctor_details) { ?>
                                                                            <span class="info_text">: <?php echo $doctor_details->name; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <?php if ($doctor_details) { ?>
                                                                            <span class="info_text"><?php echo $doctor_details->profile; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <hr class="table-qr-hr">
                                                <table style="width: 100%">
                                                    <tr>
                                                        <td style="width: 50%; padding-left: 20px; display: inline-flex">
                                                            <label style="margin-bottom: 10px;">HN:</label>
                                                            <img alt="testing" src="<?php echo site_url('lab/barcode') ?>?text=000000000<?php echo $patient->id; ?>&print=true" />
                                                        </td>

                                                        <td style="width: 50%; text-align: right; padding-right: 20px; display: inline-flex; justify-content: end;">
                                                            <label style="margin-bottom: 10px;">VN:</label>
                                                            <img alt="testing" src="<?php echo site_url('lab/barcode') ?>?text=000000000<?php echo $lab->invoice_id; ?>&print=true" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        <?php } ?>
                                        <hr class="table-qr-hr table-qr-hr_oo">
                                        <div class="reportBlock ck-content" style="padding: 10px">
                                            <?php echo $lab->report; ?>
                                        </div>
                                        <div id="footer" style="padding: 10px;">
                                            <?php
                                            $signature = "";
                                            if ($lab->signed_by) {
                                                $laboratorist = $this->db->get_where('laboratorist', array('ion_user_id' => $lab->signed_by))->row();

                                                if ($laboratorist) {
                                                    $signature = $laboratorist->signature;
                                                }
                                            ?>

                                            <?php } ?>
                                            <!-- <hr style="margin: 10px 0px !important;"> -->
                                            <div class="invoice_footer">
                                                <!-- <img src="<?php echo $signature; ?>" width="100%" height="80px; margin-bottom: 100px;"> -->
                                                <table style="width: 100%">
                                                    <tr>
                                                        <td id="footer_done" style="padding-right: 20px;"><span class="info_text"><?php echo lang('done_by'); ?>: <?php echo $lab->done_by; ?></span></td>
                                                        <td id="footer_second">
                                                            <span class='info_text'><?php $lab->test_status_date != null ? date('d/m/Y h:i A') : ""; ?></span>
                                                        </td>
                                                        <td id="footer_third" style="text-align: right;">
                                                            <p style="font-weight: bold">
                                                                <?php
                                                                if ($lab->updated_on) {
                                                                    echo lang('updated') . ': ' . date('l d M Y h:s A', $lab->updated_on);
                                                                }
                                                                ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                    </div>

                                    <?php if ($redirect != 'download') { ?>
                                        <!-- <div class="col-md-12 invoice_footer ff">

                                <?php //echo $settings->footer_invoice_message; 
                                ?>


                            </div> -->
                                    <?php } ?>
                                </section>
                                <?php if ($redirect != 'download') { ?>
                                    <section class="col-md-3 no-print">
                                        <div style="background: transparent;">
                                            <a class='btn btn-warning mb-2' onclick="window.print()"><i class='fa fa-print'></i> <?php echo lang('print'); ?></a><br>
                                            <a class='btn btn-success mb-2' href='<?php echo site_url('lab/testPdf?id=' . $lab->id); ?>'><i class='fa fa-download'></i> <?php echo lang('download'); ?></a><br>


                                            <?php if ($this->ion_auth->in_group(array('admin', 'Laboratorist', 'Doctor'))) { ?>
                                                <a class='btn btn-danger mb-2' href="<?php echo site_url('lab?id=' . $lab->id); ?>"><i class='fa fa-edit'></i> <?php echo lang('edit_report'); ?></a><br>
                                            <?php } ?>

                                            <!-- <a href="lab/sendLabreport?id=<?php echo $lab->id; ?>" class="btn  btn-info send"> <i class="fa fa-paper-plane"></i> <?php echo lang('send_report'); ?> </a> -->
                                            <form role="form" action="lab/sendLabreport" method="post" enctype="multipart/form-data">
                                                <div class="radio radio_button">
                                                    <label>
                                                        <input type="radio" name="radio" id="optionsRadios2" value="patient" checked="checked">
                                                        <?php echo lang('send_report_to_patient'); ?>
                                                    </label>
                                                </div>
                                                <div class="radio radio_button">
                                                    <label>
                                                        <input type="radio" name="radio" id="optionsRadios2" value="other">
                                                        <?php echo lang('send_report_to_others'); ?>
                                                    </label>
                                                </div>
                                                <input type="hidden" name="id" value="<?php echo $lab->id; ?>">
                                                <div class="radio other">
                                                    <label>
                                                        <?php echo lang('email'); ?> <?php echo lang('address'); ?>
                                                        <input type="email" name="other_email" value="" class="form-control form-control-lg">
                                                    </label>

                                                </div>

                                                <button type="submit" name="submit" class="btn btn-info float-left"><i class="fa fa-location-arrow"></i> <?php echo lang('send'); ?></button>

                                            </form>
                                        </div>
                                    </section>
                                <?php } ?>
                            </div>
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





<!--main content end-->
<!--footer start-->



<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
    var select_email = "<?php echo lang('select_email'); ?>";
</script>
<script src="common/extranal/js/lab/lab.js"></script>

<script>
    $(document).ready(function() {
        var prevRowHeight = 0;
        $("p, tr, img").each(function() {
            console.log(prevRowHeight);
            var maxHeight = 750;
            var eachRowHeight = $(this).height();
            if ((prevRowHeight + eachRowHeight) > maxHeight) {
                prevRowHeight = 0;
                $(this).before('<div class="page_breaker"></div>');
                console.log("add page break before");
            }
            prevRowHeight = prevRowHeight + $(this).height();
        });

    });
</script>