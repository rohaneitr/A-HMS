<footer class="site-footer no-print">
    <div class="text-center">
        <?php echo date('Y'); ?> &copy;
        <?php
        $this->db->where('hospital_id', $this->hospital_id);
        echo $this->db->get('settings')->row()->footer_message;
        ?>
        <a href="<?php echo current_url() . '#'; ?>" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
<!--footer end-->
</section>

<?php

$language = $this->language;


if ($language == 'english') {
    $lang = 'en-ca';
    $langdate = 'en-CA';
} elseif ($language == 'spanish') {
    $lang = 'es';
    $langdate = 'es';
} elseif ($language == 'french') {
    $lang = 'fr';
    $langdate = 'fr';
} elseif ($language == 'portuguese') {
    $lang = 'pt';
    $langdate = 'pt';
} elseif ($language == 'arabic') {
    $lang = 'ar';
    $langdate = 'ar';
} elseif ($language == 'italian') {
    $lang = 'it';
    $langdate = 'it';
} elseif ($language == 'zh_cn') {
    $lang = 'zh-cn';
    $langdate = 'zh-CN';
} elseif ($language == 'japanese') {
    $lang = 'ja';
    $langdate = 'ja';
} elseif ($language == 'russian') {
    $lang = 'ru';
    $langdate = 'ru';
} elseif ($language == 'turkish') {
    $lang = 'tr';
    $langdate = 'tr';
} elseif ($language == 'indonesian') {
    $lang = 'id';
    $langdate = 'id';
}


?>

<script type="text/javascript">
    var langdate = "<?php echo $langdate; ?>";
    $(document).ready(function() {
        $('.readonly').keydown(function(e) {
            e.preventDefault();
        });

    })
</script>

<script type="text/javascript">
    var time_format = "<?php echo $this->settings->time_format ?>";
</script>


<script src="common/js/respond.min.js"></script>
<!-- <script type="text/javascript" src="common/assets/ckeditor/build/ckeditor.js"></script> -->
<script type="text/javascript" src="common/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="common/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="common/js/advanced-form-components.js"></script>
<script src="common/js/jquery.cookie.js"></script>
<!--common script for all pages-->
<script src="common/js/common-scripts.js"></script>
<script class="include" type="text/javascript" src="common/js/jquery.dcjqaccordion.2.7.js"></script>
<!--script for this page only-->
<script src="common/js/editable-table.js"></script>
<script src="common/js/bootstrap-select-country.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"></script>


<script src="adminlte/plugins/jquery/jquery.min.js"></script>
<script src="adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>


<script src="common/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="common/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>


<script src="common/assets/bootstrap-datepicker/locales/bootstrap-datepicker.<?php echo $langdate; ?>.min.js"></script>

<script src="common/assets/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.<?php echo $langdate; ?>.min.js"></script>




<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="adminlte/dist/js/adminlte.min.js"></script>
<script src="adminlte/plugins/moment/moment.min.js"></script>
<script src="adminlte/plugins/chart.js/Chart.min.js"></script>
<script src="adminlte/plugins/sparklines/sparkline.js"></script>
<script src="adminlte/dist/js/pages/dashboard.js"></script>
<script src="adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="adminlte/plugins/jszip/jszip.min.js"></script>
<script src="adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="adminlte/plugins/select2/js/select2.full.min.js"></script>
<script src="adminlte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="adminlte/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="adminlte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script type="text/javascript" src="common/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="common/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="common/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="common/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>
<script src="common/js/lightbox.js"></script>
<script src="adminlte/plugins/fullcalendar/main.js"></script>
<script src="adminlte/plugins/fullcalendar/locales/<?php echo $lang; ?>.js"></script>
<script src="adminlte/plugins/dropzone/min/dropzone.min.js"></script>
<!-- SweetAlert2 -->
<script src="adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="adminlte/plugins/toastr/toastr.min.js"></script>

<script src="adminlte/plugins/daterangepicker/daterangepicker.js"></script>

<script src="adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>



<!--
=============================================================================
GLOBAL CSRF INJECTION ENGINE
Fix for REGRESSION-004 (Axios POST calls in Lab, Report Delivery, Test Status)
Fix for REGRESSION-005 (jQuery AJAX POST calls in footer.php + available_js.php)
=============================================================================

ARCHITECTURAL CONTEXT:
  CI3 CSRF protection (csrf_protection = TRUE) validates a token named
  'hms_csrf_token' on every POST request. The token value is stored in
  the browser cookie 'hms_csrf_cookie' and in the server-side session.
  Any POST request whose body does not contain hms_csrf_token = <valid_hash>
  is rejected with HTTP 403 before any controller code runs.

  This application has two HTTP client libraries in use across 14+ views:
    A. jQuery $.ajax() — used in footer.php, available_js.php, patient views,
       inventory views, emergency views, hospital views.
    B. Axios — used in lab.php, my_lab.php, report_delivery.php,
       test_status.php, add_lab_view.php.

  Rather than modifying each of the 14+ call sites individually (which would
  spread CSRF token logic across dozens of view files, creating a maintenance
  nightmare and introducing the risk that any future $.ajax() call added by
  a developer would silently fail), we inject a SINGLE, CENTRALISED handler
  in footer.php — the one file that is loaded at the bottom of every
  authenticated page without exception.

MECHANISM A — jQuery Global AJAX Interceptor ($.ajaxSetup):
  $.ajaxSetup() registers a default options object that is merged into every
  subsequent $.ajax(), $.post(), $.get() call. We use the 'beforeSend'
  callback to append the CSRF token to the request's data payload.
  This is jQuery's documented pattern for cross-cutting AJAX concerns.
  The 'beforeSend' callback receives the jqXHR object and the settings
  object. We modify settings.data (which is the serialized POST body
  string) to append the token. This works for all content types that
  jQuery sends by default (application/x-www-form-urlencoded).

  TOKEN REFRESH: After each CSRF-regenerating response (csrf_regenerate=TRUE
  means every POST triggers a new token), the server sets a new value in
  the 'hms_csrf_cookie' browser cookie. The 'complete' callback reads the
  updated cookie value and stores it in the JS object hmsCSRF.hash, so
  the next $.ajax() call uses the freshly issued token rather than the
  now-invalidated previous one.

MECHANISM B — Axios Global Request Interceptor:
  axios.interceptors.request.use() registers a transformation function that
  runs before every axios request is dispatched. We inspect the request's
  data payload type:
    - FormData: append() the token directly to the FormData object.
    - URLSearchParams: append() works identically.
    - Plain object (JSON): add the token as a top-level property.
    - String (pre-serialized): append as a query-string pair.
  We also set a custom X-HMS-CSRF-Token header for defence-in-depth
  (server-side CSRF libraries can optionally verify headers as well).

  TOKEN REFRESH for Axios: The response interceptor reads the 'hms_csrf_cookie'
  after each response and updates hmsCSRF.hash, keeping the token current
  across multiple sequential axios calls without requiring a page reload.

COOKIE READING UTILITY:
  The hmsCSRF.getCookieValue() function reads a named cookie from
  document.cookie using a regex match. It handles cookies with spaces,
  leading delimiters, and URL-encoded values. This is safe to use even
  when cookie_httponly=TRUE for OTHER cookies, because the hms_csrf_cookie
  is a CSRF token cookie (not the session cookie). CI3's CSRF implementation
  explicitly does NOT set HttpOnly on the CSRF cookie because the JavaScript
  layer must be able to read it. The session cookie (ci_session, set as
  HttpOnly) is separate and remains inaccessible to JavaScript.
=============================================================================
-->
<script>
    /**
     * Global HMS CSRF state object.
     * Populated from the server-rendered PHP values at page load time.
     * Updated by the token-refresh mechanism after each POST response.
     */
    var hmsCSRF = {
        // Token name as configured in config.php: csrf_token_name = 'hms_csrf_token'
        // PHP renders this at page-generation time. It never changes at runtime.
        name: '<?php echo $this->security->get_csrf_token_name(); ?>',

        // Token hash value as issued by CI3 Security library for THIS page load.
        // PHP renders this at page-generation time. Updated by JS after each POST.
        hash: '<?php echo $this->security->get_csrf_hash(); ?>',

        // Cookie name as configured in config.php: csrf_cookie_name = 'hms_csrf_cookie'
        // CI3's Security class has no get_csrf_cookie_name() getter (that is CI4).
        // The config value is statically known, so we use it directly.
        cookieName: '<?php echo $this->config->item('csrf_cookie_name'); ?>',

        /**
         * Reads the current CSRF token value from the browser cookie.
         * Called after each POST response to retrieve the newly issued token
         * (CI3 regenerates the token on every submission when csrf_regenerate=TRUE).
         *
         * @param {string} name - The cookie name to read.
         * @returns {string|null} The cookie value, or null if not found.
         */
        getCookieValue: function(name) {
            var match = document.cookie.match(
                new RegExp('(?:^|;)\\s*' + name.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1') + '=([^;]*)')
            );
            return match ? decodeURIComponent(match[1]) : null;
        },

        /**
         * Refreshes hmsCSRF.hash from the browser cookie after a POST response.
         * CI3 writes the new token to the hms_csrf_cookie after processing a
         * CSRF-protected POST. This function reads that updated cookie value
         * so the next request uses the correct (new) token.
         */
        refresh: function() {
            var fresh = this.getCookieValue(this.cookieName);
            if (fresh && fresh !== '') {
                this.hash = fresh;
            }
        }
    };

    // -------------------------------------------------------------------------
    // MECHANISM A: jQuery Global AJAX CSRF Interceptor
    // -------------------------------------------------------------------------
    // Applies to: ALL $.ajax(), $.post() calls across the entire application.
    // Affected views: footer.php (sidebar, dark mode), available_js.php (7 calls),
    //   patient/case_list.php, patient/medical_history.php, inventory/*.php,
    //   emergency/emergency.php, hospital/add_new.php, settings/language.php,
    //   frontend/index.php, ai_patient_overview/index.php, ai_image_analysis/index.php.
    // -------------------------------------------------------------------------
    $.ajaxSetup({
        /**
         * beforeSend fires for every $.ajax() call immediately before the
         * XHR is dispatched. We receive the settings object which contains
         * the 'data' field — the serialized POST body.
         *
         * We only inject the token for POST/PUT/PATCH/DELETE requests.
         * GET requests do not modify server state and are not CSRF-checked by CI3.
         *
         * settings.data can be:
         *   - A string (jQuery's default serialization of a plain object)
         *   - A FormData object (when processData:false is used)
         *   - null or undefined (empty body)
         */
        beforeSend: function(xhr, settings) {
            var method = (settings.type || 'GET').toUpperCase();

            // Only inject on state-changing requests.
            if (method === 'POST' || method === 'PUT' || method === 'PATCH' || method === 'DELETE') {

                if (settings.data instanceof FormData) {
                    // For multipart FormData (e.g., file uploads), use FormData.append().
                    // Check first to avoid duplicate token fields if someone
                    // manually added the token to a specific call site.
                    if (!settings.data.has(hmsCSRF.name)) {
                        settings.data.append(hmsCSRF.name, hmsCSRF.hash);
                    }
                } else if (typeof settings.data === 'string' && settings.data.length > 0) {
                    // Standard URL-encoded string (jQuery default).
                    // Append as &hms_csrf_token=<hash>.
                    // Guard against duplicate: only append if the token key is absent.
                    if (settings.data.indexOf(hmsCSRF.name + '=') === -1) {
                        settings.data += '&' + encodeURIComponent(hmsCSRF.name) + '=' + encodeURIComponent(hmsCSRF.hash);
                    }
                } else if (settings.data === null || settings.data === undefined || settings.data === '') {
                    // Empty body — build the minimal token-only payload.
                    var tokenPayload = {};
                    tokenPayload[hmsCSRF.name] = hmsCSRF.hash;
                    settings.data = $.param(tokenPayload);
                } else if (typeof settings.data === 'object' && !(settings.data instanceof FormData)) {
                    // Plain JavaScript object. Add the token key directly.
                    // $.ajax() will serialize it to URL-encoded form before sending.
                    if (!settings.data.hasOwnProperty(hmsCSRF.name)) {
                        settings.data[hmsCSRF.name] = hmsCSRF.hash;
                    }
                }
            }
        },

        /**
         * complete fires after every $.ajax() request, regardless of success
         * or failure. We use this to refresh the CSRF token from the updated
         * cookie so the next request uses the newly issued token.
         */
        complete: function(xhr, status) {
            hmsCSRF.refresh();
        }
    });

    // -------------------------------------------------------------------------
    // MECHANISM B: Axios Global Request Interceptor (CSRF Token Injection)
    // -------------------------------------------------------------------------
    // Applies to: ALL axios.post(), axios.put(), axios.patch(), axios.delete()
    // Affected views: lab/lab.php (changeReportStatus), lab/my_lab.php,
    //   lab/report_delivery.php (changeTestStatus, changeDeliveryStatus),
    //   lab/test_status.php (2x changeTestStatus), lab/add_lab_view.php.
    // -------------------------------------------------------------------------
    if (typeof axios !== 'undefined') {

        axios.interceptors.request.use(
            function(config) {
                var method = (config.method || 'get').toLowerCase();

                // Only inject on state-changing HTTP methods.
                if (method === 'post' || method === 'put' || method === 'patch' || method === 'delete') {

                    // Set custom header for defence-in-depth.
                    // Some CSRF validation layers (e.g., future middleware) can
                    // check headers instead of (or in addition to) the body field.
                    config.headers = config.headers || {};
                    config.headers['X-HMS-CSRF-Token'] = hmsCSRF.hash;

                    if (config.data instanceof FormData) {
                        // Lab views use FormData (new FormData() then .append()).
                        // Use FormData.append() to inject the token.
                        // Guard against duplicate injection.
                        if (!config.data.has(hmsCSRF.name)) {
                            config.data.append(hmsCSRF.name, hmsCSRF.hash);
                        }

                    } else if (config.data instanceof URLSearchParams) {
                        // URLSearchParams payload — same append() interface.
                        if (!config.data.has(hmsCSRF.name)) {
                            config.data.append(hmsCSRF.name, hmsCSRF.hash);
                        }

                    } else if (typeof config.data === 'string') {
                        // Pre-serialized URL-encoded string or JSON string.
                        // Attempt to detect JSON vs form-encoded.
                        var trimmed = config.data.trim();
                        if (trimmed.charAt(0) === '{' || trimmed.charAt(0) === '[') {
                            // JSON payload: parse, inject, re-serialize.
                            // Note: CI3's Security::csrf_verify() checks $_POST,
                            // which PHP only populates from form-encoded bodies.
                            // For JSON bodies, the X-HMS-CSRF-Token header above
                            // is the primary token carrier (requires server-side
                            // header check). We still inject into the body for
                            // completeness and forward compatibility.
                            try {
                                var jsonData = JSON.parse(trimmed);
                                if (typeof jsonData === 'object' && !jsonData.hasOwnProperty(hmsCSRF.name)) {
                                    jsonData[hmsCSRF.name] = hmsCSRF.hash;
                                    config.data = JSON.stringify(jsonData);
                                }
                            } catch (e) {
                                // Malformed JSON: leave data unchanged.
                                // The X-HMS-CSRF-Token header remains as fallback.
                            }
                        } else {
                            // URL-encoded string: append token pair.
                            if (config.data.indexOf(hmsCSRF.name + '=') === -1) {
                                config.data += (config.data.length > 0 ? '&' : '') +
                                    encodeURIComponent(hmsCSRF.name) + '=' +
                                    encodeURIComponent(hmsCSRF.hash);
                            }
                        }

                    } else if (config.data === null || config.data === undefined) {
                        // Null/empty body — construct a minimal FormData payload.
                        var fd = new FormData();
                        fd.append(hmsCSRF.name, hmsCSRF.hash);
                        config.data = fd;

                    } else if (typeof config.data === 'object') {
                        // Plain JS object (Axios serializes to JSON by default).
                        // Inject token into the object. Note: if the server expects
                        // form-encoded data, the caller must set the Content-Type
                        // header and transform the data accordingly.
                        if (!config.data.hasOwnProperty(hmsCSRF.name)) {
                            config.data[hmsCSRF.name] = hmsCSRF.hash;
                        }
                    }
                }

                return config;
            },
            function(error) {
                // Request configuration error — pass through unchanged.
                return Promise.reject(error);
            }
        );

        // -------------------------------------------------------------------------
        // Axios Response Interceptor — Token Refresh After Every POST Response
        // -------------------------------------------------------------------------
        // When CI3 processes a CSRF-protected POST (with csrf_regenerate=TRUE),
        // it issues a new token value and writes it to the hms_csrf_cookie cookie
        // in the response's Set-Cookie header. This interceptor reads the updated
        // cookie after every response (success or error) and refreshes hmsCSRF.hash
        // so the next axios call sends the correct, newly-issued token.
        axios.interceptors.response.use(
            function(response) {
                hmsCSRF.refresh();
                return response;
            },
            function(error) {
                hmsCSRF.refresh();
                return Promise.reject(error);
            }
        );
    }
</script>








<script>
    $(document).ready(function() {
        "use strict";

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: "<?php echo $lang; ?>",
            themeSystem: 'bootstrap',
            events: "appointment/getAppointmentByJason",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay"
            },
            firstDay: 1,
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            eventContent: function(arg) {
                var bgColor;
                switch (arg.event.extendedProps.status) {
                    case 'Pending Confirmation':
                        bgColor = "linear-gradient(135deg, #5E35B1, #8E24AA)";
                        bgColor = '#6C5B7B';

                        bgColor = '#FFD54F';


                        break;
                    case 'Confirmed':
                        bgColor = "linear-gradient(160deg, #6C5B7B, #C06C84)";
                        bgColor = "#5E35B1";
                        break;
                    case 'Cancelled':
                        bgColor = "linear-gradient(145deg, #83a4d4, #b6fbff)";
                        bgColor = "#8B0000";
                        break;
                    case 'Requested':
                        bgColor = "#36b9cc";
                        break;
                    case 'Treated':
                        bgColor = "#858796";
                        break;
                    default:
                        bgColor = "#4e73df";
                }
                return {
                    html: `<div style="background: ${bgColor}; padding: 10px; font-size: 10px; border-radius: 5px; overflow: hidden; word-wrap: break-word; text-overflow: ellipsis; max-width: 100%;">
    <span style="color: white;">${arg.timeText}</span><br/>
    <span style="color: white;">${arg.event.title}</span>
</div>`
                };
            },



            eventClick: function(info) {
                $("#medical_history").html("");
                $("#loader").show();
                if (info.event.id) {
                    $.ajax({
                        url: "patient/getMedicalHistoryByJason?id=" + info.event.id + "&from_where=calendar",
                        method: "GET",
                        dataType: "json",
                        success: function(response) {
                            "use strict";
                            $("#medical_history").html(response.view);
                            $("#loader").hide();
                        }
                    });
                }

                $("#cmodal").modal("show");
            },
            slotDuration: "00:05:00",
            businessHours: false,
            slotEventOverlap: false,
            editable: false,
            selectable: false,
            lazyFetching: true,
            initialView: "dayGridMonth", // default view
            timeZone: false
        });

        calendar.render();
    });
</script>

<script src="common/extranal/js/footer.js"></script>





<script>
    Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() {
            myDropzone.enqueueFile(file)
        }
    })
</script>



<script>
    $(".default-date-picker").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: "01-01-1900",
        clearBtn: true,
        language: langdate,
    });
</script>


<?php if ($this->session->flashdata('swal_message')) { ?>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            <?php
            if ($this->session->flashdata('swal_message')) { ?>
                Toast.fire({
                    icon: '<?= $this->session->flashdata('swal_type') ?>',
                    title: '<?= $this->session->flashdata('swal_title') ?> ',
                    text: '<?= $this->session->flashdata('swal_message') ?> ',
                });
            <?php } ?>
        });
    </script>
<?php } ?>

<?php
$this->session->unset_userdata('swal_message');
$this->session->unset_userdata('swal_type');
$this->session->unset_userdata('swal_title');
?>

<script>
    $('.collapse-server').on('click', function() {
        var sidebarCollapsed = $('body').hasClass('sidebar-collapse') ? 1 : 0;
        $.ajax({
            url: 'home/updateSidebarState',
            method: 'POST',
            data: {
                sidebar: sidebarCollapsed
            },
            success: function(response) {
                console.log('Sidebar state updated successfully.');
            },
            error: function(error) {
                console.error('Error updating sidebar state:', error);
            }
        });
    });
</script>

<!-- Auto-hide flash messages -->
<script>
    $(document).ready(function() {
        // Auto-hide flash messages after 5 seconds
        $('.alert').each(function() {
            const alert = $(this);
            setTimeout(function() {
                alert.alert('close');
            }, 5000);
        });
        
        // Also hide flash messages when clicking the close button
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').alert('close');
        });
    });
</script>

<?php
// Clear flash messages after they've been displayed
if ($this->session->flashdata('success') || $this->session->flashdata('error') || $this->session->flashdata('warning') || $this->session->flashdata('debug') || $this->session->flashdata('info')) {
    $this->session->unset_userdata('success');
    $this->session->unset_userdata('error');
    $this->session->unset_userdata('warning');
    $this->session->unset_userdata('debug');
    $this->session->unset_userdata('info');
}
?>

<!-- load avaiable_js.php -->

<?php $this->load->view('available_js'); ?>




<script>
    $(document).ready(function() {
        $('#darkModeToggle').change(function() {
            if ($(this).is(':checked')) {
                $('body').toggleClass('dark-mode');
                $('.main-header').toggleClass('navbar-dark navbar-light');
                $('.main-sidebar').toggleClass('sidebar-dark-primary sidebar-light-primary');
                $('.custom-control-label i').toggleClass('fa-moon fa-sun');
            } else {
                $('body').toggleClass('dark-mode');
                $('.main-header').toggleClass('navbar-dark navbar-light');
                $('.main-sidebar').toggleClass('sidebar-dark-primary sidebar-light-primary');
                $('.custom-control-label i').toggleClass('fa-moon fa-sun');
            }
            drawChartTopServices();
            drawChartTopDiagnoses();
            drawChartBedOccupancy();
            drawChartTopTreatments();
            drawSalesExpenseChart();

            var darkModeValue = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'home/updateDarkMode',
                method: 'POST',
                data: {
                    darkMode: darkModeValue
                }
            });






        });
    });
</script>



</body>

</html>