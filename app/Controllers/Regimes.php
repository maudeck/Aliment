<?php

namespace App\Controllers;

use App\Models\UserRegime;
use App\Models\ActiviteSportive;

class Regimes extends BaseController
{
    protected $userRegimeModel;
    protected $activiteModel;

    public function __construct()
    {
        $this->userRegimeModel = new UserRegime();
        $this->activiteModel   = new ActiviteSportive();
    }

    public function index(): string
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        // Tous les régimes achetés avec infos complètes
        $regimes = $this->userRegimeModel->getRegimesAchetes($userId);

        // Pour chaque régime acheté, on charge ses activités
        foreach ($regimes as &$regime) {
            $regime['activites'] = $this->activiteModel->getByRegime($regime['regime_id']);
        }

        $data = [
            'regimes' => $regimes,
        ];

        return view('pages/regimes', $data);
    }
}