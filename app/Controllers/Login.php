<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\HTTP\RedirectResponse;

class Login extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $data = [
            'title' => 'Connexion',
            'errors' => session()->getFlashdata('errors'),
        ];

        return view('auth/login', $data);
    }

    public function authenticate(): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'Email ou mot de passe incorrect.']);
        }

        session()->set('user_id', $user['id']);
        session()->set('user_email', $user['email']);

        return redirect()->to(base_url('/home'));
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['user_id', 'user_email']);

        return redirect()->to(base_url('/login'));
    }
}
