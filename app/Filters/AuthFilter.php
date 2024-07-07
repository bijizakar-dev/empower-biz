<?php

namespace App\Filters;

use App\Models\System\User;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function __construct()
    {
        $this->m_user = new User();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'));
        }

        $token = $session->get('token');
        $user = $this->m_user->where('token', $token)->first();

        if (!$user) {
            return redirect()->to(base_url('auth/login'));
        }

        if (strtotime($user->expiry_token) < time()) {
            $this->m_user->clear_token($user->id);
            $session->destroy();
            return redirect()->to(base_url('auth/login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}