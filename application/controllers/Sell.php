<?php

class Sell extends CI_Controller
{
    public function index()
    {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }

        $cartitems_count = $this->db->select('count(ci.id) as amount')->from('cart c')->join('cart_item ci', 'ci.cart_id = c.id')->where('c.user_id', $this->session->userdata("userId"))->get()->row();
        // var_dump($cartitems_count);
        // exit();
        if ($cartitems_count->amount == "0") {
            $this->db->delete('cart', ['user_id' => $this->session->userdata("userId")]);
        }

        $products = $this->db->get('product')->result();

        $cartItems = $this->db->select("ci.*, p.name")
            ->from("cart c")
            ->join("cart_item ci", "ci.cart_id = c.id")
            ->join("product p", "p.id = ci.product_id")
            ->where("c.user_id", $this->session->userdata("userId"))
            ->get()->result();
        // echo "<pre>";
        // print_r($vendors);
        // echo"</pre>";
        // exit();


        $data = [
            "products" => $products,
            "cartItems" => $cartItems,
        ];
        $this->load->view("sales/sell", $data);
    }


    public function create_cart($product_id, $price)
    {
        $productStock = $this->db->get_where('product', ['id' => $product_id])->row();
        if ($productStock->inventory < 1) {
            $this->session->set_flashdata('less_stock', 'Stock not enough!');

            return redirect('sell');
        }
        $cartExist = $this->db->get_where('cart', ['user_id' => $this->session->userdata('userId')])->row();
        if (!$cartExist) {
            $cart_id = uniqid('MS-');

            $userId = $this->session->userdata("userId");

            $this->db->trans_start();
            $this->db->insert("cart", ['id' => $cart_id, 'user_id' => $userId]);
            $this->db->insert('cart_item', ['cart_id' => $cart_id, 'product_id' => $product_id, 'price' => $price, 'quantity' => 1]);
            $this->db->trans_complete();

            $this->session->set_flashdata('added_tocart', 'Product is added to the cart.');
            return redirect('sell');
        } else {
            $cartitemExist = $this->db->select('ci.*')
                ->from('cart_item ci')
                ->join('cart c', 'ci.cart_id = c.id')
                ->where('ci.product_id', $product_id)
                ->where('ci.price', $price)
                ->get()->row();
            if ($cartitemExist) {
                $this->db->set('quantity', 'quantity + 1', false);
                $this->db->where('cart_id', $cartExist->id);
                $this->db->where('product_id', $product_id);
                $this->db->where('price', $price);
                $this->db->update('cart_item');

                $this->session->set_flashdata('cartitem_updated', 'Item is updated successfully!');

                redirect('sell');
            } else {
                $this->db->insert('cart_item', ['cart_id' => $cartExist->id, 'product_id' => $product_id, 'price' => $price, 'quantity' => 1]);
                $this->session->set_flashdata('added_toExistCart', 'Product added to the cart.');

                redirect('sell');
            }
        }

    }


    public function cancel_item($item_id)
    {
        $this->db->where('id', $item_id);
        $this->db->delete('cart_item');

        $this->session->set_flashdata('cartitem_cancelled', 'CartItem is cancelled');

        redirect('sell');
    }


    public function update_cart()
    {
        $quantities = $this->input->post('quantity');
        $ids = $this->input->post('item_id');

        for ($i = 0; $i < count($ids); $i++) {
            $cartItem = $this->db->get_where('cart_item', ['id' => $ids[$i]])->row();
            $product = $this->db->get_where("product", ["id" => $cartItem->product_id])->row();

            if ($product->inventory < $quantities[$i]) {
                $this->session->set_flashdata('exceed_stock', "Available <b>$product->name</b> is <b>$product->inventory</b>: but you're trying to sell $quantities[$i]");
                break;
            }

            $this->db->update('cart_item', ['quantity' => $quantities[$i]], ['id' => $ids[$i]]);
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

        $cart_id = $this->input->post("cartId");
        $userId = $this->session->userdata('userId');

        $data = [
            "id" => $orderId,
            "user_id" => $userId,
            "amountPaid" => $this->input->post('paid'),
        ];

        $this->db->trans_start();
        $cartItems = $this->db->get_where('cart_item', ['cart_id' => $cart_id])->result();
        $this->db->insert('orders', $data);
        foreach ($cartItems as $cartItem) {
            $product = $this->db->get_where('product', ['id' => $cartItem->product_id])->row();

            $this->db->insert("order_item", ['order_id' => $orderId, 'product_id' => $cartItem->product_id, 'quantity' => $cartItem->quantity, 'price' => $cartItem->price]);

            $newInventory = $product->inventory - $cartItem->quantity;

            $this->db->update('product', ['inventory' => $newInventory], ['id' => $product->id]);
        }

        $this->db->set('total', 'total + ' . $data['amountPaid'], false);
        $this->db->where('branchId', 1);
        $this->db->update('sales');

        $this->db->delete('cart', ['id' => $cart_id]);
        $this->db->trans_complete();

        redirect('sell');
    }

}