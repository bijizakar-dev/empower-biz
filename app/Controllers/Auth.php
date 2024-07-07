<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\System\User;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{

    public function getLogin() {
        return view('auth/login');
    }

    public function postLogin() {
        $session = session();
        $this->m_user = new User();

        $identity = $this->request->getPost('identity');
        $password = $this->request->getPost('password');

        $user = $this->m_user->get_user_by_username_email($identity);

        // header('Content-Type: application/json');
        // die(json_encode($user));

        if(($user->status == TRUE) && (password_verify($password, $user->response->password))) {
            $token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+7 hour'));

            $this->m_user->update_token($user->response->id, $token, $expiry);

            $session->set([
                'token' => $token,
                'isLoggedIn' => true,
                'username' => $user->response->username,
                'email' => $user->response->email,
                'name' => $user->response->name,
                'role' => $user->response->name_role,
                'department' => $user->response->name_department,
                'id_role' => $user->response->id_role,
                'id_department' => $user->response->id_department,
                'photo' => $user->response->photo
            ]);

            return redirect()->to(base_url('/'));
        } else {
            return view('/auth/login', ['error' => 'Invalid username or password']);
        }
    }

    public function getLogout() {
        $session = session();
        $this->m_user = new User();

        $token = $session->get('token');
        if ($token) {
            $user = $this->m_user->where('token', $token)->first();

        // header('Content-Type: application/json');
        // die(json_encode($user));

            if ($user) {
                $this->m_user->clear_token($user->id);
            }
            $session->destroy();
        }

        return redirect()->to(base_url('auth/login'));
    }
}
