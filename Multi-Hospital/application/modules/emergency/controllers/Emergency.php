<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emergency extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('emergency_model');
        if (!$this->ion_auth->in_group(array('admin', 'Doctor', 'Nurse'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['emergencies'] = $this->emergency_model->getEmergency();
        $this->load->view('home/dashboard');
        $this->load->view('emergency', $data);
        $this->load->view('home/footer');
    }

    public function addNewView()
    {
        $this->load->view('home/dashboard');
        $this->load->view('add_new');
        $this->load->view('home/footer');
    }

    public function addNew()
    {
        $id = $this->input->post('id');
        $patient = $this->input->post('patient');
        $doctor = $this->input->post('doctor');
        $emergency_type = $this->input->post('emergency_type');
        $description = $this->input->post('description');
        $status = $this->input->post('status');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('patient', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('emergency_type', 'Emergency Type', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[5]|max_length[500]|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|min_length[1]|max_length[50]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
                redirect("emergency/editEmergency?id=$id");
            } else {
                $this->load->view('home/dashboard');
                $this->load->view('add_new');
                $this->load->view('home/footer');
            }
        } else {
            $data = array(
                'patient' => $patient,
                'doctor' => $doctor,
                'emergency_type' => $emergency_type,
                'description' => $description,
                'status' => $status,
            );

            if (empty($id)) {
                $this->emergency_model->insertEmergency($data);
                $this->session->set_flashdata('feedback', lang('added'));
            } else {
                $this->emergency_model->updateEmergency($id, $data);
                $this->session->set_flashdata('feedback', lang('updated'));
            }

            redirect('emergency');
        }
    }

    function getEmergency()
    {
        $data['emergencies'] = $this->emergency_model->getEmergency();
        $this->load->view('emergency', $data);
    }

    function editEmergency()
    {
        $data = array();
        $id = $this->input->get('id');
        $data['emergency'] = $this->emergency_model->getEmergencyById($id);
        $this->load->view('home/dashboard');
        $this->load->view('edit_emergency', $data);
        $this->load->view('home/footer');
    }

    function editEmergencyByJason()
    {
        $id = $this->input->get('id');
        $data['emergency'] = $this->emergency_model->getEmergencyById($id);
        echo json_encode($data);
    }

    function deleteEmergency()
    {
        $id = $this->input->get('id');
        $this->emergency_model->deleteEmergency($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('emergency');
    }


    function getEmergencyList()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $emergencies = $this->emergency_model->getEmergencyForDataTable($start, $length);
        $data = array();

        foreach ($emergencies as $emergency) {
            $info = array(
                $emergency->id,
                $emergency->patient,
                $emergency->doctor,
                $emergency->emergency_type,
                $emergency->description,
                $emergency->status,
                date('d/m/Y', strtotime($emergency->date))
            );

            $options = '<a class="btn btn-info btn-xs" href="emergency/editEmergency?id=' . $emergency->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a> ';
            $options .= '<a class="btn btn-danger btn-xs" href="emergency/deleteEmergency?id=' . $emergency->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i> ' . lang('delete') . '</a>';

            $info[] = $options;

            $data[] = $info;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->emergency_model->getEmergencyCount(),
            "recordsFiltered" => $this->emergency_model->getEmergencyCount(),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
}
