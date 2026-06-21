<div class="content-wrapper bg-light">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo lang('emergency_list'); ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    <i class="fas fa-plus"></i> <?php echo lang('add_new_emergency'); ?>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="emergencyTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('patient'); ?></th>
                                        <th><?php echo lang('doctor'); ?></th>
                                        <th><?php echo lang('emergency_type'); ?></th>
                                        <th><?php echo lang('description'); ?></th>
                                        <th><?php echo lang('status'); ?></th>
                                        <th><?php echo lang('date'); ?></th>
                                        <th><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                            </table>
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

    <!-- Add Emergency Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title font-weight-bold"><?php echo lang('add_new_emergency'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" action="emergency/addNew" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="patient"><?php echo lang('patient'); ?></label>
                            <select class="form-control select2" name="patient" id="patient" style="width: 100%;" data-placeholder="<?php echo lang('select_patient'); ?>">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="doctor"><?php echo lang('doctor'); ?></label>
                            <select class="form-control select2" name="doctor" id="doctor" style="width: 100%;" data-placeholder="<?php echo lang('select_doctor'); ?>">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="emergency_type"><?php echo lang('emergency_type'); ?></label>
                            <input type="text" class="form-control form-control-lg" name="emergency_type" id="emergency_type" placeholder="Enter emergency type">
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo lang('description'); ?></label>
                            <textarea class="form-control form-control-lg" name="description" id="description" rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status"><?php echo lang('status'); ?></label>
                            <input type="text" class="form-control form-control-lg" name="status" id="status" placeholder="Enter status">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
                        <button type="submit" class="btn btn-primary" name="submit"><?php echo lang('submit'); ?></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Edit Emergency Modal -->
    <div class="modal fade" id="myModal2">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title font-weight-bold"><?php echo lang('edit_emergency'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" id="editEmergencyForm" action="emergency/addNew" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_patient"><?php echo lang('patient'); ?></label>
                            <select class="form-control select2" name="patient" id="edit_patient" style="width: 100%;" data-placeholder="<?php echo lang('select_patient'); ?>">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_doctor"><?php echo lang('doctor'); ?></label>
                            <select class="form-control select2" name="doctor" id="edit_doctor" style="width: 100%;" data-placeholder="<?php echo lang('select_doctor'); ?>">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_emergency_type"><?php echo lang('emergency_type'); ?></label>
                            <input type="text" class="form-control form-control-lg" name="emergency_type" id="edit_emergency_type" placeholder="Enter emergency type">
                        </div>
                        <div class="form-group">
                            <label for="edit_description"><?php echo lang('description'); ?></label>
                            <textarea class="form-control form-control-lg" name="description" id="edit_description" rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_status"><?php echo lang('status'); ?></label>
                            <input type="text" class="form-control form-control-lg" name="status" id="edit_status" placeholder="Enter status">
                        </div>
                        <input type="hidden" name="id" id="edit_id">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
                        <button type="submit" class="btn btn-primary" name="submit"><?php echo lang('submit'); ?></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<!-- /.content-wrapper -->

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jszip/jszip.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/pdfmake/pdfmake.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/pdfmake/vfs_fonts.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/select2/js/select2.full.min.js'); ?>"></script>

<script>
    $(function() {
        $('.select2').select2({
            ajax: {
                url: 'emergency/getPatientInfo',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#doctor, #edit_doctor').select2({
            ajax: {
                url: 'emergency/getDoctorInfo',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#emergencyTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('emergency/getEmergencyList'); ?>",
                "type": "POST"
            },
            "columns": [{
                    "data": "patient_name"
                },
                {
                    "data": "doctor_name"
                },
                {
                    "data": "emergency_type"
                },
                {
                    "data": "description"
                },
                {
                    "data": "status"
                },
                {
                    "data": "date"
                },
                {
                    "data": "options"
                }
            ]
        }).buttons().container().appendTo('#emergencyTable_wrapper .col-md-6:eq(0)');

        $(".editbutton").click(function(e) {
            e.preventDefault();
            var iid = $(this).data('id');
            $('#editEmergencyForm').trigger("reset");
            $.ajax({
                url: 'emergency/editEmergencyByJason?id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
                success: function(response) {
                    $('#edit_id').val(response.emergency.id);

                    var patientOption = new Option(response.emergency.patient, response.emergency.patient_id, true, true);
                    $('#edit_patient').append(patientOption).trigger('change');

                    var doctorOption = new Option(response.emergency.doctor, response.emergency.doctor_id, true, true);
                    $('#edit_doctor').append(doctorOption).trigger('change');

                    $('#edit_emergency_type').val(response.emergency.emergency_type);
                    $('#edit_description').val(response.emergency.description);
                    $('#edit_status').val(response.emergency.status);
                }
            });
        });
    });
</script>