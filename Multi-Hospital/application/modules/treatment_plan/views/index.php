<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>AI Treatment Plan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active">AI Treatment Plan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?> 
            
            <?php if ($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php } ?>

            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-robot mr-2"></i>
                                AI-Powered Treatment Plan Generator
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" id="treatmentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="symptoms-tab" data-toggle="tab" href="#symptoms" role="tab" aria-controls="symptoms" aria-selected="true">
                                        <i class="fas fa-stethoscope mr-1"></i> Symptoms
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="analysis-tab" data-toggle="tab" href="#analysis" role="tab" aria-controls="analysis" aria-selected="false">
                                        <i class="fas fa-search mr-1"></i> AI Analysis
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="treatment-tab" data-toggle="tab" href="#treatment" role="tab" aria-controls="treatment" aria-selected="false">
                                        <i class="fas fa-pills mr-1"></i> Treatment Plan
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="prescription-tab" data-toggle="tab" href="#prescription" role="tab" aria-controls="prescription" aria-selected="false">
                                        <i class="fas fa-file-prescription mr-1"></i> Prescription
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="treatmentTabsContent">
                                <!-- Tab 1: Symptoms -->
                                <div class="tab-pane fade show active" id="symptoms" role="tabpanel" aria-labelledby="symptoms-tab">
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="patientSelect">Select Patient <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="patientSelect" name="patient_id" required>
                                                    <option value="">Choose a patient...</option>
                                                    <?php foreach ($patients as $patient): ?>
                                                        <option value="<?php echo $patient->id; ?>">
                                                            <?php echo $patient->name . ' (' . $patient->id . ') - ' . $patient->age . ' years, ' . $patient->sex; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="doctorSelect">Select Doctor <span class="text-danger">*</span></label>
                                                <?php if (count($doctors) == 1): ?>
                                                    <!-- If only one doctor (doctor login), show as read-only -->
                                                    <select class="form-control select2" id="doctorSelect" name="doctor_id" required readonly>
                                                        <option value="<?php echo $doctors[0]->id; ?>" selected>
                                                            <?php echo $doctors[0]->name . ' - ' . $doctors[0]->specialist; ?>
                                                        </option>
                                                    </select>
                                                <?php else: ?>
                                                    <!-- If multiple doctors (admin login), show dropdown -->
                                                    <select class="form-control select2" id="doctorSelect" name="doctor_id" required>
                                                        <option value="">Choose a doctor...</option>
                                                        <?php foreach ($doctors as $doctor): ?>
                                                            <option value="<?php echo $doctor->id; ?>">
                                                                <?php echo $doctor->name . ' - ' . $doctor->specialist; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="symptomsInput">Patient Symptoms <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="symptomsInput" name="symptoms" rows="6" 
                                                          placeholder="Describe the patient's symptoms in detail..." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-primary btn-lg" id="generateAnalysisBtn" disabled>
                                                <i class="fas fa-robot mr-2"></i>
                                                Generate AI Symptom Analysis
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 2: AI Analysis -->
                                <div class="tab-pane fade" id="analysis" role="tabpanel" aria-labelledby="analysis-tab">
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-info text-white" id="analysisHeader" style="display: none;">
                                                    <h6 class="card-title mb-0">
                                                        <i class="fas fa-brain mr-2"></i>
                                                        AI Symptom Analysis
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div id="analysisContent">
                                                        <div class="text-center text-muted">
                                                            <i class="fas fa-robot fa-3x mb-3"></i>
                                                            <p>Click "Generate AI Symptom Analysis" in the Symptoms tab to get started.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="row mt-4">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-info btn-lg" id="generateNewAnalysisBtn" style="display: none;">
                                                <i class="fas fa-sync-alt mr-2"></i>
                                                Generate New Analysis
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-lg" id="goToSymptomsBtn" style="display: none;">
                                                <i class="fas fa-stethoscope mr-2"></i>
                                                Go to Symptoms Tab
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 3: Treatment Plan -->
                                <div class="tab-pane fade" id="treatment" role="tabpanel" aria-labelledby="treatment-tab">
                                    <!-- No Analysis Available State -->
                                    <div id="treatmentNoAnalysis" class="text-center py-5">
                                        <h4 class="text-muted mb-3">AI Analysis Required</h4>
                                        <p class="text-muted mb-4">You need to complete the AI symptom analysis first before generating a treatment plan.</p>
                                        <button type="button" class="btn btn-primary btn-lg" id="goToSymptomsFromTreatmentBtn">
                                            <i class="fas fa-stethoscope mr-2"></i>
                                            Go to Symptoms Tab
                                        </button>
                                    </div>
                                    
                                    <!-- Analysis Available State -->
                                    <div id="treatmentWithAnalysis" style="display: none;">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="doctorInput">Doctor's Clinical Input</label>
                                                    <textarea class="form-control" id="doctorInput" name="doctor_input" rows="4" 
                                                              placeholder="Enter your clinical observations, physical examination findings, etc..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="testResults">Test Results</label>
                                                    <textarea class="form-control" id="testResults" name="test_results" rows="4" 
                                                              placeholder="Enter laboratory results, imaging findings, etc..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center mb-4">
                                                <button type="button" class="btn btn-success btn-lg" id="generateTreatmentBtn" disabled>
                                                    <i class="fas fa-pills mr-2"></i>
                                                    Generate AI Treatment Plan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card" id="treatmentPlanCard">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-clipboard-list mr-2"></i>
                                                            AI Treatment Plan
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="treatmentContent">
                                                            <div class="text-center text-muted">
                                                                <i class="fas fa-pills fa-3x mb-3"></i>
                                                                <p>Complete the previous steps and click "Generate AI Treatment Plan" to get started.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab 4: Prescription -->
                                <div class="tab-pane fade" id="prescription" role="tabpanel" aria-labelledby="prescription-tab">
                                    <!-- No Treatment Plan Available State -->
                                    <div id="prescriptionNoTreatment" class="text-center py-5">
                                        <h4 class="text-muted mb-3">Treatment Plan Required</h4>
                                        <p class="text-muted mb-4">You need to complete the AI treatment plan first before generating a prescription.</p>
                                        <button type="button" class="btn btn-primary btn-lg" id="goToTreatmentFromPrescriptionBtn">
                                            <i class="fas fa-pills mr-2"></i>
                                            Go to Treatment Plan Tab
                                        </button>
                                    </div>
                                    
                                    <!-- Treatment Plan Available State -->
                                    <div id="prescriptionWithTreatment" style="display: none;">
                                        <div class="row mt-4">
                                            <div class="col-12 text-center mb-4">
                                                <button type="button" class="btn btn-warning btn-lg" id="generatePrescriptionBtn" disabled>
                                                    <i class="fas fa-file-prescription mr-2"></i>
                                                    Generate AI Prescription
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card prescription-card" id="prescriptionCard">
                                                    <div class="card-header bg-warning text-dark">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-file-prescription mr-2"></i>
                                                            AI Generated Prescription
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="prescriptionContent">
                                                            <div class="text-center text-muted">
                                                                <i class="fas fa-file-prescription fa-3x mb-3"></i>
                                                                <p>Complete the treatment plan and click "Generate AI Prescription" to create a printable prescription.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-info" id="printPrescriptionBtn" disabled>
                                                    <i class="fas fa-print mr-2"></i>
                                                    Print Prescription
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5>AI is working...</h5>
                <p class="text-muted">Please wait while our AI analyzes the information.</p>
            </div>
        </div>
    </div>
</div>

<script>
let currentTreatmentId = null;

$(document).ready(function() {
    // Test navigation functions
    console.log('Testing navigation functions:');
    console.log('goToSymptomsTab:', typeof window.goToSymptomsTab);
    console.log('goToTreatmentTab:', typeof window.goToTreatmentTab);
    
    // Test function calls
    if (typeof window.goToSymptomsTab === 'function') {
        console.log('goToSymptomsTab function is available');
    } else {
        console.error('goToSymptomsTab function is NOT available');
    }
    
    if (typeof window.goToTreatmentTab === 'function') {
        console.log('goToTreatmentTab function is available');
    } else {
        console.error('goToTreatmentTab function is NOT available');
    }
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Enable/disable buttons based on form completion
    $('#patientSelect, #doctorSelect, #symptomsInput').on('change input', function() {
        const patientSelected = $('#patientSelect').val() !== '';
        const doctorSelected = $('#doctorSelect').val() !== '';
        const symptomsEntered = $('#symptomsInput').val().trim() !== '';
        $('#generateAnalysisBtn').prop('disabled', !(patientSelected && doctorSelected && symptomsEntered));
        
        // Reset analysis state when patient, doctor, or symptoms change
        resetAnalysisState();
    });
    
    // Reset analysis state function
    function resetAnalysisState() {
        $('#analysisHeader').hide();
        $('#analysisContent').html(`
            <div class="text-center text-muted">
                <i class="fas fa-robot fa-3x mb-3"></i>
                <p>Click "Generate AI Symptom Analysis" in the Symptoms tab to get started.</p>
            </div>
        `);
        $('#generateTreatmentBtn').prop('disabled', true);
        $('#treatmentContent').html(`
            <div class="text-center text-muted">
                <i class="fas fa-pills fa-3x mb-3"></i>
                <p>Complete the previous steps and click "Generate AI Treatment Plan" to get started.</p>
            </div>
        `);
        
        // Hide analysis buttons
        $('#generateNewAnalysisBtn').hide();
        $('#goToSymptomsBtn').show();
        
        // Update visibility states
        updateTreatmentPlanVisibility();
        updatePrescriptionVisibility();
    }
    
    
    // Navigation functions (global scope)
    window.goToSymptomsTab = function() {
        console.log('goToSymptomsTab called');
        try {
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');
            $('#symptoms-tab').addClass('active');
            $('#symptoms').addClass('show active');
            console.log('Successfully navigated to symptoms tab');
        } catch (error) {
            console.error('Error navigating to symptoms tab:', error);
        }
    };
    
    window.goToAnalysisTab = function() {
        console.log('Navigating to analysis tab');
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        $('#analysis-tab').addClass('active');
        $('#analysis').addClass('show active');
    };
    
    window.goToTreatmentTab = function() {
        console.log('goToTreatmentTab called');
        try {
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');
            $('#treatment-tab').addClass('active');
            $('#treatment').addClass('show active');
            updateTreatmentPlanVisibility();
            console.log('Successfully navigated to treatment tab');
        } catch (error) {
            console.error('Error navigating to treatment tab:', error);
        }
    };
    
    window.goToPrescriptionTab = function() {
        console.log('Navigating to prescription tab');
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        $('#prescription-tab').addClass('active');
        $('#prescription').addClass('show active');
        updatePrescriptionVisibility();
    };
    
    // Check if analysis is available and update treatment plan visibility
    function updateTreatmentPlanVisibility() {
        const hasAnalysis = $('#analysisContent').find('.analysis-result').length > 0 && 
                           $('#analysisContent').find('.analysis-result').text().trim() !== '';
        
        if (hasAnalysis) {
            $('#treatmentNoAnalysis').hide();
            $('#treatmentWithAnalysis').show();
            $('#treatmentPlanCard').show();
            $('#generateNewAnalysisBtn').show();
            $('#goToSymptomsBtn').hide();
        } else {
            $('#treatmentNoAnalysis').show();
            $('#treatmentWithAnalysis').hide();
            $('#treatmentPlanCard').hide();
            $('#generateNewAnalysisBtn').hide();
            $('#goToSymptomsBtn').show();
        }
    }
    
    // Check if treatment plan is available and update prescription visibility
    function updatePrescriptionVisibility() {
        const hasTreatment = $('#treatmentContent').find('.treatment-result').length > 0 && 
                            $('#treatmentContent').find('.treatment-result').text().trim() !== '';
        
        if (hasTreatment) {
            $('#prescriptionNoTreatment').hide();
            $('#prescriptionWithTreatment').show();
            $('#prescriptionCard').show();
        } else {
            $('#prescriptionNoTreatment').show();
            $('#prescriptionWithTreatment').hide();
            $('#prescriptionCard').hide();
        }
    }
    
    // Check if doctor is pre-selected (for doctor login)
    $(document).ready(function() {
        if ($('#doctorSelect').val() !== '') {
            // Doctor is pre-selected, trigger validation
            $('#patientSelect, #symptomsInput').trigger('change');
        }
        
        // Initialize visibility states
        updateTreatmentPlanVisibility();
        updatePrescriptionVisibility();
        
        // Initialize analysis tab buttons
        $('#goToSymptomsBtn').show();
        $('#generateNewAnalysisBtn').hide();
    });
    
    // Tab shown event handlers
    $('#treatment-tab').on('shown.bs.tab', function(e) {
        updateTreatmentPlanVisibility();
    });
    
    $('#prescription-tab').on('shown.bs.tab', function(e) {
        updatePrescriptionVisibility();
    });
    
    // AI Analysis tab button handlers
    $('#generateNewAnalysisBtn').on('click', function() {
        // Reset analysis state and go to symptoms tab
        resetAnalysisState();
        goToSymptomsTab();
    });
    
    $('#goToSymptomsBtn').on('click', function() {
        goToSymptomsTab();
    });
    
    // Treatment tab navigation button
    $('#goToSymptomsFromTreatmentBtn').on('click', function() {
        console.log('Treatment tab button clicked');
        goToSymptomsTab();
    });
    
    // Prescription tab navigation button
    $('#goToTreatmentFromPrescriptionBtn').on('click', function() {
        console.log('Prescription tab button clicked');
        goToTreatmentTab();
    });

    // Generate Symptom Analysis
    $('#generateAnalysisBtn').on('click', function() {
        const patientId = $('#patientSelect').val();
        const doctorId = $('#doctorSelect').val();
        const symptoms = $('#symptomsInput').val();
        
        if (!patientId || !doctorId || !symptoms) {
            alert('Please select a patient, doctor, and enter symptoms.');
            return;
        }

        // Hide the analysis header when starting new analysis
        $('#analysisHeader').hide();
        
        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '<?php echo base_url(); ?>treatment_plan/generateSymptomAnalysis',
            type: 'POST',
            data: {
                patient_id: patientId,
                doctor_id: doctorId,
                symptoms: symptoms
            },
            dataType: 'json',
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                if (response.success) {
                    currentTreatmentId = response.treatment_id;
                    
                    
                    // Display analysis
                    const analysisHtml = `
                        <div class="analysis-result">
                            <div class="analysis-content">${response.analysis.replace(/\n/g, '<br>')}</div>
                        </div>
                    `;
                    
                    $('#analysisContent').html(analysisHtml);
                    
                    // Show the analysis header
                    $('#analysisHeader').show();
                    
                    // Enable treatment plan generation
                    $('#generateTreatmentBtn').prop('disabled', false);
                    
                    // Update treatment plan visibility
                    updateTreatmentPlanVisibility();
                    
                    // Switch to analysis tab
                    goToAnalysisTab();
                } else {
                    showNotification(response.message || 'Failed to generate analysis', 'error');
                }
            },
            error: function() {
                $('#loadingModal').modal('hide');
                showNotification('An error occurred while generating analysis', 'error');
            }
        });
    });

    // Generate Treatment Plan
    $('#generateTreatmentBtn').on('click', function() {
        if (!currentTreatmentId) {
            alert('Please complete the symptom analysis first.');
            return;
        }

        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '<?php echo base_url(); ?>treatment_plan/generateTreatmentRecommendation',
            type: 'POST',
            data: {
                treatment_id: currentTreatmentId,
                doctor_input: $('#doctorInput').val(),
                test_results: $('#testResults').val()
            },
            dataType: 'json',
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                if (response.success) {
                    // Display treatment plan
                    const treatmentHtml = `
                        <div class="treatment-result">
                            <div class="treatment-content">${response.treatment_plan.replace(/\n/g, '<br>')}</div>
                        </div>
                    `;
                    
                    $('#treatmentContent').html(treatmentHtml);
                    
                    // Enable prescription generation
                    $('#generatePrescriptionBtn').prop('disabled', false);
                    
                    // Update prescription visibility
                    updatePrescriptionVisibility();
                    
                    // Switch to treatment tab
                    goToTreatmentTab();
                } else {
                    showNotification(response.message || 'Failed to generate treatment plan', 'error');
                }
            },
            error: function() {
                $('#loadingModal').modal('hide');
                showNotification('An error occurred while generating treatment plan', 'error');
            }
        });
    });

    // Generate Prescription
    $('#generatePrescriptionBtn').on('click', function() {
        if (!currentTreatmentId) {
            alert('Please complete the treatment plan first.');
            return;
        }

        $('#loadingModal').modal('show');
        
        $.ajax({
            url: '<?php echo base_url(); ?>treatment_plan/generatePrescription',
            type: 'POST',
            data: {
                treatment_id: currentTreatmentId
            },
            dataType: 'json',
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                if (response.success) {
                    // Get patient and doctor information
                    const selectedPatient = $('#patientSelect option:selected').text();
                    const patientId = $('#patientSelect').val();
                    const selectedDoctor = $('#doctorSelect option:selected').text();
                    
                    // Create professional prescription HTML
                    const prescriptionHTML = `
                        <div class="prescription-print">
                            <div class="prescription-header">
                                <div class="prescription-title">
                                    <h3>PRESCRIPTION</h3>
                                    <p class="prescription-date">Date: ${new Date().toLocaleDateString()}</p>
                                    <p class="prescription-doctor">Dr. ${selectedDoctor}</p>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="hospital-info">
                                    <h2 class="hospital-name"><?php echo $settings->title ?? "Hospital"; ?></h2>
                                    <div class="hospital-details">
                                        <p><strong>Address:</strong> <?php echo $settings->address ?? ""; ?></p>
                                        <p><strong>Phone:</strong> <?php echo $settings->phone ?? ""; ?> | <strong>Email:</strong> <?php echo $settings->email ?? ""; ?></p>
                                    </div>
                                </div>
                                <div class="patient-info">
                                    <h4>Patient Information:</h4>
                                    <p><strong>Name:</strong> ${selectedPatient}</p>
                                    <p><strong>Patient ID:</strong> ${patientId}</p>
                                </div>
                            </div>
                            
                            <div class="prescription-content">
                                <h4>Prescription:</h4>
                                <div class="prescription-text">${response.prescription.replace(/\n/g, '<br>')}</div>
                            </div>
                            
                            <div class="prescription-footer">
                                <div class="doctor-signature">
                                    <p>Doctor's Signature: _________________</p>
                                    <p>Date: _________________</p>
                                </div>
                                <div class="prescription-note">
                                    <p><em>Please follow the prescription as directed. Contact the hospital if you have any questions.</em></p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('#prescriptionContent').html(prescriptionHTML);
                    
                    // Enable print button
                    $('#printPrescriptionBtn').prop('disabled', false);
                    
                    // Switch to prescription tab
                    goToPrescriptionTab();
                } else {
                    showNotification(response.message || 'Failed to generate prescription', 'error');
                }
            },
            error: function() {
                $('#loadingModal').modal('hide');
                showNotification('An error occurred while generating prescription', 'error');
            }
        });
    });

    // Print Prescription
    $('#printPrescriptionBtn').on('click', function() {
        const prescriptionContent = $('#prescriptionContent').html();
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Medical Prescription</title>
                    <style>
                        body { 
                            font-family: 'Times New Roman', serif; 
                            margin: 0; 
                            padding: 20px; 
                            background: white;
                            line-height: 1.6;
                        }
                        .prescription-print {
                            max-width: 800px;
                            margin: 0 auto;
                            border: 2px solid #333;
                            padding: 30px;
                            background: white;
                        }
                        .prescription-header {
                            border-bottom: 3px solid #333;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }
                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            margin-bottom: 25px;
                            gap: 30px;
                        }
                        .hospital-info {
                            flex: 1;
                            text-align: left;
                        }
                        .hospital-name {
                            font-size: 24px;
                            font-weight: bold;
                            color: #2c3e50;
                            margin: 0 0 10px 0;
                        }
                        .hospital-details p {
                            margin: 5px 0;
                            font-size: 14px;
                            color: #555;
                        }
                        .prescription-title {
                            text-align: center;
                        }
                        .prescription-title h3 {
                            font-size: 24px;
                            font-weight: bold;
                            color: #2c3e50;
                            margin: 0;
                            letter-spacing: 2px;
                        }
                        .prescription-date {
                            font-size: 16px;
                            color: #666;
                            margin: 10px 0 0 0;
                        }
                        .prescription-doctor {
                            font-size: 18px;
                            color: #2c3e50;
                            margin: 8px 0 0 0;
                            font-weight: 600;
                        }
                        .patient-info {
                            background: #f8f9fa;
                            padding: 15px;
                            border-left: 4px solid #007bff;
                            margin-bottom: 25px;
                        }
                        .patient-info h4 {
                            color: #2c3e50;
                            margin: 0 0 10px 0;
                            font-size: 18px;
                        }
                        .patient-info p {
                            margin: 5px 0;
                            font-size: 16px;
                        }
                        .prescription-content {
                            margin-bottom: 30px;
                        }
                        .prescription-content h4 {
                            color: #2c3e50;
                            font-size: 18px;
                            margin: 0 0 15px 0;
                            border-bottom: 2px solid #007bff;
                            padding-bottom: 5px;
                        }
                        .prescription-text {
                            font-size: 16px;
                            line-height: 1.8;
                            white-space: pre-line;
                        }
                        .prescription-footer {
                            border-top: 2px solid #333;
                            padding-top: 20px;
                            margin-top: 30px;
                        }
                        .doctor-signature {
                            float: right;
                            text-align: right;
                            margin-bottom: 20px;
                        }
                        .doctor-signature p {
                            margin: 10px 0;
                            font-size: 16px;
                        }
                        .prescription-note {
                            clear: both;
                            text-align: center;
                            font-style: italic;
                            color: #666;
                            font-size: 14px;
                        }
                        @media print {
                            body { margin: 0; padding: 15px; }
                            .prescription-print { border: none; padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${prescriptionContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });

    // Tab change handlers
    $('#treatmentTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        
        // Enable/disable buttons based on current state
        if (target === '#treatment') {
            $('#generateTreatmentBtn').prop('disabled', !currentTreatmentId);
        } else if (target === '#prescription') {
            $('#generatePrescriptionBtn').prop('disabled', !currentTreatmentId);
        }
    });
});

function showNotification(message, type) {
    // You can implement a toast notification here
    alert(message);
}
</script>

<style>
.analysis-result, .treatment-result, .prescription-result {
    white-space: pre-line;
    line-height: 1.6;
    font-size: 14px;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #007bff;
    color: #007bff;
    background: none;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #007bff;
    color: #007bff;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
}

#loadingModal .modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Prescription Styling */
.prescription-card .prescription-print {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}

.prescription-print .prescription-header {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.prescription-print .prescription-title {
    text-align: center;
}

.prescription-print .prescription-title h3 {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
    margin: 0;
    letter-spacing: 1px;
}

.prescription-print .prescription-date {
    font-size: 14px;
    color: #6c757d;
    margin: 8px 0 0 0;
}

.prescription-print .prescription-doctor {
    font-size: 16px;
    color: #2c3e50;
    margin: 8px 0 0 0;
    font-weight: 600;
}

.prescription-print .info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    gap: 20px;
}

.prescription-print .hospital-info {
    flex: 1;
    text-align: left;
}

.prescription-print .hospital-name {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
    margin: 0 0 8px 0;
}

.prescription-print .hospital-details p {
    margin: 3px 0;
    font-size: 12px;
    color: #6c757d;
}

.prescription-print .patient-info {
    flex: 1;
    background: #f8f9fa;
    padding: 12px;
    border-left: 3px solid #007bff;
    border-radius: 0 4px 4px 0;
}

.prescription-print .patient-info h4 {
    color: #2c3e50;
    margin: 0 0 8px 0;
    font-size: 16px;
}

.prescription-print .patient-info p {
    margin: 3px 0;
    font-size: 14px;
}

.prescription-print .prescription-content {
    margin-bottom: 25px;
}

.prescription-print .prescription-content h4 {
    color: #2c3e50;
    font-size: 16px;
    margin: 0 0 12px 0;
    border-bottom: 1px solid #007bff;
    padding-bottom: 3px;
}

.prescription-print .prescription-text {
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-line;
}

.prescription-print .prescription-footer {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
    margin-top: 25px;
}

.prescription-print .doctor-signature {
    float: right;
    text-align: right;
    margin-bottom: 15px;
}

.prescription-print .doctor-signature p {
    margin: 8px 0;
    font-size: 14px;
}

.prescription-print .prescription-note {
    clear: both;
    text-align: center;
    font-style: italic;
    color: #6c757d;
    font-size: 12px;
}


.analysis-content, .treatment-content {
    line-height: 1.6;
    font-size: 14px;
}
</style>
