<?php

class VendorProduct extends CI_Controller
{
    public function index()
    {
        $userId = $this->session->userdata("userId");
        $branchId = $this->session->userdata("branchId");

        if (empty($userId)) {
            return redirect("login");
        }

        $products = $this->db->select('p.name, bp.id')
            ->from('branchproduct bp')
            ->join('product p', 'bp.productId = p.id')
            ->where('bp.branchId', $branchId)
            ->get()->result();

        $vendors = $this->db->get_where('user', ['role' => 'vendor'])->result();

        $vendor_products = $this->db->select("vp.*, p.name, bp.inventory as bp_inventory, u.name as vendor")
            ->from('vendorproduct vp')
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->join('user u', 'vp.userId = u.id')
            ->where('vp.branchId', $branchId)
            ->where('vp.status', 'pending')
            ->get()->result();
        //  echo "<pre>";
        //     print_r($products);
        //     echo "</pre>";
        //     return;
        $data = [
            'products' => $products,
            'orderitems' => $vendor_products,
            'vendors' => $vendors,
        ];
        $this->load->view('products/vendor_products', $data);
    }


    public function add_transfer()
    {
        $userId = $this->session->userdata('userId');
        $branchId = $this->session->userdata('branchId');

        $toUserId = $this->input->post('toUserId');
        $products = $this->input->post('product');


        $this->db->trans_start();

        foreach ($products as $productId) {
            // $product = $this->db->get_where('branchproduct', ['id' => $productId])->row();
            $this->db->insert('vendorproduct', [
                'branchProductId' => $productId,
                'branchId' => $branchId,
                'userId' => $toUserId,
                'quantity' => '0',
            ]);
        }
        $this->db->trans_complete();
        redirect('vendorProduct');
    }


    public function confirm_sales($id)
    {
        $this->db->delete('vendorproduct', ['id' => $id]);
        $this->session->set_flashdata('sales_confirmed', 'Successfully confirmed!');
        redirect('vendorProduct');
    }


    public function update()
    {
        $ids = $this->input->post('id');
        $quantities = $this->input->post('quantity');

        for ($i = 0; $i < count($ids); $i++) {
            $cartItem = $this->db->get_where('vendorproduct', ['id' => $ids[$i]])->row();
            $branchProduct = $this->db->select("p.name, bp.inventory")->from('branchproduct bp')
                ->join('product p', 'bp.productId = p.id')
                ->where('bp.id', $cartItem->branchProductId)
                ->get()->row();

            if ($branchProduct->inventory < $quantities[$i]) {
                $this->session->set_flashdata('exceed_stock2', "Available <b>$branchProduct->name</b> is <b>$branchProduct->inventory</b>: but you're trying to transfer $quantities[$i]");
                break;
            }
            $this->db->update('vendorproduct', ['quantity' => $quantities[$i], 'inventory' => $quantities[$i]], ['id' => $ids[$i]]);
        }

        echo "success";
    }


    public function pending_stock()
    {
        $products = $this->db->select('vp.id, bp.id as bp_id, p.name as product_name,b.name as branch, vp.quantity')->from('vendorproduct vp')
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->join('branch b', 'vp.branchId = b.id')
            ->where('vp.status', 'pending')
            ->where('vp.userId', $this->session->userdata('userId'))
            ->get()->result();

        $this->load->view('stocks/pending_stock', ['products' => $products]);
    }


    public function approve_stock()
    {
        $userId = $this->session->userdata('userId');

        $ids = $this->input->post('id');
        $bp_ids = $this->input->post('bp_id');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->trans_start();
            $vendorProduct = $this->db->get_where('vendorproduct', ['id' => $ids[$i]])->row();
            $this->db->update('vendorproduct', ['status' => 'approved'], ['id' => $ids[$i]]);
            //reduce the stock accordingly
            $this->db->set('inventory', 'inventory - ' . $vendorProduct->quantity, false);
            $this->db->where('id', $bp_ids[$i]);
            $this->db->update('branchproduct');
            $this->db->trans_complete();
        }
        $this->session->set_flashdata('vp_approved', 'Stock approved successfully!');

        echo "success";

        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }

    public function data()
    {
        $branchId = $this->session->userdata('branchId');

        $vendor_products = $this->db->select("vp.*, p.name, bp.inventory as bp_inventory, u.name as vendor")
            ->from('vendorproduct vp')
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->order_by('vp.createdAt', 'DESC')
            ->join('user u', 'vp.userId = u.id')
            ->where('vp.branchId', $branchId)
            ->where('vp.status', 'approved')
            ->or_where('vp.status', 'completed')
            ->get()->result();

        $this->load->view('products/vendor_data', ['orderitems' => $vendor_products]);

    }


    public function stock_return()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }

        $userId = $this->session->userdata("userId");

        if (empty($userId)) {
            return redirect("login");
        }

        $products = $this->db->select('p.name, vp.id, vp.inventory')
            ->from('vendorproduct vp')
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->where('vp.userId', $userId)
            ->where('vp.status', 'approved')
            ->where('vp.inventory !=', 0)
            ->get()->result();


        $order = $this->db->select("sr.*, p.name, b.name as branch, vp.inventory")
            ->from('stock_return sr')
            ->join('vendorproduct vp', 'sr.vendorProductId = vp.id')
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->join('branch b', 'sr.branchId = b.id')
            ->where('sr.status', 'pending')
            ->get()->result();
        //  echo "<pre>";
        //     print_r($products);
        //     echo "</pre>";
        //     return;
        $data = [
            'products' => $products,
            'orderitems' => $order,
        ];
        $this->load->view('stocks/stock_return', $data);

    }


    public function return_product()
    {
        $userId = $this->session->userdata('userId');
        $branchId = $this->session->userdata('branchId');

        $products = $this->input->post('product');
        $toBranchId = $this->input->post('toBranchId');


        $this->db->trans_start();

        foreach ($products as $productId) {
            // $product = $this->db->get_where('branchproduct', ['id' => $productId])->row();
            $this->db->insert('stock_return', [
                'userId' => $userId,
                'vendorProductId' => $productId,
                'branchId' => $toBranchId,
                'quantity' => '0',
                'status' => 'pending',
            ]);
        }
        $this->db->trans_complete();
        $this->session->set_flashdata('return_added', 'Products added to the cart.');

        redirect('vendorProduct/stock_return');
    }


    public function return_to_branch()
    {
        $ids = $this->input->post('id');
        $quantities = $this->input->post('quantity');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->update('stock_return', ['quantity' => $quantities[$i], 'status' => 'returned'], ['id' => $ids[$i]]);
        }
        $this->session->set_flashdata('product_returned', 'Products returned successfully!');

        echo "success";
    }

}