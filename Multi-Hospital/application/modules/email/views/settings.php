<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-envelope mr-2"></i> <?php echo lang('email_settings') ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('email_settings') ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo lang('All the email settings'); ?></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clearfix">
                                <?php echo validation_errors(); ?>
                                <form role="form" action="email/addNewSettings" class="clearfix" method="post" enctype="multipart/form-data">
                                    <?php if ($settings->type == 'Domain Email') { ?>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> <?php echo lang('admin'); ?> <?php echo lang('email'); ?></label>
                                            <input type="text" class="form-control form-control-lg" name="email" id="exampleInputEmail1" value='<?php
                                                                                                                                                if (!empty($settings->admin_email)) {
                                                                                                                                                    echo $settings->admin_email;
                                                                                                                                                }
                                                                                                                                                ?>' placeholder="From which you want to send the email">
                                        </div>
                                        <code>
                                            <?php echo lang('email_settings_instruction_1') ?>
                                            <br>
                                            <?php echo lang('email_settings_instruction_2') ?>
                                        </code>
                                    <?php } ?>
                                    <?php if ($settings->type == 'Smtp') { ?>
                                        <div class="form-group emailSelectCompany">
                                            <label for="exampleInputEmail1"> <?php echo lang('email'); ?> <?php echo lang('company'); ?></label>
                                            <select class="form-control form-control-lg m-bot15 pos_select" id="emailCompany" name="email_company" required="">
                                                <option><?php echo lang('select'); ?></option>
                                                <option value="gmail" <?php
                                                                        if ($settings->mail_provider == 'gmail') {
                                                                            echo 'selected';
                                                                        }
                                                                        ?>><?php echo lang('gmail'); ?></option>
                                                <option value="yahoo" <?php
                                                                        if ($settings->mail_provider == 'yahoo') {
                                                                            echo 'selected';
                                                                        }
                                                                        ?>><?php echo lang('yahoo_mail'); ?></option>
                                                <option value="zoho" <?php
                                                                        if ($settings->mail_provider == 'zoho') {
                                                                            echo 'selected';
                                                                        }
                                                                        ?>><?php echo lang('zoho_mail'); ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group m-bot15">
                                            <label for="exampleInputEmail1"> <?php echo lang('email'); ?></label>
                                            <?php
                                            if (!empty($settings->user)) {
                                                $extension = explode("@", $settings->user);
                                            }
                                            ?>
                                            <input type="text" class="form-control form-control-lg" name="user" pattern="[^@,]+" id="emailAddress" value='<?php
                                                                                                                                                            if (!empty($settings->user)) {
                                                                                                                                                                echo $extension[0];
                                                                                                                                                            }
                                                                                                                                                            ?>' placeholder="Email user" required="">
                                            <span class="input-group-addon" id="mailExtension"><?php
                                                                                                if (!empty($settings->user)) {
                                                                                                    echo '@' . $extension[1];
                                                                                                }
                                                                                                ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> <?php echo lang('email'); ?> <?php echo lang('app') ?> <?php echo lang('password'); ?></label>
                                            <input type="password" class="form-control form-control-lg" name="password" id="exampleInputEmail1" value='<?php
                                                                                                                                                        if (!empty($settings->password)) {
                                                                                                                                                            echo base64_decode($settings->password);
                                                                                                                                                        }
                                                                                                                                                        ?>' placeholder="<?php echo lang('email') . " " . lang('password'); ?>" required="">
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="id" value='<?php
                                                                            if (!empty($settings->id)) {
                                                                                echo $settings->id;
                                                                            }
                                                                            ?>'>
                                    <input type="hidden" name="type" value='<?php
                                                                            if (!empty($settings->type)) {
                                                                                echo $settings->type;
                                                                            }
                                                                            ?>'>
                                    <code>
                                        <?php echo lang('yahoo_mail_password_instruction1') ?>
                                        <br>
                                        >><?php echo lang('yahoo_mail_password_instruction2') ?><br>
                                        >> <?php echo lang('yahoo_mail_password_instruction3') ?><br>
                                        <?php echo lang('yahoo_mail_password_instruction4') ?>
                                    </code>
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-info float-right"><?php echo lang('submit'); ?></button>
                                    </div>
                                </form>
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
<script src="common/extranal/js/email/settings.js"></script>