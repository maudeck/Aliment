<?php

namespace App\Controllers;

use App\Models\ActiviteSportive;
use App\Models\User;
use App\Models\UserObjectif;
use App\Models\RegimeObjectif;
use App\Models\UserRegime;
use CodeIgniter\HTTP\RedirectResponse;

class Activites extends BaseController
{
    public function index(): string|RedirectResponse
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/login'));
        }

        $db = \Config\Database::connect();

        $user = (new User())->find($userId);

        
        $userObjectif = (new UserObjectif())->where('user_id', $userId)->first();

        
        $regimes = [];
        if ($userObjectif) {
            $roModel = new RegimeObjectif();
            $ros = $roModel->where('objectif_id', $userObjectif['objectif_id'])->findAll();
            foreach ($ros as $ro) {
                $regime = $db->table('regimes')->where('id', $ro['regime_id'])->get()->getRowArray();
                if ($regime) {
                    $regime['achete'] = (new UserRegime())->aAchete($userId, $regime['id']);
                    $regimes[] = $regime;
                }
            }
        }

        
        $regimesAchetes = (new UserRegime())->getRegimesAchetes($userId);

       
        $activiteModel = new ActiviteSportive();
        $activitesPar = []; 

        foreach ($regimes as $r) {
            $acts = $activiteModel->getByRegime($r['id']);
            if (!empty($acts)) {
                $activitesPar[$r['id']] = [
                    'regime'    => $r,
                    'activites' => $acts,
                ];
            }
        }

     
        $toutesActivites = $db->table('activites_sportives')
            ->orderBy('calories_brulees_heure', 'DESC')
            ->get()->getResultArray();

        return view('pages/activites', [
            'user'           => $user,
            'activitesPar'   => $activitesPar,
            'toutesActivites'=> $toutesActivites,
            'regimes'        => $regimes,
            'regimesAchetes' => $regimesAchetes,
        ]);
    }
}