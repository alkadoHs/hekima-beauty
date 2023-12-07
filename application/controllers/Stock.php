<?php

class Stock extends CI_Controller
{
    public function index()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }

        $order = $this->db->select('ns.*, p.name as product_name')
            ->from('newstock ns')
            ->join('product p', 'p.id = ns.productId')
            ->where('ns.branchId', $this->session->userdata('branchId'))
            ->where('ns.status', 'pending')
            ->get()->result();


        $data = [
            'orderitems' => $order,
        ];

        $this->load->view('stocks/new_stock', $data);
    }


    public function approve_stock()
    {
        $userId = $this->session->userdata('userId');

        $quantities = $this->input->post('quantity2');
        $ids = $this->input->post('order_item_id');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->trans_start();
            $this->db->update('newstock', ['quantity2' => $quantities[$i], 'status' => 'approved', 'userId' => $userId], ['id' => $ids[$i]]);
            $this->db->trans_complete();
        }

        echo "success";

        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }

    public function approved_orders()
    {
        $order = $this->db->select('ns.*, p.name as product_name, b.name as branch, u.username as seller')
            ->from('newstock ns')
            ->join('product p', 'p.id = ns.productId')
            ->join('branch b', 'ns.branchId = b.id')
            ->join('user u', 'ns.userId = u.id')
            ->where('ns.status', 'approved')
            ->order_by('b.name')
            ->get()->result();


        $data = [
            'orderitems' => $order,
        ];

        $this->load->view('stocks/approved_stocks', $data);
    }


    public function confirm_stock()
    {

        $ids = $this->input->post('order_item_id');

        // echo "<pre>";
        // print_r($quantities);
        // echo "</pre>";
        // exit();

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->trans_start();
            $newStock = $this->db->get_where('newstock', ['id' => $ids[$i]])->row();
            $product1 = $this->db->get_where('product', ['id' => $newStock->productId])->row();
            $product1branch = $this->db->get_where('branch', ['id' => $newStock->branchId])->row();
            $product = $this->db->select('bp.id, bp.quantity, bp.quantity, bp.inventory, p.name')
                ->from('branchproduct bp')
                ->join('product p', 'bp.productId = p.id')
                ->where('bp.branchId', $newStock->branchId)
                ->where('bp.productId', $newStock->productId)
                ->get()->row();

            if ($product) {
                $newInventory = $product->inventory + $newStock->quantity2;
                //update the stock
                $this->db->update('branchproduct', ['quantity' => $newInventory, 'inventory' => $newInventory], ['id' => $product->id]);
            } else {
                $this->session->set_flashdata('product_not_availabele', "Bidhaa ya <b>$product1->name </b> ni mpya kwenye <b>$product1branch->name</b>, hakikisha kwanza umeisajili kwenye hii branch halafu urudi tena kukonfemu.");
                break;
            }
            $this->db->update('newstock', ['status' => 'confirmed'], ['id' => $ids[$i]]);
            $this->db->trans_complete();
        }

        echo "success";

        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }



    public function transfer()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }

        $userId = $this->session->userdata("userId");
        $branchId = $this->session->userdata("branchId");

        if (empty($userId)) {
            return redirect("login");
        }

        $products = $this->db->select('p.name, p.brand, bp.id')
            ->from('branchproduct bp')
            ->join('product p', 'bp.productId = p.id')
            ->where('bp.branchId', $branchId)
            ->get()->result();


        $order = $this->db->select("tp.*, p.name, p.brand")
            ->from('transferedproduct tp')
            ->join('branchproduct bp', 'tp.branchProductId = bp.id')
            ->join('product p', 'bp.productId = p.id')
            ->where('tp.status', 'pending')
            ->get()->result();
        //  echo "<pre>";
        //     print_r($products);
        //     echo "</pre>";
        //     return;
        $data = [
            'products' => $products,
            'orderitems' => $order,
        ];
        $this->load->view("stocks/transfer", $data);
    }

    public function add_transfer()
    {
        $userId = $this->session->userdata('userId');
        $branchId = $this->session->userdata('branchId');

        $toBranchId = $this->input->post('toBranchId');
        $products = $this->input->post('product');


        $this->db->trans_start();

        foreach ($products as $productId) {
            // $product = $this->db->get_where('branchproduct', ['id' => $productId])->row();
            $this->db->insert('transferedproduct', [
                'branchProductId' => $productId,
                'quantity' => '0',
                'fromBranchId' => $branchId,
                'toBranchId' => $toBranchId,
                'status' => 'pending',
            ]);
        }
        $this->db->trans_complete();
        redirect('stock/transfer');
    }


    public function cancel_transfer($id)
    {
        $this->db->delete('transferedproduct', ['id' => $id]);
        $this->session->set_flashdata('transfer_canceled', 'The transfer order item has been canceld!');
        redirect('stock/transfer');
    }


    public function complete_transfer()
    {
        $ids = $this->input->post('id');
        $quantities = $this->input->post('quantity');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->update('transferedproduct', ['quantity' => $quantities[$i], 'status' => 'transfered'], ['id' => $ids[$i]]);
        }

        echo "success";
    }


    public function receive_transfer()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }


        $branchId = $this->session->userdata('branchId');

        $items = $this->db->select("tp.*, b.name as from, p.name, p.brand, tp.quantity")
            ->from("transferedproduct tp")
            ->join("branchproduct bp", "tp.branchProductId = bp.id")
            ->join("product p", "bp.productId = p.id")
            ->join("branch b", "tp.fromBranchId = b.id")
            ->where("tp.status", "transfered")
            ->where("tp.toBranchId", $branchId)
            ->get()->result();

        $data = [
            "orderitems" => $items,
        ];
        $this->load->view('stocks/receive', $data);
    }


    public function confirm_transfer()
    {
        $userId = $this->session->userdata('userId');
        $ids = $this->input->post('id');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->trans_start();
            $newStock = $this->db->get_where('transferedproduct', ['id' => $ids[$i]])->row();
            $product = $this->db->get_where('branchproduct', ['id' => $newStock->branchProductId])->row();
            if ($product) {
                $newInventory = $product->inventory - $newStock->quantity;
                //update the fom branch stock
                $this->db->update('branchproduct', ['inventory' => $newInventory], ['id' => $product->id]);

                //increment to branch stock
                $product2 = $this->db->get_where('branchproduct', ['productId' => $product->productId, 'branchId' => 2])->row();
                $newInventory2 = $product2->inventory + $newStock->quantity;
                $this->db->update('branchproduct', ['quantity' => $newInventory2, 'inventory' => $newInventory2], ['branchId' => 2, 'productId' => $product->productId]);
            } else {
                $this->session->set_flashdata('product_not_availabele', "kuna bidhaa ambayo haijasajiliwa kwenye UYOLE SHOP.");
                break;
            }
            $this->db->update('transferedproduct', ['status' => 'confirmed', 'userId' => $userId], ['id' => $ids[$i]]);

            $this->db->trans_complete();
        }
    }


    public function stock_return_index()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }


        $branchId = $this->session->userdata('branchId');

        $items = $this->db->select("sr.*, u.username as vendor, p.name")
            ->from("stock_return sr")
            ->join('vendorproduct vp', 'sr.vendorProductId = vp.id')
            ->join("branchproduct bp", "vp.branchProductId = bp.id")
            ->join("product p", "bp.productId = p.id")
            ->join("user u", "sr.userId = u.id")
            ->join("branch b", "sr.branchId = b.id")
            ->where("sr.status", "returned")
            ->where("sr.branchId", $branchId)
            ->get()->result();

        $data = [
            "orderitems" => $items,
        ];
        $this->load->view('stocks/vendor_return', $data);
    }

    public function approve_vendor_return()
    {
        $userId = $this->session->userdata('userId');

        $ids = $this->input->post('id');
        $vp_ids = $this->input->post('vp_id');
        $quantities = $this->input->post('quantity');

        for ($i = 0; $i < count($ids); $i++) {
            $this->db->trans_start();
            $this->db->update('stock_return', ['status' => 'approved'], ['id' => $ids[$i]]);

            $this->db->set('inventory', 'inventory - ' . $quantities[$i], false);
            $this->db->where('id', $vp_ids[$i]);
            $this->db->update('vendorproduct');
            // $branchProduct = $this->db->select('sr. ,bp.id as pb_id, bp.productId')
            //                          ->from('stock_return sr')
            //                          ->join('vendorproduct vp', 'sr.vendorProductId = vp.id')
            //                          ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            //                          ->where('sr.id', $ids[$i])
            //                          ->get()->row();

            // $branchProductExist = $this->db->get_where('branchproduct', ['branchId', $this->session->userdata('branchId'), 'productId' => $branchProduct->productId ])->row();

            // if($branchProductExist) {
            // }
            $this->db->trans_complete();
        }

        $this->session->set_flashdata('sr_confirmed', 'Confirmed successfully!');


        echo "success";

        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }

    public function cancel_stock_return($id) 
    {
        $this->db->delete('stock_return', ['id' => $id]);
        $this->session->set_flashdata('stock_return_deleted', 'Item cancelled successifuly!');
        
        redirect('vendorProduct/stock_return');
        
    }

}