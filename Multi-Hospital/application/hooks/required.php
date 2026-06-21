<?php

function required()
{
    $CI = &get_instance();

    // =========================================================================
    // REGRESSION-003 FIX: Per-Subsystem sess_match_ip Runtime Override
    // =========================================================================
    //
    // PROBLEM:
    //   sess_match_ip = TRUE was applied globally. This binds a session to the
    //   IP address that created it. For browser-based SaaS users on a hospital
    //   LAN this is correct and desirable. However, mobile API consumers
    //   (Flutter/Android app via Api.php) use cellular networks where the
    //   device IP address changes on cell tower handover. Each IP change causes
    //   CI3's Session_database driver to fail the row lookup:
    //       SELECT * FROM ci_sessions WHERE id = ? AND ip_address = ?
    //   returning 0 rows, which CI3 interprets as "session not found" →
    //   a new anonymous session is created → the user is silently logged out.
    //
    // MECHANISM — WHY THIS LOCATION IS THE ONLY SAFE PLACE:
    //   CI3's Session library is loaded LAZILY via autoload or the first call
    //   to $CI->load->library('session'). In this application the session
    //   library is loaded on Line 7 below ($CI->load->library('session')).
    //   The pre_controller hook fires BEFORE the controller's __construct(),
    //   which means we must override sess_match_ip BEFORE line 7 executes.
    //   If we tried to set this in config.php or later in the controller, the
    //   Session library would have already read the original config value and
    //   cached it internally. The hook position here (pre_controller, before
    //   any library loading) is the only architectural insertion point that
    //   guarantees the override reaches the Session library on first read.
    //
    // MECHANISM — HOW THE ROUTER IS AVAILABLE HERE:
    //   The Router (load_class('Router')) is instantiated by CI3's bootstrap
    //   in CodeIgniter.php before any hooks fire. The pre_controller hook is
    //   called after routing is complete but before the controller is loaded.
    //   $RTR->class is therefore fully populated and reliable at this point.
    //
    // SECURITY POSTURE:
    //   - Browser dashboard requests (all non-api classes): sess_match_ip=TRUE
    //     Session cookie stolen over an unencrypted hospital LAN becomes
    //     unusable from a different IP. Defence-in-depth against cookie theft.
    //
    //   - Mobile API requests ($RTR->class === 'api'): sess_match_ip=FALSE
    //     Cellular IP handovers are tolerated. The API's primary security
    //     control is the CSRF exemption (session is stateless per-request auth
    //     via ion_auth->login() credentials, not long-lived session cookies).
    //     The IP-binding protection is redundant for the API subsystem since
    //     the API does not use persistent browser-style session cookies.
    // =========================================================================
    $RTR = &load_class('Router');

    if ($RTR->class === 'api') {
        // Override sess_match_ip to FALSE for the mobile API subsystem only.
        // $CI->config->set_item() writes directly into the live config array
        // that the Session library will read when it initialises below.
        // This is the CI3-native method for runtime config mutation and does
        // not require editing config.php or the CI3 core files.
        $CI->config->set_item('sess_match_ip', FALSE);
    }
    // All other controllers retain the global value: sess_match_ip = TRUE
    // (set in application/config/config.php as part of SESS-001 remediation).

    $CI->load->library('Ion_auth');
    $CI->load->library('session');
    $CI->load->helper('cookie');
    $CI->load->library('form_validation');
    $CI->load->library('upload');
    $CI->load->library('parser');
    $CI->load->helper('security');
    $CI->load->helper('toastr_helper');


    if ($RTR->class != "frontend" && $RTR->class != "payu" && $RTR->class != "status" && $RTR->class != "cronjobs" && $RTR->class != "request" && $RTR->class != "auth" && $RTR->class != "site" && $RTR->class != "api") {
        if (!$CI->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    $CI->load->model('settings/settings_model');
    $CI->load->model('logs/logs_model');
    $CI->load->model('ion_auth_model');
    $CI->load->model('hospital/hospital_model');



    // if ($CI->ion_auth->logged_in()) {
    //     if (!$CI->ion_auth->in_group(array('superadmin'))) {
    //         if ($CI->router->fetch_class() != 'settings' && $CI->router->fetch_class() != 'auth') {
    //             try {
    //                 $verify = $CI->settings_model->verify();
    //                 if ($verify['verified'] == 1) {
    //                 } else {
    //                     redirect('settings/verifyYourPruchase776cbvcfytfytfvvn');
    //                 }
    //             } catch (Exception $e) {
    //                 redirect('settings/verifyYourPruchase776cbvcfytfytfvvn');
    //             }
    //         }
    //     }
    // }



    if ($CI->router->fetch_class() == 'site' && $CI->router->fetch_method() == 'index') {
        $modules = $CI->db->get_where('hospital', array('id' => $CI->hospital_id))->row()->module;
        $CI->modules = explode(',', $modules);
        $hospital_username = $CI->uri->segment(2);
        $CI->hospital_id = $CI->db->get_where('hospital', array('username' => $hospital_username))->row()->id;
        if (!empty($CI->hospital_id)) {
            $newdata = array(
                'site_id' => $CI->hospital_id,
                'site_name' => $hospital_username,
                'hospital_id' => $CI->hospital_id
            );
            $CI->session->set_userdata($newdata);
        } else {
            redirect('home/permission');
        }
        $CI->db->where('hospital_id', $CI->session->userdata('site_id'));
        $language = $CI->db->get('site_settings')->row()->language;


        if (!empty($CI->session->userdata('language_site'))) {
            $language = $CI->session->userdata('language_site');
        }

        if (empty($language)) {
            $language = 'english';
        }


        $CI->language = $language;
        $CI->lang->load('system_syntax', $language);
    } elseif ($CI->router->fetch_class() == 'site' && ($CI->router->fetch_method() == 'getAvailableSlotByDoctorByDateByJason' || $CI->router->fetch_method() == 'getDoctorVisit' || $CI->router->fetch_method() == 'getDoctorVisitCharges' || $CI->router->fetch_method() == 'addNew')) {
        $CI->hospital_id = $CI->session->userdata('site_id');
    } else {
        // Initialize hospital_id to null. It will be assigned below based on
        // the user's group. Without this, reading $CI->hospital_id on line 211
        // when the class is 'auth' (which skips the assignment block) generates
        // an 'Undefined property' warning that sends output before headers.
        $CI->hospital_id = null;

        if ($CI->ion_auth->logged_in()) {
            $user = $CI->ion_auth->get_user_id();
            $users_details = $CI->db->get_where('users', array('id' => $user))->row();

            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                if (empty($users_details->hospital_ion_id)) {
                    $hospital = $CI->db->get_where('hospital', array('ion_user_id' => $users_details->id))->row();
                    $hospital_payment = $CI->db->get_where('hospital_payment', array('hospital_user_id' => $hospital->id))->row();
                    if (!empty($hospital_payment)) {
                        if ($hospital_payment->next_due_date_stamp < time()) {
                            $data_de = array();
                            $data_de = array('active' => 0);
                            $CI->db->where('id', $user);
                            $CI->db->update('users', $data_de);
                            $status = array('status' => 'expired');
                            $CI->db->where('id', $hospital_payment->id)->update('hospital_payment', $status);
                        }
                    }
                } else {
                    $hospital = $CI->db->get_where('hospital', array('ion_user_id' => $users_details->hospital_ion_id))->row();
                    $hospital_payment = $CI->db->get_where('hospital_payment', array('hospital_user_id' => $hospital->id))->row();
                    if (!empty($hospital_payment)) {
                        if ($hospital_payment->next_due_date_stamp < time()) {
                            $data_de = array();
                            $data_de = array('active' => 0);
                            $CI->db->where('id', $user);
                            $CI->db->update('users', $data_de);

                            $status = array('status' => 'expired');
                            $CI->db->where('id', $hospital_payment->id)->update('hospital_payment', $status);
                        }
                    }
                }
            }
        }

        if ($RTR->class != "cronjobs" && $RTR->class != "frontend" && $RTR->class != "payu" && $RTR->class != "status" && $RTR->class != "request" && $RTR->class != "auth" && $RTR->class != "api" ) {
            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                if ($CI->ion_auth->in_group(array('admin'))) {
                    $current_user_id = $CI->ion_auth->user()->row()->id;
                    $CI->hospital_id = $CI->db->get_where('hospital', array('ion_user_id' => $current_user_id))->row()->id;
                    if (!empty($CI->hospital_id)) {
                        $newdata = array(
                            'hospital_id' => $CI->hospital_id,
                        );
                        $CI->session->set_userdata($newdata);
                    }
                } else {
                    $current_user_id = $CI->ion_auth->user()->row()->id;
                    $group_id = $CI->db->get_where('users_groups', array('user_id' => $current_user_id))->row()->group_id;
                    $group_name = $CI->db->get_where('groups', array('id' => $group_id))->row()->name;
                    $group_name = strtolower($group_name);
                    $CI->hospital_id = $CI->db->get_where($group_name, array('ion_user_id' => $current_user_id))->row()->hospital_id;
                    if (!empty($CI->hospital_id)) {
                        $newdata = array(
                            'hospital_id' => $CI->hospital_id,
                        );
                        $CI->session->set_userdata($newdata);
                    }
                }
            } else {
                $CI->hospital_id = 'superadmin';
                if (!empty($CI->hospital_id)) {
                    $newdata = array(
                        'hospital_id' => $CI->hospital_id,
                    );
                    $CI->session->set_userdata($newdata);
                }
            }
        }

    if (!$CI->ion_auth->in_group(array('superadmin'))) {
        $CI->db->where('hospital_id', $CI->hospital_id);
        $CI->timezone = $CI->db->get('settings')->row()->timezone;
    } else {
        $CI->db->where('hospital_id', 'superadmin');
        $CI->timezone = $CI->db->get('settings')->row()->timezone;
    }
    $timezone = $CI->timezone;
    if (!empty($timezone)) {
        date_default_timezone_set($timezone);
    } else {
        date_default_timezone_set('UTC');
    }


        // Language
        if ($RTR->class != "cronjobs" && $RTR->class != "frontend" && $RTR->class != "payu" && $RTR->class != "status"  && $RTR->class != "request" && $RTR->class != "api") {
            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                $CI->db->where('hospital_id', $CI->hospital_id);
                $CI->language = $CI->db->get('settings')->row()->language;
                $CI->hospital_language = $CI->language;
                $CI->lang->load('system_syntax', $CI->language);
                if ($CI->ion_auth->in_group(array('Patient'))) {
                    $CI->language = $CI->db->get_where('patient', array('ion_user_id' => $current_user_id))->row()->language;
                    if (empty($CI->language)) {
                        $CI->language = $CI->hospital_language;
                    }
                    if (!empty($CI->language)) {
                        $CI->lang->load('system_syntax', $CI->language);
                    }
                }
                if ($CI->ion_auth->in_group(array('Doctor'))) {
                    $CI->language = $CI->db->get_where('doctor', array('ion_user_id' => $current_user_id))->row()->language;
                    if (empty($CI->language)) {
                        $CI->language = $CI->hospital_language;
                    }
                    if (!empty($CI->language)) {
                        $CI->lang->load('system_syntax', $CI->language);
                    }
                }
            } else {
                $CI->db->where('hospital_id', 'superadmin');
                $CI->language = $CI->db->get('settings')->row()->language;
                $CI->lang->load('system_syntax', $CI->language);
            }
        }
        if ($RTR->class == "frontend" || $RTR->class == "request") {
            $CI->db->where('hospital_id', 'superadmin');
            $CI->language = $CI->db->get('settings')->row()->language;
            $CI->lang->load('system_syntax', $CI->language);
        }


        if ($CI->router->fetch_class() == 'frontend' && $CI->router->fetch_method() == 'index') {
            $language = $CI->session->userdata('language');
            if (empty($language)) {
                $language = 'english';
            }
            $CI->language = $language;
            $CI->lang->load('system_syntax', $language);
        }




        if ($RTR->class == "auth" && $CI->router->fetch_method() == 'login') {
            $CI->db->where('hospital_id', 'superadmin');
            $CI->language = $CI->db->get('settings')->row()->language;
            $CI->lang->load('system_syntax', $CI->language);
        }
        // Language



        // Currency
        if ($RTR->class != "cronjobs" && $RTR->class != "payu" && $RTR->class != "status" &&   $RTR->class != "auth" && $RTR->class != "frontend" && $RTR->class != "site") {
            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                $CI->db->where('hospital_id', $CI->hospital_id);
                $CI->currency = $CI->db->get('settings')->row()->currency;
            } else {
                $CI->db->where('hospital_id', 'superadmin');
                $CI->currency = $CI->db->get('settings')->row()->currency;
            }
        }
        // Currency

        if ($RTR->class != "cronjobs" && $RTR->class != "payu" && $RTR->class != "status"  && $CI->ion_auth->in_group(array('admin', 'superadmin', 'Doctor', 'Receptionist')) && $RTR->class != "auth" && $RTR->class != "site") {
            if (!$CI->ion_auth->in_group(array('superadmin')) && $RTR->class != "frontend") {
                $CI->db->where('hospital_id', $CI->hospital_id);
                $CI->settings = $CI->db->get('settings')->row();
            } else {
                $CI->db->where('hospital_id', 'superadmin');
                $CI->settings = $CI->db->get('settings')->row();
            }
            if ($CI->settings->emailtype == 'Domain Email') {

                $CI->load->library('email');
            }
            if ($CI->settings->emailtype == 'Smtp') {


                $email_Settings = $CI->db->get_where('email_settings', array('type' => $CI->settings->emailtype, 'hospital_id' => $CI->hospital_id))->row();

                $config['protocol'] = 'smtp';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['smtp_host'] = $email_Settings->smtp_host;
                $config['smtp_port'] = $email_Settings->smtp_port;
                $config['smtp_user'] = $email_Settings->user;
                $config['smtp_pass'] = base64_decode($email_Settings->password);
                $config['smtp_crypto'] = 'tls';
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['send_multipart'] = TRUE;
                $config['newline'] = "\r\n";

                $CI->load->library('email');
                $CI->email->initialize($config);
                $CI->load->library('email');
            }
        }
        if ($RTR->class != "cronjobs" && $RTR->class != "payu" && $RTR->class != "status"  && $RTR->class != "frontend" && $RTR->class != "request" && $RTR->class != "auth" && $RTR->class != "api") {
            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                if ($CI->ion_auth->in_group(array('admin'))) {
                    $current_user_id = $CI->ion_auth->user()->row()->id;
                    $modules = $CI->db->get_where('hospital', array('ion_user_id' => $current_user_id))->row()->module;
                    $CI->modules = explode(',', $modules);
                } else {
                    $current_user_id = $CI->ion_auth->user()->row()->id;

                    $group_id = $CI->db->get_where('users_groups', array('user_id' => $current_user_id))->row()->group_id;
                    $group_name = $CI->db->get_where('groups', array('id' => $group_id))->row()->name;

                    $group_name = strtolower($group_name);

                    $hospital_id = $CI->db->get_where($group_name, array('ion_user_id' => $current_user_id))->row()->hospital_id;

                    $modules = $CI->db->get_where('hospital', array('id' => $hospital_id))->row()->module;

                    $CI->modules = explode(',', $modules);
                }
            }
        }
        if ($RTR->class != "cronjobs" && $RTR->class != "payu" && $RTR->class != "status" &&  $RTR->class != "" && $RTR->class != "" && $RTR->class != "auth") {
            if ($CI->ion_auth->in_group(array('superadmin'))) {
                $current_user_id = $CI->ion_auth->user()->row()->id;
                $super_modules = $CI->db->get_where('superadmin', array('ion_user_id' => $current_user_id))->row()->module;
                $CI->super_modules = explode(',', $super_modules);
            }
        }

        $common = array('payu', 'status', 'macro', 'auth', 'pservice', 'frontend', 'settings', 'import', 'home', 'profile', 'request', 'api', 'cronjobs', 'logs', 'doctorvisit', 'site', 'testpkz', 'facilitie', 'faq', 'diagnosis', 'treatment', 'symptom', 'advice', 'inventory', 'treatment_plan', 'ai_image_analysis', 'ai_patient_overview'); 

        if (!in_array($RTR->class, $common)) {
            if (!$CI->ion_auth->in_group(array('superadmin'))) {
                if ($RTR->class != "schedule" && $RTR->class != "meeting" && $RTR->class != "featured" && $RTR->class != "gallery" && $RTR->class != "review" && $RTR->class != "gridsection" && $RTR->class != "service" && $RTR->class != "slide" && $RTR->class != "facilitie" && $RTR->class != "faq") {
                    if ($RTR->class != "pgateway") {
                        if (!in_array($RTR->class, $CI->modules)) {
                            redirect('home');
                        }
                    } elseif (!in_array('finance', $CI->modules)) {
                        redirect('home');
                    }
                } elseif (!in_array('appointment', $CI->modules)) {
                    redirect('home');
                }
            } else {
                if (!in_array($RTR->class, $CI->super_modules)) {
                    redirect('home');
                }
            }
        }
    }



    // ------------------------------------------------------------------
    // LANGUAGE-FIX-4: Cookie-based language override.
    //
    // This override must:
    //   (a) Only fire when a valid language_site cookie is present.
    //   (b) Validate the cookie value against actually-existing language
    //       files (APPPATH . 'language/<name>/system_syntax_lang.php').
    //       An invalid/tampered cookie must NOT reach lang->load() — that
    //       would trigger a PHP warning/error and potentially crash the page.
    //   (c) Silently discard any cookie that fails validation so the DB-based
    //       language (set earlier in this hook) remains in effect.
    // ------------------------------------------------------------------
    if (!empty($CI->input->cookie('language_site'))) {
        $cookieLang = $CI->input->cookie('language_site');
        // Sanitise: only allow lowercase letters, digits, and underscores/hyphens
        // (all valid CI language folder names follow this pattern).
        if (preg_match('/^[a-z][a-z0-9_-]{0,49}$/i', $cookieLang)) {
            $langFile = APPPATH . 'language/' . $cookieLang . '/system_syntax_lang.php';
            if (file_exists($langFile)) {
                $CI->language = $cookieLang;
                $CI->lang->load('system_syntax', $cookieLang);
            }
            // else: file missing — silently keep the DB-based language already loaded above.
        }
        // else: cookie value looks tampered/invalid — ignore it entirely.
    }


    // if ($RTR->class == "site") {
    //     if ($CI->router->fetch_method() == 'index') {

    //     }
    //     // $settings = $CI->db->get_where('settings', array('hospital_id' => $CI->session->userdata('hospital_id')))->row();
    //     // $CI->lang->load('system_syntax', $settings->language);
    // }
}
