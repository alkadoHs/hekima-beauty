<?php


class Supplier extends CI_Controller {
    public function index() {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }
        
        $suppliers = $this->db->get("suppliers")->result();

        $data = [
            "users"=> $suppliers
        ];
        $this->load->view('suppliers/index', $data);
    }


    public function register() 
    {
        $data = [
            'name'=> $this->input->post('name'),
            'phone'=> $this->input->post('phone'),
            'address'=> $this->input->post('address'),
        ];

        $this->db->insert('suppliers', $data);
        redirect('supplier');
    }
}