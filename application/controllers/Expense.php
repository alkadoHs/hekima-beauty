<?php

class Expense extends CI_Controller
{
    public function index()
    {
        $userId = $this->session->userdata("userId");

        if (empty($userId)) {
            return redirect("login");
        }
        $expenses = $this->db->query("SELECT * FROM expense WHERE userId = '$userId' AND MONTH(createdAt) = MONTH(CURDATE())")->result();
        // $expenses = $this->db->get_where("expense", array("userId"=> $this->session->userdata("userId"), "MONTH(createdAt)" => "MONTH(CURDATE())"))->result();

        $data = [
            "expenses" => $expenses,
        ];
        $this->load->view("expenses/add", $data);
    }


    public function create()
    {
        $userId = $this->session->userdata("userId");
        $branchId = $this->session->userdata("branchId");
        $datas = [
            ['userId' => $userId, 'description' => $this->input->post('description1'), 'amount' => $this->input->post('amount1')],
            ['userId' => $userId, 'description' => $this->input->post('description2'), 'amount' => $this->input->post('amount2')],
            ['userId' => $userId, 'description' => $this->input->post('description3'), 'amount' => $this->input->post('amount3')],
            ['userId' => $userId, 'description' => $this->input->post('description4'), 'amount' => $this->input->post('amount4')],
        ];


        $this->db->trans_start();
        foreach ($datas as $data) {
            if (!$data['description'] || !$data['amount']) {
                continue;
            }
            $this->db->set('total', 'total - ' . $data['amount'], false);
            $this->db->where('branchId', 1);
            $this->db->update('sales');

            $this->db->insert("expense", $data);
        }
        $this->db->trans_complete();
        redirect("expense");
    }
}