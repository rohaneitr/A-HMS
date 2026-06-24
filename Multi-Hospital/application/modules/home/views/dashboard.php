<!DOCTYPE html>
<html lang="en" <?php
                if (!$this->ion_auth->in_group(array('superadmin'))) {

                  $this->db->where('hospital_id', $this->hospital_id);
                  $settings_lang = $this->db->get('settings')->row()->language;
                  if ($this->language == 'arabic') {
                ?> dir="rtl" <?php } else { ?> dir="ltr" <?php
                                                        }
                                                      } else {
                                                        $this->db->where('hospital_id', 'superadmin');
                                                        $settings_lang = $this->db->get('settings')->row()->language;
                                                        if ($this->language == 'arabic') {
                                                          ?> dir="rtl" <?php } else { ?> dir="ltr" <?php
                                                                                                  }
                                                                                                }
                                                                                                    ?>>

<head>
  <base href="<?php echo base_url(); ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  $class_name = $this->router->fetch_class();
  $class_name_lang = lang($class_name);
  if (empty($class_name_lang)) {
    $class_name_lang = $class_name;
  }
  ?>

  <title> <?php echo $class_name_lang; ?> |
    <?php
    if ($this->ion_auth->in_group(array('superadmin'))) {
      $this->db->where('hospital_id', 'superadmin');
    } else {
      $this->db->where('hospital_id', $this->hospital_id);
    }
    ?>
    <?php
    $settings = $this->db->get('settings')->row();
    echo $settings->system_vendor;
    ?>
  </title>

  <!-- <link rel="stylesheet" href="common/css/bootstrap-select.min.css"> -->

  <!-- Google Fonts -->

  <!-- design the sidebar with more professional css  -->



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="adminlte/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="adminlte/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="adminlte/plugins/summernote/summernote-bs4.min.css">


  <link rel="stylesheet" href="adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="adminlte/dist/css/changes.css">

  <link rel="stylesheet" href="adminlte/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="adminlte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="adminlte/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="adminlte/plugins/daterangepicker/daterangepicker.css">


  <link rel="stylesheet" href="adminlte/plugins/fullcalendar/main.css">
  <link rel="stylesheet" href="adminlte/plugins/flag-icon-css/css/flag-icon.min.css">

  <link rel="stylesheet" href="common/assets/bootstrap-datepicker/css/bootstrap-datepicker.css" />
  <link rel="stylesheet" type="text/css" href="common/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="common/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
  <link rel="stylesheet" href="common/assets/bootstrap-timepicker/compiled/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="common/assets/jquery-multi-select/css/multi-select.css" />
  <link rel="stylesheet" type="text/css" href="common/css/lightbox.css" />


  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="adminlte/plugins/toastr/toastr.min.css">

  <!-- dropzonejs -->
  <link rel="stylesheet" href="adminlte/plugins/dropzone/min/dropzone.min.css">





  <?php

  if ($this->language == 'arabic') { ?>
    <link rel="stylesheet" href="adminlte/dist/css/rtl.css">
  <?php } ?>

  <!-- <link rel="stylesheet" href="common/css/bootstrap-select-country.min.css"> -->

  <link rel="stylesheet" href="common/assets/bootstrap-datepicker/css/bootstrap-datepicker.css" />
  <link rel="stylesheet" href="<?php echo base_url('common/css/custom-style.css'); ?>">
</head>

<body class="hold-transition sidebar-mini <?php
                                          if ($this->ion_auth->user()->row()->sidebar != 1) {
                                            echo 'sidebar-collapse';
                                          }

                                          if ($this->session->userdata('darkMode') == 1) {
                                            echo 'dark-mode ';
                                          } else {
                                          }

                                          ?> ">



  <div id="loader" class="loader" style="display:none;"></div>
  <style>
    .loader {
      border: 16px solid #f3f3f3;
      /* Light grey */
      border-top: 16px solid #3498db;
      /* Blue */
      border-radius: 50%;
      width: 80px;
      height: 80px;
      animation: spin 1s linear infinite;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
    }

    /* Loader animation */
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>



  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-transparent py-3 <?php
                                                                          if ($this->session->userdata('darkMode') == 1) {
                                                                            echo 'navbar-dark';
                                                                          } else {
                                                                            echo 'navbar-light';
                                                                          }
                                                                          ?>">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link collapse-server" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>


        <!-- <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li> -->
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <?php if (!$this->ion_auth->in_group(array('superadmin'))) { ?>
                            <div style="margin:auto">
                                <?php $this->db->where('hospital_id', $this->hospital_id);
                                        $query = $this->db->get('settings')->row(); 
                                        echo "Timezone : " . $query->timezone; ?>
                            </div>
                            <?php } ?>

        <?php $this->load->view('available'); ?>

        <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
          <li class="nav-item dropdown d-none d-md-block">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="fa fa-link"></i>
              <span class="badge badge-info navbar-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

              <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) { ?>
                <?php if (in_array('finance', $this->modules)) { ?>
                  <a href="finance/addPaymentView" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('add_payment'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-money-check"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>



              <?php if ($this->ion_auth->in_group(array('admin', 'Nurse', 'Accountant', 'Pharmacist', 'Doctor', 'Laboratorist', 'Receptionist'))) { ?>
                <?php if (in_array('appointment', $this->modules)) { ?>
                  <a href="appointment/addNewView" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('add_appointment'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-calendar-check"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>



              <?php if ($this->ion_auth->in_group(array('admin', 'Nurse', 'Accountant', 'Pharmacist', 'Doctor', 'Laboratorist', 'Receptionist'))) { ?>
                <?php if (in_array('patient', $this->modules)) { ?>
                  <a href="patient/addNewView" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('add_patient'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-user"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>



              <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
                <?php if (in_array('doctor', $this->modules)) { ?>
                  <a href="doctor/addNewView" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('add_doctor'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-user"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>



              <?php if ($this->ion_auth->in_group(array('Doctor'))) { ?>
                <?php if (in_array('prescription', $this->modules)) { ?>
                  <a href="prescription/addPrescriptionView" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('add_prescription'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-user"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>

              <?php if ($this->ion_auth->in_group(array('admin', 'Nurse', 'Accountant', 'Pharmacist', 'Doctor', 'Laboratorist', 'Receptionist'))) { ?>
                <?php if (in_array('lab', $this->modules)) { ?>
                  <a href="lab" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('lab'); ?> <?php echo lang('reports'); ?>
                          <span class="float-right text-sm text-danger"><i class="fa fa-flask"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>

              <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) { ?>
                <?php if (in_array('finance', $this->modules)) { ?>
                  <a href="finance/dueCollection" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          <?php echo lang('due_collection'); ?>
                          <span class="float-right text-sm text-danger"><i class="fas fa-money-check"></i></span>
                        </h3>
                      </div>
                    </div>
                    <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                <?php } ?>
              <?php } ?>




            </div>
          </li>
        <?php } ?>



        <!-- Messages Dropdown Menu -->

        <?php if ($this->ion_auth->in_group(array('admin', 'Nurse', 'Accountant', 'Pharmacist', 'Doctor', 'Laboratorist', 'Receptionist'))) { ?>
          <?php if (in_array('chat', $this->modules)) { ?>
            <li class="nav-item dropdown d-none d-md-block">
              <a class="nav-link" href="chat" title="<?php echo lang('chat'); ?>">
                <i class="far fa-comments"></i>
                <span class="badge badge-info navbar-badge text-xs bg-transparent" id="chatCount"></span>
              </a>
            </li>
          <?php } ?>
        <?php } ?>
        <!-- Notifications Dropdown Menu -->





        <?php if ($this->ion_auth->in_group(array('admin', 'Accountant'))) : ?>
          <?php if (in_array('finance', $this->modules)) : ?>
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#" title="<?php echo lang('payment'); ?>">
                <i class="fa fa-money-check"></i>
                <?php
                $this->db->where('hospital_id', $this->hospital_id);
                $query = $this->db->get('payment');
                $query = $query->result();
                $payment_number = 0;
                foreach ($query as $payment) {
                  $payment_date = date('y/m/d', $payment->date);
                  if ($payment_date == date('y/m/d')) {
                    $payment_number++;
                  }
                }
                ?>
                <span class="badge badge-danger navbar-badge text-xs bg-transparent"><?= $payment_number; ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?= $payment_number; ?>
                  <?php if ($payment_number <= 1) {
                    echo lang('payment_today');
                  } else {
                    echo lang('payments_today');
                  } ?>
                </span>
                <div class="dropdown-divider"></div>
                <a href="finance/payment" class="dropdown-item">
                  <?= lang('see_all_payments'); ?>
                  <span class="float-right text-muted text-sm"><?= ($payment_number > 0) ? 'Available' : 'Not Available'; ?></span>
                </a>
                <!-- Add more notifications or a footer link similar to "See All Notifications" if needed -->
              </div>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($this->ion_auth->in_group(['admin', 'Accountant', 'Doctor', 'Nurse', 'Laboratorist'])) : ?>
          <?php if (in_array('patient', $this->modules)) : ?>
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#" title="<?php echo lang('patient'); ?>">
                <i class="fa fa-user"></i>
                <?php
                $this->db->where('hospital_id', $this->hospital_id);
                $this->db->where('add_date', date('m/d/y'));
                $query = $this->db->get('patient');
                $query = $query->result();
                $patient_number = count($query);
                ?>
                <span class="badge badge-warning navbar-badge text-xs bg-transparent"><?= $patient_number; ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                  <?= $patient_number; ?>
                  <?php if ($patient_number <= 1) : ?>
                    <?= lang('patient_registerred_today'); ?>
                  <?php else : ?>
                    <?= lang('patients_registerred_today'); ?>
                  <?php endif; ?>
                </span>
                <div class="dropdown-divider"></div>
                <a href="patient" class="dropdown-item">
                  <?= lang('see_all_patients'); ?>
                  <span class="float-right text-muted text-sm"><?= ($patient_number > 0) ? 'Available' : 'Not Available'; ?></span>
                </a>
                <!-- Add more notifications or a footer link similar to "See All Notifications" if needed -->
              </div>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <?php


        $languages = $this->db->get('language')->result();

        foreach ($languages as $language) {
          if ($this->language == $language->language) {
            $flagIcon = $language->flag_icon;
          }
        }

        ?>



        <!-- Language Dropdown Menu -->
        <?php if ($this->ion_auth->in_group(array('admin', 'superadmin'))) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="<?php echo lang('language'); ?>">
              <i class="flag-icon flag-icon-<?php echo $flagIcon; ?>"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">

              <?php

              foreach ($languages as $language) {

              ?>
                <a href="settings/changeLanguageFlag?lang=<?php echo $language->language; ?>" class="dropdown-item <?php if ($this->language ==  $language->language) {
                                                                                                                      echo 'active';
                                                                                                                    } ?>">
                  <i class="flag-icon flag-icon-<?php echo $language->flag_icon; ?> mr-2"></i> <?php echo $language->language; ?>
                </a>
              <?php } ?>


            </div>
          </li>
        <?php } ?>

        <?php if ($this->ion_auth->in_group(array('Patient', 'Doctor'))) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="">
              <i class="flag-icon flag-icon-<?php echo $flagIcon; ?>"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">


              <?php
              $languages = $this->db->get('language')->result();
              foreach ($languages as $language) {

              ?>
                <a href="profile/changeLanguageFlag?lang=<?php echo $language->language; ?>" class="dropdown-item <?php if ($this->language == $language->language) {
                                                                                                                    echo 'active';
                                                                                                                  } ?>">
                  <i class="flag-icon flag-icon-<?php echo $language->flag_icon; ?> mr-2"></i> <?php echo ucfirst($language->language); ?>
                </a>


              <?php } ?>


            </div>
          </li>
        <?php } ?>

        <li class="nav-item d-none d-md-block">
          <a class="nav-link" title="<?php echo lang('full_screen'); ?>" data-widget="fullscreen" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link" title="<?php echo lang('log_out'); ?>" href="auth/logout" role="button">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </li>
        <!-- <li class="nav-item d-flex align-items-center">
          <div class="custom-control custom-switch d-flex align-items-center">
            <input type="checkbox" class="custom-control-input me-2" id="darkModeToggle" <?php if ($this->session->userdata('darkMode') == 1) echo 'checked'; ?>>
            <label class="custom-control-label d-flex align-items-center" for="darkModeToggle">
              <i class="fas fa-moon text-secondary"></i>
            </label>
          </div>
        </li> -->



      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-1 px-2">
      <!-- Brand Logo -->
      <a href="home" class="brand-link py-6 bg-gradient-to-r from-indigo-900 to-purple-900">
        <?php if (!$this->ion_auth->in_group(array('superadmin'))) { ?>
          <div class="flex items-center justify-center py-1">
            <img src="<?php echo $settings->logo_title; ?>" alt="HMS" class="brand-image w-24 h-24 rounded-full shadow-2xl border-4 border-white">
            <span class="brand-text text-3xl font-black text-white tracking-widest uppercase"><?php echo $settings->title; ?></span>
          </div>
        <?php } else { ?>
          <div class="flex flex-col items-center justify-center">
            <img src="<?php echo $settings->logo_title; ?>" alt="HMS" class="brand-image w-24 h-24 rounded-full shadow-2xl border-4 border-white">
            <span class="brand-text text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600"><?php echo $settings->title; ?></span>
          </div>
        <?php } ?>
      </a>

      <!-- <div class="user-panel d-flex border-bottom-0">
        <div class="mt-2 ml-2">
          <?php
          $user_id = $this->ion_auth->get_user_id();
          $user_group = $this->ion_auth->get_users_groups($user_id)->row();
          $group_name = strtolower($user_group->name);

          if ($group_name === 'admin') {
            $table = 'users';
            $id_field = 'id';
          } elseif ($group_name === 'superadmin') {
            $table = 'superadmin';
            $id_field = 'ion_user_id';
          } else {
            $table = $group_name;
            $id_field = 'ion_user_id';
          }

          $user = $this->db->get_where($table, array($id_field => $user_id))->row();
          ?>
        </div>
        <div class="info mt-2">
          <a href="profile" class="d-block text-sm text-gray">
            <i class="fas fa-user-circle mr-2"></i>
            <?php
            $username = $this->ion_auth->user()->row()->username;
            echo !empty($username) ? $username : '';
            ?>
            - <small class="text-gray-300">
              <?php echo ucfirst($group_name); ?>
            </small>
          </a>
        </div>
      </div> -->

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-compact" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->



            <?php $this->load->view('menu'); ?>

            <?php
            //  $this->load->view('menu_demo');
            ?>

          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>



    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark elevation-4">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->



  <style>
    .nav-compact .nav-item .nav-link {
      padding: 0.5rem 0.5rem;
    }

    label {
      display: inline-block;
      margin-bottom: 0px;
      font-weight: 500 !important;
      padding-top: 5px;
    }
  </style>






  <style>
    .loader {
      border: 16px solid #f3f3f3;
      border-top: 16px solid #3498db;
      border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 1s linear infinite;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
      box-shadow: 0 0 20px rgba(52, 152, 219, 0.5);
    }

    @keyframes spin {
      0% {
        transform: translate(-50%, -50%) rotate(0deg);
      }

      100% {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }
  </style>


  <style>
    .nav-sidebar .nav-item .nav-link {
      padding: 0.75rem 1rem;
      font-size: 1.1em;
      font-weight: 500;
    }

    label {
      display: inline-block;
      margin-bottom: 0.5rem;
      font-weight: 600 !important;
    }

    <?php if ($this->session->userdata('mode') != 'dark'): ?>label {
      font-weight: 400 !important;
    }

    <?php endif; ?>.content-wrapper {
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    }

    .card {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.8);
    }

    .btn-primary {
      border: none;
    }



    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .navbar-nav .nav-link {
      position: relative;
      overflow: hidden;
    }

    .navbar-nav .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background-color: var(--accent-color);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .navbar-nav .nav-link:hover::after {
      transform: scaleX(1);
    }

    /* Add more extreme styles as needed */
  </style>

  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
      --accent-color: #e74c3c;
      --text-color: #34495e;
      --background-color: #ecf0f1;
    }

    body {
      background: var(--background-color);
      color: var(--text-color);
      font-family: 'Source Sans Pro', sans-serif;
      transition: all 0.3s ease;
    }



    .main-sidebar {
      background: #2c3e50;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .nav-sidebar .nav-item .nav-link {
      color: #ecf0f1;
      transition: all 0.3s ease;
    }

    .nav-sidebar .nav-item .nav-link:hover {
      background: var(--accent-color);
      transform: translateX(5px);
    }

    .content-wrapper {
      background: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
    }

    .btn {
      border-radius: 5px;
      font-weight: bold;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      padding: 10px 20px;
      font-size: 12px;
    }

    table .btn {
      font-size: 12px;
    }

    .btn:hover {
      transform: scale(1.05);
    }

    .table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
      background: var(--primary-color);
      color: #fff;
    }

    .buttons-html5 {
      background: transparent !important;
      border: 1px solid #a1a1a1 !important;
      color: #ccc !important;
    }

    @media print {
      .table thead th {
        color: #333;
      }
    }



    /* Excel-like styling */
    .table {
      border-collapse: collapse;
      width: 100%;
      background: white;
    }

    .table th {
      background-color: #f3f3f3;
      border: 1px solid #ddd;
      font-weight: bold;
      padding: 8px;
      position: sticky;
      top: 0;
      z-index: 1;
      vertical-align: middle;
    }

    .table td {
      border: 1px solid #ddd;
      padding: 6px 8px;
      vertical-align: middle;
    }

    .table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .table tbody tr:hover {
      background-color: #f5f5f5;
    }

    /* Excel-like grid lines */
    .table-bordered {
      border: 1px solid #ddd;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #ddd;
      vertical-align: middle;
    }

    /* Excel-like header */
    .card-header {
      background-color: #f3f3f3;
      border-bottom: 1px solid #ddd;
    }

    /* Excel-like cell focus */
    .table td:focus {
      outline: 2px solid #1a73e8;
      outline-offset: -2px;
    }



    /* Keep header visible while scrolling */
    thead {
      position: sticky;
      top: 0;
      background: #f3f3f3;
    }
  </style>