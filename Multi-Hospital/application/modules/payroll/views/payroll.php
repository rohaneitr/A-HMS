<div class="content-wrapper bg-gradient-light" style="min-height: 2726.9px;">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-money-check-alt text-primary mr-3"></i>
                        <?php echo lang('payroll') ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home"><?php echo lang('home'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('payroll'); ?></li>
                        </ol>
                    </nav>
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
                            <h3 class="card-title text-black font-weight-800"><?php echo lang('All the payroll informations'); ?></h3>
                        </div>

                        <div class="card-body bg-light">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-uppercase font-weight-bold text-muted"><?php echo lang('month'); ?></label>
                                        <select class="form-control form-control-lg shadow-sm js-example-basic-single" id="payroll_month">
                                            <?php
                                            foreach ($months as $month) {
                                                if ($month == date('F')) {
                                            ?>
                                                    <option value="<?php echo $month; ?>" selected><?php echo $month; ?></option>
                                                <?php
                                                    break;
                                                } else {
                                                ?>
                                                    <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-uppercase font-weight-bold text-muted"><?php echo lang('year'); ?></label>
                                        <select class="form-control form-control-lg shadow-sm js-example-basic-single" id="payroll_year">
                                            <?php foreach ($years as $year) { ?>
                                                <option value="<?php echo $year; ?>" <?php if ($year == date('Y')) { ?>selected<?php } ?>><?php echo $year; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button class="btn btn-success btn-lg px-4 py-3 generatePayroll">
                                    <i class="fas fa-paper-plane mr-2"></i> <?php echo lang('generate'); ?>
                                </button>
                            </div>

                            <table class="table table-hover" id="salary-sample">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase"><?php echo lang('staff'); ?></th>
                                        <th class="font-weight-bold text-uppercase"><?php echo lang('salary'); ?></th>
                                        <th class="font-weight-bold text-uppercase"><?php echo lang('paid_on'); ?></th>
                                        <th class="font-weight-bold text-uppercase"><?php echo lang('status'); ?></th>
                                        <th class="font-weight-bold text-uppercase no-print"><?php echo lang('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($employees)) {
                                        for ($i = 0; $i < count($employees); $i++) {
                                    ?>
                                            <tr>
                                                <td><?php echo $employees[$i][0]; ?></td>
                                                <td><?php echo $employees[$i][1]; ?></td>
                                                <td><?php echo $employees[$i][2]; ?></td>
                                                <td><?php echo $employees[$i][3]; ?></td>
                                                <td><?php echo $employees[$i][4]; ?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>























<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">
    var language = "<?php echo $this->language; ?>";
</script>

<script src="common/extranal/js/payroll/payroll.js"></script>