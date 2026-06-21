<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emergency_model extends CI_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertEmergency($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('emergency', $data2);
    }

    function getEmergency()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('emergency');
        return $query->result();
    }

    function getEmergencyById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('emergency');
        return $query->row();
    }

    function updateEmergency($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('emergency', $data);
    }

    function deleteEmergency($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('emergency');
    }

    function getEmergencyForDataTable($start, $length)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit($length, $start);
        $query = $this->db->get('emergency');
        return $query->result();
    }
    function getEmergencyCount()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('emergency');
        return $query->num_rows();
    }
}
