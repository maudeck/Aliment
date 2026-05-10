<?php

namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\User;
use App\Models\UserRegime;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class PortefeuilleController extends BaseController
{
    protected $portefeuilleModel;
    protected $userModel;
    protected $userRegimeModel;

    public function __construct()
    {
        $this->portefeuilleModel = new Portefeuille();
        $this->userModel         = new User();
        $this->userRegimeModel   = new UserRegime();
    }

    // ── Page portefeuille ─────────────────────────────────────────

    public function index(): string|RedirectResponse
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/login'));
        }

        $user          = $this->userModel->find($userId);
        $solde         = $this->portefeuilleModel->getSolde($userId);
        $historique    = $this->portefeuilleModel->getHistorique($userId);
        $achatsRegimes = $this->userRegimeModel->getRegimesAchetes($userId);

        return view('pages/portefeuille', [
            'user'          => $user,
            'solde'         => $solde,
            'historique'    => $historique,    // recharges via code
            'achatsRegimes' => $achatsRegimes, // achats de régimes
        ]);
    }

    // ── Recharge via code (POST depuis portefeuille.php) ──────────

    public function recharger(): RedirectResponse|ResponseInterface
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expirée.'
                ]);
            }

            return redirect()->to(base_url('/login'));
        }

        $code = trim($this->request->getPost('code') ?? '');

        if (empty($code)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Veuillez entrer un code.'
                ]);
            }

            session()->setFlashdata('erreur', 'Veuillez entrer un code.');
            return redirect()->to(base_url('/portefeuille'));
        }

        $resultat = $this->portefeuilleModel->utiliserCode($code, $userId);

        if ($resultat['success']) {
            $montantFormate = number_format($resultat['montant'], 0, ',', ' ');

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $resultat['message'],
                    'data' => [
                        'montant' => $resultat['montant'],
                        'solde' => $this->portefeuilleModel->getSolde($userId),
                    ],
                ]);
            }

            session()->setFlashdata('succes', "✓ {$resultat['message']} +{$montantFormate} Ar ajoutés.");
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $resultat['message'],
                ]);
            }

            session()->setFlashdata('erreur', $resultat['message']);
        }

        return redirect()->to(base_url('/portefeuille'));
    }
}