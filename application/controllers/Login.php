<?php


class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login');
    }


    public function auth()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim');
        $this->form_validation->set_rules('password', 'Password', 'trim');

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($this->form_validation->run()) {
            $user = $this->db->get_where("user", ["username" => $username, 'status' => 'active'])->row();
            if ($user) {
                //verify password
                $authenticatedUser = password_verify($password, $user->password);
                if ($authenticatedUser) {
                    $userdata = $this->db->get_where("user", ['id' => $user->id])->row();
                    $data = [
                        "userId" => $user->id,
                        "username" => $userdata->username,
                        "firstName" => $userdata->name,
                        "role" => $userdata->role,
                    ];
                    $this->session->set_userdata($data);

                    if ($userdata->role == "ADMIN") {
                        return redirect('dashboard');
                    } else {
                        return redirect('mysales');
                    }
                } else {
                    $this->session->set_flashdata("login_failure", "Incorrect username or password");
                    return redirect('login');
                }
            } else {
                $this->session->set_flashdata("login_failure", "Incorrect username or password");
                return redirect('login');
            }
        }
    }


    public function logout()
    {
        $this->session->unset_userdata(['username', 'userId', 'role']);
        return redirect('login');
    }
}