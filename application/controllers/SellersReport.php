<?php

use Mpdf\Mpdf;

class SellersReport extends CI_Controller
{
    public function index()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }

        $todaySalesPerStaff = $this->db->get('user')->result();

        // echo "<pre>";
        // print_r($todaySalesPerStaff);
        // echo "</pre>";

        $data = [
            "sellers" => $todaySalesPerStaff,
        ];

        $this->load->view('reports/sellersReport', $data);
    }


    public function sellerReportDetail($sellerId)
    {
        $user = $this->db->get_where('user', array('id' => $sellerId))->row();

        $expenses = $this->db->select('*')
            ->from('expense')
            ->join('user', 'expense.userId = user.id', 'left')
            ->where('expense.userId', $sellerId)
            ->where("DATE(expense.createdAt)", date('Y-m-d'))
            ->get()->result_array();

        $this->db->select('o.*, p.name, p.buy_price as buyPrice, oi.id as item_id, oi.quantity, oi.price');
        $this->db->from('orders o');
        $this->db->join('order_item oi', 'o.id = oi.order_id', 'left');
        $this->db->join('product p', 'oi.product_id = p.id');
        $this->db->where('o.user_id', $sellerId);
        $this->db->where("DATE(o.created_at)", date('Y-m-d'));
        $this->db->order_by('o.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $orders = [];
        $current_order_id = null;

        foreach ($result as $row) {
            if ($row['id'] !== $current_order_id) {
                // New order, initialize order array
                $orders[] = [
                    'id' => $row['id'],
                    'customerId' => "not required",
                    'paid' => $row['amountPaid'],
                    'orderItems' => [],
                ];
                $current_order_id = $row['id'];
            }

            // Add order item to the current order
            $orders[count($orders) - 1]['orderItems'][] = [
                'id' => $row['item_id'],
                'product' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'net_price' => $row['quantity'] * $row['price'],
                'profit' => ($row['price'] - $row['buyPrice']) * $row['quantity'],
            ];
        }

        // echo "<pre>";
        // print_r($orders);
        // echo "</pre>";
        // die();

        $data = [
            'orders' => $orders,
            'expenses' => $expenses,
            'user' => $user,
            'date' => date('d-m-Y')
        ];

        $this->load->view('reports/sellerReportDetail', $data);

    }


    public function filter_seller_dashboard()
    {
        $start_date = $this->input->post('start');
        $end_date = $this->input->post('end');
        $userId = $this->input->post('userId');

        if (!$start_date || !$end_date) {
            return $this->sellerReportDetail($userId);
        }

        $user = $this->db->get_where('user', array('id' => $userId))->row();


        $expenses = $this->db->select('*')
            ->from('expense')
            ->join('user', 'expense.userId = user.id', 'left')
            ->where('expense.userId', $userId)
            ->where("DATE(expense.createdAt) BETWEEN '$start_date' AND '$end_date' ")
            ->get()->result_array();

        $this->db->select('o.*, p.name, p.buy_price as buyPrice, oi.id as item_id, oi.quantity, oi.price');
        $this->db->from('orders o');
        $this->db->join('order_item oi', 'o.id = oi.order_id', 'left');
        $this->db->join('product p', 'oi.product_id = p.id');
        $this->db->where('o.user_id', $userId);
        $this->db->where("DATE(o.created_at) BETWEEN '$start_date' AND '$end_date' ");
        $this->db->order_by('o.created_at', 'DESC');
        $query = $this->db->get();

        $result = $query->result_array();

        $orders = [];
        $current_order_id = null;

        foreach ($result as $row) {
            if ($row['id'] !== $current_order_id) {
                // New order, initialize order array
                $orders[] = [
                    'id' => $row['id'],
                    'customerId' => $row['customerId'],
                    'total' => $row['totalPrice'],
                    'paid' => $row['amountPaid'],
                    'orderItems' => [],
                ];
                $current_order_id = $row['id'];
            }

            // Add order item to the current order
            $orders[count($orders) - 1]['orderItems'][] = [
                'id' => $row['item_id'],
                'product' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'net_price' => $row['quantity'] * $row['price'],
                'profit' => ($row['price'] - $row['buyPrice']) * $row['quantity'],
            ];
        }

        // echo "<pre>";
        // print_r($orders);
        // echo "</pre>";
        // die();


        // Convert the string dates to DateTime objects
        $startDate = new DateTime($start_date);
        $endDate = new DateTime($end_date);

        // Format the dates as strings
        $formattedStartDate = $startDate->format('d/m/Y');
        $formattedEndDate = $endDate->format('d/m/Y');

        // Combine the formatted dates with a dash to create the desired output
        $renderedDateRange = $formattedStartDate . ' - ' . $formattedEndDate;

        $data = [
            'orders' => $orders,
            'expenses' => $expenses,
            'user' => $user,
            'date' => $renderedDateRange,
        ];

        $this->load->view('reports/sellerReportDetail', $data);


        // $orders = $this->db->where("DATE(createdAt) BETWEEN '$start_date' AND '$end_date' ")->get('order')->result();
        // var_dump([$orders]);
    }


    public function buying_price()
    {

        $data['products'] = $this->db
        ->select('UPPER(name) as name, buy_price')
        ->get('product')
        ->result();

        // echo "<pre>";
        // print_r($data);
        // exit();

        $this->load->view('reports/buying_price',$data );
    }


    public function print_buyprice()
    {
       
        $data['products'] = $this->db
        ->select('UPPER(name) as name, buy_price')
        ->get('product')
        ->result();

        // echo "<pre>";
        // print_r($data);
        // exit();
    
        $mpdf = new Mpdf(['mode' => 'utf-8','format' => 'A4-L','orientation' => 'L']);
        $html = $this->load->view('reports/price_report',$data,true);
        $mpdf->WriteHTML($html);
        $mpdf->Output();

}

public function top_selling()
{
        //best selling products
        $top_products = $this->db->select("p.name, SUM(oi.quantity) as quantity")
            ->from('order_item oi')
            ->join('product p', 'oi.product_id = p.id')
            ->group_by('oi.product_id')
            ->order_by('quantity', 'DESC')
            ->limit(10)
            ->get()->result();
        $this->load->view('reports/top_selling', ['products' => $top_products]);
        
}

}