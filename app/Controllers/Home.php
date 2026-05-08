<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\EtatUser;
use App\Models\UserObjectif;
use App\Models\Objectif;

class Home extends BaseController
{
    protected $userModel;
    protected $etatUserModel;
    protected $userObjectifModel;
    protected $objectifModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->etatUserModel = new EtatUser();
        $this->userObjectifModel = new UserObjectif();
        $this->objectifModel = new Objectif();
    }

    public function index(): string
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        $user = $this->userModel->find($userId);
        $etat = $this->etatUserModel->where('user_id', $userId)->first();
        $userObjectif = $this->userObjectifModel->where('user_id', $userId)->first();
        $objectifNom = null;

        if ($userObjectif) {
            $objectif = $this->objectifModel->find($userObjectif['objectif_id']);
            if ($objectif) {
                $objectifNom = $objectif['nom'];
            }
        }

        $data = [
            'user' => $user,
            'etat' => $etat,
            'objectifNom' => $objectifNom,
        ];

        return view('pages/home', $data);
    }
}
