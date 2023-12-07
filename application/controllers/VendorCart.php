<?php

class vendorCart extends CI_Controller
{
    public function index()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }
        //update all vendor products with zero balance
        $this->db->update('vendorproduct', ['status' => 'completed'], ['inventory' => 0]);

        $vendorcartitems_count = $this->db->select('count(vci.id) as amount')->from('cart c')->join('vendorcartitem vci', 'vci.cartId = c.id')->where('c.userId', $this->session->userdata("userId"))->get()->row();
        // var_dump($vendorcartitems_count);
        // exit();
        if ($vendorcartitems_count->amount == "0") {
            $this->db->delete('cart', ['userId' => $this->session->userdata("userId")]);
        }


        $products = $this->db->select("vp.inventory, vp.id, bp.stockLimit, p.name as productName, p.buyPrice, p.retailPrice, p.wholePrice")
            ->from("vendorproduct vp")
            ->join('branchproduct bp', 'vp.branchProductId = bp.id')
            ->join("product p", "bp.productId = p.id")
            ->where('vp.userId', $this->session->userdata("userId"))
            ->where('vp.status', 'approved')
            ->where('vp.inventory !=', 0)
            ->get()->result();



        $vendorcartitems = $this->db->select("vci.*, p.name")
            ->from("cart c")
            ->join("vendorcartitem vci", "c.id = vci.cartId")
            ->join("vendorproduct vp", "vci.vendorProductId = vp.id")
            ->join("branchproduct bp", "vp.branchProductId = bp.id")
            ->join("product p", "bp.productId = p.id")
            ->where("c.userId", $this->session->userdata("userId"))
            ->get()->result();
        // echo "<pre>";
        // print_r($vendors);
        // echo"</pre>";
        // exit();


        $data = [
            "products" => $products,
            "vendorcartitems" => $vendorcartitems,
        ];
        $this->load->view("sales/vendorCart", $data);
    }


    public function create_cart($product_id, $price)
    {
        $productStock = $this->db->get_where('vendorproduct', ['id' => $product_id])->row();
        if ($productStock->inventory < 1) {
            $this->session->set_flashdata('less_stock', 'Srock not enough!');

            return redirect('vendorCart');
        }
        $cartExist = $this->db->get_where('cart', ['userId' => $this->session->userdata('userId')])->row();
        if (!$cartExist) {
            $cartId = uniqid('MS-');

            $userId = $this->session->userdata("userId");

            $this->db->trans_start();
            $this->db->insert("cart", ['id' => $cartId, 'userId' => $userId]);
            $this->db->insert('vendorcartitem', ['cartId' => $cartId, 'vendorProductId' => $product_id, 'price' => $price, 'quantity' => 1]);
            $this->db->trans_complete();

            $this->session->set_flashdata('added_tocart', 'Product is added to the cart.');
            return redirect('vendorCart');
        } else {
            $vendorcartitemExist = $this->db->select('vci.*')
                ->from('vendorcartitem vci')
                ->join('cart c', 'vci.cartId = c.id')
                ->where('vci.vendorProductId', $product_id)
                ->where('vci.price', $price)
                ->get()->row();
            if ($vendorcartitemExist) {
                $this->db->set('quantity', 'quantity + 1', false);
                $this->db->where('cartId', $cartExist->id);
                $this->db->where('branchProductId', $product_id);
                $this->db->where('price', $price);
                $this->db->update('vendorcartitem');

                $this->session->set_flashdata('vendorcartitem_updated', 'Item is updated successfully!');

                redirect('vendorCart');
            } else {
                $this->db->insert('vendorcartitem', ['cartId' => $cartExist->id, 'vendorProductId' => $product_id, 'price' => $price, 'quantity' => 1]);
                $this->session->set_flashdata('added_toExistCart', 'Product added to the cart.');

                redirect('vendorCart');
            }
        }

    }


    public function cancel_item($item_id)
    {
        $this->db->where('id', $item_id);
        $this->db->delete('vendorcartitem');

        $this->session->set_flashdata('cartitem_cancelled', 'Item is cancelled');

        redirect('vendorCart');
    }


    public function update_cart()
    {
        $quantities = $this->input->post('quantity');
        $ids = $this->input->post('item_id');

        for ($i = 0; $i < count($ids); $i++) {
            $vendorcartitem = $this->db->get_where('vendorcartitem', ['id' => $ids[$i]])->row();
            $vendorProduct = $this->db->select("p.name, vp.inventory")
                ->from('vendorproduct vp')
                ->join('branchProduct bp', 'vp.branchProductId = bp.id')
                ->join('product p', 'bp.productId = p.id')
                ->where('vp.id', $vendorcartitem->vendorProductId)
                ->get()->row();

            if ($vendorProduct->inventory < $quantities[$i]) {
                $this->session->set_flashdata('exceed_stock', "Available <b>$vendorProduct->name</b> is <b>$vendorProduct->inventory</b>: but you're trying to sell $quantities[$i]");
                break;
            }

            $this->db->update('vendorcartitem', ['quantity' => $quantities[$i]], ['id' => $ids[$i]]);
        }
        $this->session->set_flashdata('cartitems_updated', "Items updated successfully!");

        echo "success";
        // foreach ($ids as $key => $id) {
        //     $this->db->update('purchaseorderitem', ['quantity' => $quantities[$key]], ['id' => $id]);
        // }
        return;

    }


    public function complete_order()
    {
        $orderId = uniqid('INV-');

        $cartId = $this->input->post("cartId");
        $branchId = $this->session->userdata("branchId");
        $userId = $this->session->userdata('userId');
        $customer_id = $this->input->post("customerId");



        $data = [
            "id" => $orderId,
            "userId" => $userId,
            "branchId" => $branchId,
            "customerId" => $customer_id,
            "totalPrice" => $this->input->post("total"),
            "amountPaid" => $this->input->post("paid"),
        ];

        $this->db->trans_start();
        $vendorcartitems = $this->db->select("vci.*, vp.branchProductId")->from('vendorcartitem vci')->join("vendorproduct vp", "vci.vendorProductId = vp.id")->where("vci.cartId", $cartId)->get()->result();
        $this->db->insert('order', $data);
        foreach ($vendorcartitems as $vendorcartitem) {
            $vendorProduct = $this->db->get_where('vendorProduct', ['id' => $vendorcartitem->vendorProductId])->row();

            $this->db->insert("orderitem", ['order_id' => $orderId, 'branchProductId' => $vendorcartitem->branchProductId, 'quantity' => $vendorcartitem->quantity, 'price' => $vendorcartitem->price]);

            $newInventory = $vendorProduct->inventory - $vendorcartitem->quantity;

            $this->db->update('vendorProduct', ['inventory' => $newInventory], ['id' => $vendorProduct->id]);
        }

        $this->db->set('total', 'total + ' . $data['amountPaid'], false);
        $this->db->where('branchId', 1);
        $this->db->update('sales');

        $this->db->delete('cart', ['id' => $cartId]);
        $this->db->trans_complete();

        $this->session->set_flashdata('order_complete', 'Successfully!');

        redirect('vendorCart');
    }

}