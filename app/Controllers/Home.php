<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\EtatUser;
use App\Models\UserObjectif;
use App\Models\Objectif;
use App\Models\Regime;
use App\Models\RegimeObjectif;
use App\Models\ActiviteSportive;
use App\Models\Portefeuille;
use App\Models\UserRegime;
use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    protected $userModel;
    protected $etatUserModel;
    protected $userObjectifModel;
    protected $objectifModel;
    protected $regimeModel;
    protected $regimeObjectifModel;
    protected $activiteModel;
    protected $portefeuilleModel;
    protected $userRegimeModel;

    public function __construct()
    {
        $this->userModel           = new User();
        $this->etatUserModel       = new EtatUser();
        $this->userObjectifModel   = new UserObjectif();
        $this->objectifModel       = new Objectif();
        $this->regimeModel         = new Regime();
        $this->regimeObjectifModel = new RegimeObjectif();
        $this->activiteModel       = new ActiviteSportive();
        $this->portefeuilleModel   = new Portefeuille();
        $this->userRegimeModel     = new UserRegime();
    }

    public function index(): string|RedirectResponse
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        $user         = $this->userModel->find($userId);
        $etat         = $this->etatUserModel->where('user_id', $userId)->first();
        $userObjectif = $this->userObjectifModel->where('user_id', $userId)->first();
        $objectifNom  = null;

        if ($userObjectif) {
            $objectif = $this->objectifModel->find($userObjectif['objectif_id']);
            if ($objectif) {
                $objectifNom = $objectif['nom'];
            }
        }

        $regimes   = [];
        $activites = [];

        if ($userObjectif) {
            $regimeObjectifs = $this->regimeObjectifModel
                ->where('objectif_id', $userObjectif['objectif_id'])
                ->findAll();

            foreach ($regimeObjectifs as $ro) {
                $regime = $this->regimeModel->getAvecPrix($ro['regime_id'], dureeId: 3);
                if ($regime) {
                    $regime['achete'] = $this->userRegimeModel->aAchete($userId, $regime['id']);
                    $regimes[] = $regime;
                }
            }

            if (!empty($regimes)) {
                $activites = $this->activiteModel->getByRegime($regimes[0]['id']);
            }
        }

        $solde = $this->portefeuilleModel->getSolde($userId);

        $data = [
            'user'        => $user,
            'etat'        => $etat,
            'objectifNom' => $objectifNom,
            'regimes'     => $regimes,
            'activites'   => $activites,
            'solde'       => $solde,
        ];

        return view('pages/home', $data);
    }

    // ── Achat d'un régime ─────────────────────────────────────────

    public function acheter(): RedirectResponse
    {
        $userId   = session()->get('user_id');
        $regimeId = (int) $this->request->getPost('regime_id');
        $dureeId  = (int) $this->request->getPost('duree_id') ?: 3;

        if (!$userId || !$regimeId) {
            return redirect()->to(base_url('/home'));
        }

        if ($this->userRegimeModel->aAchete($userId, $regimeId)) {
            return redirect()->to(base_url('/home'));
        }

        $regime = $this->regimeModel->getAvecPrix($regimeId, $dureeId);
        if (!$regime || empty($regime['prix'])) {
            return redirect()->to(base_url('/home'));
        }

        $prix  = $regime['prix'];
        $solde = $this->portefeuilleModel->getSolde($userId);

        if ($solde < $prix) {
            session()->setFlashdata('erreur', 'Solde insuffisant.');
            return redirect()->to(base_url('/home'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('portefeuilles')
           ->where('user_id', $userId)
           ->update(['solde' => $solde - $prix]);

        $this->userRegimeModel->insert([
            'user_id'   => $userId,
            'regime_id' => $regimeId,
            'duree_id'  => $dureeId,
            'prix_paye' => $prix,
        ]);

        $db->transComplete();

        session()->setFlashdata('succes', 'Achat validé !');
        return redirect()->to(base_url('/home'));
    }

    // ── Recharge du portefeuille depuis home ──────────────────────

    public function recharger(): RedirectResponse
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        $code = $this->request->getPost('code');

        if (empty($code)) {
            session()->setFlashdata('wallet_erreur', 'Veuillez entrer un code.');
            return redirect()->to(base_url('/home'));
        }

        $resultat = $this->portefeuilleModel->utiliserCode($code, $userId);

        if ($resultat['success']) {
            $montantFormate = number_format($resultat['montant'], 0, ',', ' ');
            session()->setFlashdata('wallet_succes', "✓ {$resultat['message']} +{$montantFormate} Ar ajoutés.");
        } else {
            session()->setFlashdata('wallet_erreur', $resultat['message']);
        }

        return redirect()->to(base_url('/home'));
    }
}