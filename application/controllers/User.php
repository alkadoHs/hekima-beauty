<?php

class User extends CI_Controller
{

    public function register_index()
    {
        $userId = $this->session->userdata("userId");

        if (empty($userId)) {
            return redirect("login");
        }

        $users = $this->db->get('user')->result();


        $data = [
            'users' => $users,
        ];
        $this->load->view("users/index", $data);
    }

    public function register()
    {
        $this->form_validation->set_rules("name", "Name", "trim|required");
        $this->form_validation->set_rules("username", "username", "trim|required|is_unique[user.username]");
        $this->form_validation->set_rules("role", "Role", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        $this->form_validation->set_rules("confirmPassword", "Confirm Password", "trim|required|matches[password]");

        if ($this->form_validation->run()) {
            $uuid = uniqid('ID-');
            $password = $this->input->post("password");
            $hash = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
            $data = [
                "id" => $uuid,
                "name" => $this->input->post("name"),
                "username" => $this->input->post("username"),
                "role" => $this->input->post("role"),
                "phone" => $this->input->post("phone"),
                "password" => $hash,
            ];
            $this->db->insert("user", $data);
            $this->session->set_flashdata("register_success", "Staff is registered successfully!");
            redirect("user/register_index");
        } else {
            $this->session->set_flashdata("register_failed", "Staff registration failed!");
            return $this->register_index();
        }

    }


    public function edit($id)
    {
        $user = $this->db->get_where('user', ['id' => $id])->row();
        $this->load->view('users/edit_user', ['user' => $user]);

    }


    public function update()
    {
        $this->form_validation->set_rules('username', 'username', 'is_unique[user.username]');


        $input_data = [
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'username' => $this->input->post('username'),
            'role' => $this->input->post('role'),
            'phone' => $this->input->post('phone'),
            'status' => $this->input->post('status'),
        ];

        if ($this->form_validation->run()) {
            $this->db->replace('user', $input_data);
            $this->session->set_flashdata('update_user_success', 'user updated successfully!');
        } else {
            return $this->edit($input_data['id']);
        }



    }


    public function delete($id)
    {
        $this->db->delete('user', ['id' => $id]);
        $this->session->set_flashdata('delete_user_success', 'user deleted successfully!');
        redirect('user/register_index');
    }


    public function switchBranch()
    {
        $branch = $this->input->post('branch');
        $user = $this->input->post('user');
        $this->db->update('user', ['branchId' => $branch], ['id' => $user]);
        echo "Done!";
    }
}