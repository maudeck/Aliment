<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\EtatUser;
use CodeIgniter\HTTP\RedirectResponse;

class Register extends BaseController
{
    protected $userModel;
    protected $etatUserModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->etatUserModel = new EtatUser();
    }

    public function index()
    {
        $data = [
            'title' => 'Inscription - Etape 1',
            'errors' => session()->getFlashdata('errors'),
        ];

        return view('register/step1', $data);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'nom' => 'required|string|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'genre_id' => 'required|is_not_unique[genres.id]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'genre_id' => $this->request->getPost('genre_id'),
            'password' => $this->request->getPost('password'),
        ];

        $userId = $this->userModel->createUser($data);

        if (!$userId) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'Erreur lors de la creation du compte.']);
        }

        session()->set('user_id', $userId);

        return redirect()->to(base_url('/register/step2'));
    }

    public function step2()
    {
        if (!session()->has('user_id')) {
            return redirect()->to(base_url('/register'));
        }

        $data = [
            'title' => 'Inscription - Etape 2',
            'errors' => session()->getFlashdata('errors'),
        ];

        return view('register/step2', $data);
    }

    public function store2(): RedirectResponse
    {
        if (!session()->has('user_id')) {
            return redirect()->to(base_url('/register'));
        }

        $rules = [
            'taille' => 'required|numeric|greater_than[0.5]|less_than[3]',
            'poids' => 'required|numeric|greater_than[20]|less_than[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => session()->get('user_id'),
            'taille' => $this->request->getPost('taille'),
            'poids' => $this->request->getPost('poids'),
        ];

        if (!$this->etatUserModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'Erreur lors de la sauvegarde.']);
        }

        return redirect()->to(base_url('/register'));
    }
}
