<?php


class BranchProducts extends CI_Controller
{
    public function index($branch_id)
    {
        $main_products = $this->db->get("product")->result();
        $branch_name = $this->db->get_where("branch", ['id' => $branch_id])->row()->name;
        $mainbranch_products = $this->db->select("p.*, bp.branchId, bp.quantity as branch_quantity, bp.inventory as branch_inventory, bp.damages as branch_damages, bp.stockLimit as branch_stockLimit, bp.id as branch_product_id, bp.updatedAt as last_updated")
                                ->from("product p")
                                ->join("branchproduct bp", "p.id = bp.productId")
                                ->where("bp.branchId", $branch_id)
                                ->get()
                            ->result();


        $data = [
            "main_products" => $main_products,
            "mainbranch_products" => $mainbranch_products,
            "branch_name" => $branch_name,
            "branch_id" => $branch_id,
            "active_tab" => "active",
        ];
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        $this->load->view("branch_products/index", $data);
    }


    public function create() {
        $product_id = $this->input->post('productId');
        $branch_id = $this->input->post('branchId');

        $data = [
            "productId"=> $product_id,
            "branchId"=> $branch_id,
            "quantity"=> $this->input->post("quantity"),
            "inventory"=> $this->input->post("quantity"),
            "damages"=> $this->input->post("damages"),
            "stockLimit"=> $this->input->post("stockLimit"),
        ];

        $product = $this->db->get_where('branchproduct', ['productId'=> $product_id, 'branchId' => $branch_id])->row_array();
        if ($product) {
            $data["quantity"] += $product['inventory'];
            $data["inventory"] += $product['inventory'];
            $this->db->update("branchproduct", $data, ['productId'=> $product_id, 'branchId' => $branch_id]);
            $this->session->set_flashdata("update_branchproduct_success', 'Product is updated successfully!");
            
            redirect('branchProducts/index/'.$branch_id);
        } else {
            $this->db->insert("branchproduct", $data);
            $this->session->set_flashdata("create_branchproduct_success", "New stock is added successfully!" );
            redirect('branchProducts/index/'.$branch_id);
        }

    }


    public function edit($product_id, $name) {
        if (!$this->session->userdata("userId")) {
            return redirect("login");
        }
        
        $product = $this->db->get_where("branchproduct", ['id' => $product_id])->row();
        $data = [
            "product" => $product,
            "name" => $name,
        ];

        $this->load->view("branch_products/edit", $data);
    }


    public function update()
    {
        $product_id = $this->input->post('product_branch_id');
        $branch_id = $this->input->post('branchId');

        $data = [
            "productId" => $this->input->post("productId"),
            "branchId" => $branch_id,
            "damages" => $this->input->post('damages'),
            "quantity" => $this->input->post('quantity'),
            "inventory" => $this->input->post('quantity'),
            "stockLimit" => $this->input->post('stockLimit'),
        ];
        
        $this->db->update("branchproduct", $data, ["id" => $product_id]);

        $this->session->set_flashdata("update_branchproduct_success', 'Product is updated successfully!");
        
        redirect('branchProducts/index/'.$branch_id);
    }


    public function delete($branch_id, $id)
    {
        $this->db->delete("branchproduct", ["id" => $id]);
        $this->session->set_flashdata("delete_branchproduct_success", "Product is deleted successfully!");
        redirect('branchProducts/index/'.$branch_id);
    }
    
}


