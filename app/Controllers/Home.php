<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\EtatUser;

class Home extends BaseController
{
    protected $userModel;
    protected $etatUserModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->etatUserModel = new EtatUser();
    }

    public function index(): string
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        $user = $this->userModel->find($userId);
        $etat = $this->etatUserModel->where('user_id', $userId)->first();

        $data = [
            'user' => $user,
            'etat' => $etat,
        ];

        return view('pages/home', $data);
    }
}
