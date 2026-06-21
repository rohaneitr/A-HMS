<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicine_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertMedicine($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine', $data2);
    }

    function getMedicine()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getMedicineWithoutSearch($order, $dir)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'asc');
        }
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getLatestMedicine()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getMedicineLimitByNumber($number)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine', $number);
        return $query->result();
    }

    function getMedicineByPageNumber($page_number)
    {
        $data_range_1 = 50 * $page_number;
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine', 50, $data_range_1);
        return $query->result();
    }

    function getMedicineByStockAlert()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('quantity <=', 20);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getMedicineByStockAlertByPageNumber($page_number)
    {
        $data_range_1 = 50 * $page_number;
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('quantity <=', 20);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine', 50, $data_range_1);
        return $query->result();
    }

    function getMedicineById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('medicine');
        return $query->row();
    }

    function getMedicineByKeyByStockAlert($page_number, $key)
    {
        $data_range_1 = 50 * $page_number;
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('quantity <=', 20);
        $this->db->or_like('name', $key);
        $this->db->or_like('company', $key);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine', 50, $data_range_1);
        return $query->result();
    }

    function getMedicineByKey($page_number, $key)
    {
        $data_range_1 = 50 * $page_number;
        $this->db->like('name', $key);
        $this->db->or_like('company', $key);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine', 50, $data_range_1);
        return $query->result();
    }

    function getMedicineByKeyForPos($key)
    {
        $this->db->like('name', $key);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function updateMedicine($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine', $data);
    }

    function insertMedicineCategory($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine_category', $data2);
    }

    function getMedicineCategory()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('medicine_category');
        return $query->result();
    }

    function getMedicineCategoryById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('medicine_category');
        return $query->row();
    }

    function totalStockPrice()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('medicine')->result();
        $stock_price = array();
        foreach ($query as $medicine) {
            $stock_price[] = $medicine->price * $medicine->quantity;
        }

        if (!empty($stock_price)) {
            return array_sum($stock_price);
        } else {
            return 0;
        }
    }

    function updateMedicineCategory($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine_category', $data);
    }

    function deleteMedicine($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medicine');
    }

    function deleteMedicineCategory($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medicine_category');
    }

    function getMedicineBySearch($search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $query = $this->db->select('*')
            ->from('medicine')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(id LIKE '%" . $search . "%' OR category LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR e_date LIKE '%" . $search . "%'OR generic LIKE '%" . $search . "%'OR company LIKE '%" . $search . "%'OR effects LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        return $query->result();
    }

    function getMedicineByLimit($limit, $start, $order, $dir)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get('medicine');
        return $query->result();
    }

    function getMedicineByLimitBySearch($limit, $start, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->select('*')
            ->from('medicine')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(id LIKE '%" . $search . "%' OR category LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR e_date LIKE '%" . $search . "%'OR generic LIKE '%" . $search . "%'OR company LIKE '%" . $search . "%'OR effects LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        return $query->result();
    }

    function getMedicineNameByAvailablity($searchTerm)
    {
        if (!empty($searchTerm)) {
            $fetched_records = $this->db->select('*')
            ->from('medicine')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->group_start()
                ->like('id', $searchTerm)
                ->or_like('name', $searchTerm)
            ->group_end()
            ->get();
        
        $query = $fetched_records->result();
        } else {
            $this->db->select('*');
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->limit(10);
            $fetched_records = $this->db->get('medicine');
            $query = $fetched_records->result();
        }

        return $query;
    }

    function getMedicineInfo($searchTerm)
    {
        if (!empty($searchTerm)) {
            $query = $this->db->select('*')
            ->from('medicine')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->group_start()
                ->like('id', $searchTerm)
                ->or_like('name', $searchTerm)
            ->group_end()
            ->get();
        
        $users = $query->result_array();

            // $this->db->select('*');
            // $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            // $this->db->where("id LIKE '%" . $searchTerm . "%' OR name LIKE '%" . $searchTerm . "%'");
            // $fetched_records = $this->db->get('medicine');
            // $users = $fetched_records->result_array();
        } else {
            // $this->db->select('*');
            // $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            // $this->db->limit(10);
            // $fetched_records = $this->db->get('medicine');
            // $users = $fetched_records->result_array();

            $query = $this->db->select('*')
                ->from('medicine')
                ->where('hospital_id', $this->session->userdata('hospital_id'))
                ->limit(10)
                ->get();
            $users = $query->result_array();
        }
        // Initialize Array with fetched data
        $data = array();
        foreach ($users as $user) {
            $data[] = array("id" => $user['id'] . '*' . $user['name'], "text" => $user['name']);
        }
        return $data;
    }

    function getMedicineInfoForPharmacySale($searchTerm)
    {
        if (!empty($searchTerm)) {
            // $this->db->select('*');
            // $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            // $this->db->where('quantity >', '0');
            // $this->db->where("id LIKE '%" . $searchTerm . "%' OR name LIKE '%" . $searchTerm . "%'");
            // $fetched_records = $this->db->get('medicine');
            // $users = $fetched_records->result_array();

            $query = $this->db->select('*')
            ->from('medicine')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where('quantity >', '0')
            ->group_start()
                ->like('id', $searchTerm)
                ->or_like('name', $searchTerm)
            ->group_end()
            ->get();
        
        $users = $query->result_array();
        } else {
            $this->db->select('*');
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where('quantity >', '0');
            $this->db->limit(10);
            $fetched_records = $this->db->get('medicine');
            $users = $fetched_records->result_array();
        }
        // Initialize Array with fetched data
        $data = array();
        foreach ($users as $user) {
            $data[] = array("id" => $user['id'] . '*' . (float) $user['s_price'] . '*' . $user['name'] . '*' . $user['company'] . '*' . $user['quantity'], "text" => $user['name']);
        }
        return $data;
    }
    function getGenericInfoByAll($searchTerm)
    {

        if (!empty($searchTerm)) {
            $this->db->select('*');

            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where("id LIKE '%" . $searchTerm . "%' OR generic LIKE '%" . $searchTerm . "%' OR medicine_id LIKE '%" . $searchTerm . "%'");

            $fetched_records = $this->db->get('medicine');
            $users = $fetched_records->result_array();
        } else {
            $this->db->select('*');
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->limit(10);
            $fetched_records = $this->db->get('medicine');
            $users = $fetched_records->result_array();
        }

        $user_gen = array();
        foreach ($users as $user) {
            $user_gen[] = $user['generic'];
        }
        $result = array_unique($user_gen);

        $data = array();
        $i = 0;
        foreach ($result as $user) {
            //  echo $user[$i];
            $data[] = array("id" => $user, "text" => $user);
        }

        return $data;
    }
    function getMedicineByGeneric($id)
    {
        return  $this->db->where('hospital_id', $this->session->userdata('hospital_id'))->where('generic', $id)
            ->get('medicine')
            ->result();
    }

    // ========== SUPPLIER FUNCTIONS ==========
    
    function insertSupplier($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine_suppliers', $data2);
    }

    function getSuppliers()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('medicine_suppliers');
        return $query->result();
    }

    function getActiveSuppliers()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('status', 'active');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('medicine_suppliers');
        return $query->result();
    }

    function getSupplierById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('medicine_suppliers');
        return $query->row();
    }

    function updateSupplier($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine_suppliers', $data);
    }

    function deleteSupplier($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medicine_suppliers');
    }

    // ========== PURCHASE FUNCTIONS ==========
    
    function insertPurchase($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine_purchases', $data2);
        return $this->db->insert_id();
    }

    function getPurchases()
    {
        $this->db->select('mp.*, ms.name as supplier_name');
        $this->db->from('medicine_purchases mp');
        $this->db->join('medicine_suppliers ms', 'mp.supplier_id = ms.id', 'left');
        $this->db->where('mp.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('mp.purchase_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function getPurchaseById($id)
    {
        $this->db->select('mp.*, ms.name as supplier_name, ms.company_name, ms.contact_person, ms.phone, ms.email');
        $this->db->from('medicine_purchases mp');
        $this->db->join('medicine_suppliers ms', 'mp.supplier_id = ms.id', 'left');
        $this->db->where('mp.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('mp.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function updatePurchase($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine_purchases', $data);
    }

    function deletePurchase($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medicine_purchases');
    }

    // ========== PURCHASE ITEM FUNCTIONS ==========
    
    function insertPurchaseItem($data)
    {
        $this->db->insert('medicine_purchase_items', $data);
        return $this->db->insert_id();
    }

    function getPurchaseItems($purchase_id)
    {
        $this->db->select('mpi.*, m.name as medicine_name, m.generic, m.category');
        $this->db->from('medicine_purchase_items mpi');
        $this->db->join('medicine m', 'mpi.medicine_id = m.id', 'left');
        $this->db->where('mpi.purchase_id', $purchase_id);
        $this->db->order_by('mpi.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getPurchaseItemById($id)
    {
        $this->db->select('mpi.*, m.name as medicine_name, mp.supplier_id');
        $this->db->from('medicine_purchase_items mpi');
        $this->db->join('medicine m', 'mpi.medicine_id = m.id', 'left');
        $this->db->join('medicine_purchases mp', 'mpi.purchase_id = mp.id', 'left');
        $this->db->where('mpi.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function updatePurchaseItem($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine_purchase_items', $data);
    }

    // ========== BATCH FUNCTIONS ==========
    
    function insertBatch($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine_batches', $data2);
        return $this->db->insert_id();
    }

    function getBatches()
    {
        $this->db->select('mb.*, m.name as medicine_name, m.generic, m.category, ms.name as supplier_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicine m', 'mb.medicine_id = m.id', 'left');
        $this->db->join('medicine_suppliers ms', 'mb.supplier_id = ms.id', 'left');
        $this->db->where('mb.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('mb.expiry_date', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getBatchesByMedicine($medicine_id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('current_stock >', 0);
        $this->db->order_by('expiry_date', 'asc');
        $query = $this->db->get('medicine_batches');
        return $query->result();
    }

    function getBatchById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('medicine_batches');
        return $query->row();
    }

    function updateBatch($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('medicine_batches', $data);
    }

    function deleteBatch($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('medicine_batches');
    }

    function getExpiringMedicines($days = 90)
    {
        $this->db->select('mb.*, m.name as medicine_name, m.generic, m.category, ms.name as supplier_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicine m', 'mb.medicine_id = m.id', 'left');
        $this->db->join('medicine_suppliers ms', 'mb.supplier_id = ms.id', 'left');
        $this->db->where('mb.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('mb.current_stock >', 0);
        $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) <=', $days);
        $this->db->where('DATEDIFF(mb.expiry_date, CURDATE()) >=', 0);
        $this->db->order_by('mb.expiry_date', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getExpiredMedicines()
    {
        $this->db->select('mb.*, m.name as medicine_name, m.generic, m.category, ms.name as supplier_name');
        $this->db->from('medicine_batches mb');
        $this->db->join('medicine m', 'mb.medicine_id = m.id', 'left');
        $this->db->join('medicine_suppliers ms', 'mb.supplier_id = ms.id', 'left');
        $this->db->where('mb.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('mb.current_stock >', 0);
        $this->db->where('mb.expiry_date <', date('Y-m-d'));
        $this->db->order_by('mb.expiry_date', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    // ========== STOCK MANAGEMENT FUNCTIONS ==========
    
    function updateMedicineTotalStock($medicine_id)
    {
        $this->db->select('SUM(current_stock) as total_stock');
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('medicine_batches');
        $result = $query->row();
        
        $total_stock = ($result && $result->total_stock) ? $result->total_stock : 0;
        
        $this->db->where('id', $medicine_id);
        $this->db->update('medicine', array('quantity' => $total_stock));
        
        return $total_stock;
    }

    function reduceBatchStock($batch_id, $quantity)
    {
        $batch = $this->getBatchById($batch_id);
        if ($batch && $batch->current_stock >= $quantity) {
            $new_stock = $batch->current_stock - $quantity;
            $this->updateBatch($batch_id, array(
                'current_stock' => $new_stock,
                'quantity_sold' => $batch->quantity_sold + $quantity
            ));
            
            // Update medicine total stock
            $this->updateMedicineTotalStock($batch->medicine_id);
            
            // Log stock movement
            $this->logStockMovement($batch->medicine_id, $batch_id, 'sale', $quantity);
            
            return true;
        }
        return false;
    }

    function logStockMovement($medicine_id, $batch_id, $movement_type, $quantity, $reference_type = null, $reference_id = null, $notes = null)
    {
        $data = array(
            'medicine_id' => $medicine_id,
            'batch_id' => $batch_id,
            'movement_type' => $movement_type,
            'quantity' => $quantity,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'notes' => $notes,
            'performed_by' => $this->session->userdata('user_id'),
            'hospital_id' => $this->session->userdata('hospital_id')
        );
        
        $this->db->insert('medicine_stock_movements', $data);
    }

    function getStockMovements($medicine_id = null, $batch_id = null)
    {
        $this->db->select('msm.*, m.name as medicine_name, mb.batch_number');
        $this->db->from('medicine_stock_movements msm');
        $this->db->join('medicine m', 'msm.medicine_id = m.id', 'left');
        $this->db->join('medicine_batches mb', 'msm.batch_id = mb.id', 'left');
        $this->db->where('msm.hospital_id', $this->session->userdata('hospital_id'));
        
        if ($medicine_id) {
            $this->db->where('msm.medicine_id', $medicine_id);
        }
        
        if ($batch_id) {
            $this->db->where('msm.batch_id', $batch_id);
        }
        
        $this->db->order_by('msm.movement_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    // ========== ENHANCED MEDICINE FUNCTIONS ==========
    
    function getMedicineWithBatches($medicine_id)
    {
        $medicine = $this->getMedicineById($medicine_id);
        if ($medicine) {
            $medicine->batches = $this->getBatchesByMedicine($medicine_id);
        }
        return $medicine;
    }

    function getMedicineByStockAlertWithBatches()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('quantity <=', 10); // Low stock threshold
        $this->db->order_by('quantity', 'asc');
        $query = $this->db->get('medicine');
        $medicines = $query->result();
        
        foreach ($medicines as $medicine) {
            $medicine->batches = $this->getBatchesByMedicine($medicine->id);
        }
        
        return $medicines;
    }

    function getMedicineForSaleWithBatch($medicine_id, $required_quantity)
    {
        // Get available batches ordered by expiry date (FIFO)
        $this->db->where('medicine_id', $medicine_id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('current_stock >', 0);
        $this->db->where('expiry_date >', date('Y-m-d'));
        $this->db->order_by('expiry_date', 'asc');
        $query = $this->db->get('medicine_batches');
        
        return $query->result();
    }

    // ========== PAYMENT FUNCTIONS ==========
    
    function insertPurchasePayment($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('medicine_purchase_payments', $data2);
        return $this->db->insert_id();
    }

    function getPurchasePayments($purchase_id)
    {
        $this->db->where('purchase_id', $purchase_id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('payment_date', 'desc');
        $query = $this->db->get('medicine_purchase_payments');
        return $query->result();
    }

    function getTotalPaymentsByPurchase($purchase_id)
    {
        $this->db->select('SUM(amount) as total_paid');
        $this->db->where('purchase_id', $purchase_id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('status', 'cleared');
        $query = $this->db->get('medicine_purchase_payments');
        $result = $query->row();
        
        return ($result && $result->total_paid) ? $result->total_paid : 0;
    }

    // ========== REPORTING FUNCTIONS ==========
    
    function getPurchaseReport($start_date = null, $end_date = null, $supplier_id = null)
    {
        $this->db->select('mp.*, ms.name as supplier_name');
        $this->db->from('medicine_purchases mp');
        $this->db->join('medicine_suppliers ms', 'mp.supplier_id = ms.id', 'left');
        $this->db->where('mp.hospital_id', $this->session->userdata('hospital_id'));
        
        if ($start_date) {
            $this->db->where('mp.purchase_date >=', $start_date);
        }
        
        if ($end_date) {
            $this->db->where('mp.purchase_date <=', $end_date);
        }
        
        if ($supplier_id) {
            $this->db->where('mp.supplier_id', $supplier_id);
        }
        
        $this->db->order_by('mp.purchase_date', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function getStockReport()
    {
        $this->db->select('m.*, SUM(mb.current_stock) as total_batch_stock, COUNT(mb.id) as total_batches');
        $this->db->from('medicine m');
        $this->db->join('medicine_batches mb', 'm.id = mb.medicine_id AND mb.current_stock > 0', 'left');
        $this->db->where('m.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->group_by('m.id');
        $this->db->order_by('m.name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getSupplierReport($supplier_id = null)
    {
        $this->db->select('ms.*, COUNT(mp.id) as total_purchases, SUM(mp.net_amount) as total_amount, SUM(mp.balance_amount) as total_balance');
        $this->db->from('medicine_suppliers ms');
        $this->db->join('medicine_purchases mp', 'ms.id = mp.supplier_id', 'left');
        $this->db->where('ms.hospital_id', $this->session->userdata('hospital_id'));
        
        if ($supplier_id) {
            $this->db->where('ms.id', $supplier_id);
        }
        
        $this->db->group_by('ms.id');
        $this->db->order_by('ms.name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
}
