<?php

class PurchaseOrder extends CI_Controller
{
    public function index()
    {
        $userId = $this->session->userdata("userId");

        if (empty($userId)) {
            return redirect("login");
        }

        $products = $this->db->get('product')->result();
        $suppliers = $this->db->get('suppliers')->result();

        //get purchase order items join with product
        $order = $this->db->select('poi.*, po.id as purchaseorderId, p.buyPrice, p.name as product_name, s.name as supplier_name')
            ->from('purchaseorderitem poi')
            ->join('product p', 'p.id = poi.productId')
            ->join('purchaseorder po', 'poi.purchaseorderId = po.id')
            ->join('suppliers s', 's.id = po.supplierId')
            ->where('po.status', 'pending')
            ->get()->result();
        //  echo "<pre>";
        //     print_r($order);
        //     echo "</pre>";
        //     return;
        $data = [
            'products' => $products,
            'suppliers' => $suppliers,
            'orderitems' => $order,
        ];
        $this->load->view('purchase_orders/place_order', $data);
    }


    public function create()
    {
        $orderItems = $this->input->post('order_items');
        $supplierId = $this->input->post('supplierId');
        $purchaseorder_id = uniqid('INV-');

        $this->db->trans_start();
        $this->db->insert('purchaseorder', [
            'id' => $purchaseorder_id,
            'branchId' => $this->input->post('branchId'),
            'supplierId' => $supplierId,
            'paid' => '0',
            'total' => '0',
            'status' => 'pending',
        ]);

        foreach ($orderItems as $orderItem) {
            $this->db->insert('purchaseorderitem', [
                'purchaseorderId' => $purchaseorder_id,
                'productId' => $orderItem,
                'quantity' => '1',
            ]);
        }
        $this->db->trans_complete();
        redirect('purchaseOrder/index');
    }


    public function cancel_order($id)
    {
        $this->db->delete('purchaseorderitem', ['id' => $id]);
        $this->session->set_flashdata('purchaseorderitem_canceled', 'The purchase order item has been canceld!');
        redirect('purchaseOrder/index');
    }


    public function update_order()
    {
        $quantities = $this->input->post('quantity');
        $ids = $this->input->post('order_item_id');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->update('purchaseorderitem', ['quantity' => $quantities[$i]], ['id' => $ids[$i]]);
        }

        echo "success";

        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }


    public function complete_order()
    {
        $id = $this->input->post('purchaseorderId');
        $total = $this->input->post('total');
        $paid = $this->input->post('paid');
        $status = $this->input->post('status');

        $order = $this->db->get_where('purchaseorder', ['id' => $id])->row();

        $this->db->trans_start();
        $orderItems = $this->db->get_where('purchaseorderitem', ['purchaseorderId' => $id])->result();
        foreach ($orderItems as $orderItem) {
            $this->db->insert('newstock', ['productId' => $orderItem->productId, 'purchaseorderId' => $orderItem->purchaseorderId, 'branchId' => $order->branchId, 'quantity' => $orderItem->quantity]);
        }
        $this->db->update('purchaseorder', [
            'total' => $total,
            'paid' => $paid,
            'status' => $status,
        ], ['id' => $id]);
        //update sales
        $this->db->set('total', 'total - ' . $paid, false);
        $this->db->where('branchId', 1);
        $this->db->update('sales');

        $this->db->trans_complete();
        $this->session->set_flashdata('complete_purchaseorder_success', 'The purchase order has been completed successfully!');
        redirect('purchaseOrder/index');
    }



    public function credit_orders()
    {
        $userId = $this->session->userdata("userId");

        if (empty($userId)) {
            return redirect("login");
        }

        $orders = $this->db->select('po.*, s.name as supplier_name')
            ->from('purchaseorder po')
            ->join('suppliers s', 's.id = po.supplierId')
            ->where('po.paid != po.total')
            ->where('po.status', 'complete')
            ->order_by('po.createdAt', 'desc')
            ->get()
            ->result();

        $data = [
            'orders' => $orders,
        ];
        $this->load->view('purchase_orders/credit_orders', $data);
    }


    public function pay_credit_order()
    {
        $branchId = $this->input->post('branchId');
        $id = $this->input->post('purchaseorder_id');

        $data = [
            'paid' => $this->input->post('paid'),
            'total' => $this->input->post('total'),
            'supplierId' => $this->input->post('supplierId'),
            'status' => $this->input->post('status'),
        ];

        //increment the paid amount
        $this->db->trans_start();
        $this->db->set('paid', 'paid + ' . $data['paid'], false);
        $this->db->where('id', $id);
        $this->db->update('purchaseorder');

        $this->db->set('total', 'total - ' . $data['paid'], false);
        $this->db->where('branchId', 1);
        $this->db->update('sales');
        $this->db->trans_complete();

        $this->session->set_flashdata('pay_credit_order_success', 'The credit order has been paid successfully!');
        redirect('purchaseOrder/credit_orders');
    }


    public function order_history()
    {
        $orders = $this->db->select('po.*, s.name as supplier_name')
            ->from('purchaseorder po')
            ->join('suppliers s', 's.id = po.supplierId')
            ->where('po.paid = po.total')
            ->where('po.status', 'complete')
            ->order_by('po.createdAt', 'desc')
            ->get()
            ->result();

        $data = [
            'orders' => $orders,
        ];
        $this->load->view('purchase_orders/order_history', $data);
    }
}
