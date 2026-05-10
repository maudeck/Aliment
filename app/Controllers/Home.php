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
use CodeIgniter\HTTP\ResponseInterface;

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
        $isGold       = !empty($user['is_gold']);
        $etat         = $this->etatUserModel->where('user_id', $userId)->first();
        $userObjectif = $this->userObjectifModel->where('user_id', $userId)->first();
        $objectifNom  = null;

        if ($userObjectif) {
            $objectif = $this->objectifModel->find($userObjectif['objectif_id']);
            if ($objectif) {
                $objectifNom = $objectif['nom'];
            }
        }
        $regimes = [];
        $activites = [];

        if ($userObjectif) {
            $regimeObjectifs = $this->regimeObjectifModel
                ->where('objectif_id', $userObjectif['objectif_id'])
                ->findAll();

            foreach ($regimeObjectifs as $ro) {
                $regime = $this->regimeModel->getAvecPrix($ro['regime_id'], dureeId: 3, appliquerRemiseGold: $isGold);
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
            'isGold'      => $isGold,
        ];

        return view('pages/home', $data);
    }

    // ── Achat d'un régime ─────────────────────────────────────────

    public function acheter(): RedirectResponse|ResponseInterface
    {
        $userId   = session()->get('user_id');
        $regimeId = (int) $this->request->getPost('regime_id');
        $dureeId  = (int) $this->request->getPost('duree_id') ?: 3;

        if (!$userId || !$regimeId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ou régime invalide.'
                ]);
            }

            return redirect()->to(base_url('/home'));
        }

        if ($this->userRegimeModel->aAchete($userId, $regimeId)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ce régime est déjà acheté.'
                ]);
            }

            return redirect()->to(base_url('/home'));
        }

        $user = $this->userModel->find($userId);
        $isGold = !empty($user['is_gold']);

        $regime = $this->regimeModel->getAvecPrix($regimeId, $dureeId, $isGold);
        if (!$regime || empty($regime['prix'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Régime introuvable ou sans prix.'
                ]);
            }

            return redirect()->to(base_url('/home'));
        }

        $prix  = $regime['prix'];
        $solde = $this->portefeuilleModel->getSolde($userId);

        if ($solde < $prix) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solde insuffisant.'
                ]);
            }

            session()->setFlashdata('erreur', 'miskine tu est pauvre 😂');
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

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Achat validé !',
                'data' => [
                    'regime_id' => $regimeId,
                    'prix' => $prix,
                    'solde' => $this->portefeuilleModel->getSolde($userId),
                ],
            ]);
        }

        session()->setFlashdata('succes', 'Achat validé !');
        return redirect()->to(base_url('/home'));
    }

    // ── Achat de l'option Gold ───────────────────────────────────

    public function devenirGold(): RedirectResponse|ResponseInterface
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expirée.'
                ]);
            }

            return redirect()->to(base_url('/register'));
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Utilisateur introuvable.'
                ]);
            }

            return redirect()->to(base_url('/home'));
        }

        if (!empty($user['is_gold'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'L\'option Gold est déjà activée.'
                ]);
            }

            session()->setFlashdata('succes', "L'option Gold est déjà activée.");
            return redirect()->to(base_url('/home'));
        }

        $prixGold = 120000;
        $solde = $this->portefeuilleModel->getSolde($userId);

        if ($solde < $prixGold) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Solde insuffisant pour activer Gold.'
                ]);
            }

            session()->setFlashdata('wallet_erreur', "tu es trop pauvre pour ahceter ca frero va taffer espece de pd");
            return redirect()->to(base_url('/home'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('portefeuilles')
           ->where('user_id', $userId)
           ->set('solde', "solde - {$prixGold}", false)
           ->update();

        $this->userModel->update($userId, [
            'is_gold' => true,
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Une erreur est survenue pendant l'activation Gold."
                ]);
            }

            session()->setFlashdata('wallet_erreur', "Une erreur est survenue pendant l'activation Gold.");
            return redirect()->to(base_url('/home'));
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Option Gold activée.',
                'data' => [
                    'solde' => $this->portefeuilleModel->getSolde($userId),
                ],
            ]);
        }

        session()->setFlashdata('succes', 'Option Gold activée. Les régimes affichent maintenant la remise de 15%.');
        return redirect()->to(base_url('/home'));
    }


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

            return redirect()->to(base_url('/register'));
        }

        $code = $this->request->getPost('code');

        if (empty($code)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Veuillez entrer un code.'
                ]);
            }

            session()->setFlashdata('wallet_erreur', 'Veuillez entrer un code.');
            return redirect()->to(base_url('/home'));
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

            session()->setFlashdata('wallet_succes', "✓ {$resultat['message']} +{$montantFormate} Ar ajoutés.");
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $resultat['message'],
                ]);
            }

            session()->setFlashdata('wallet_erreur', $resultat['message']);
        }

        return redirect()->to(base_url('/home'));
    }
}