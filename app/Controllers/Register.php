<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\EtatUser;
use App\Models\Objectif;
use App\Models\UserObjectif;
use App\Models\Portefeuille;
use CodeIgniter\HTTP\RedirectResponse;

class Register extends BaseController
{
    protected $userModel;
    protected $etatUserModel;
    protected $objectifModel;
    protected $userObjectifModel;
    protected $portefeuilleModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->etatUserModel = new EtatUser();
        $this->objectifModel = new Objectif();
        $this->userObjectifModel = new UserObjectif();
        $this->portefeuilleModel = new Portefeuille();
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

        $this->portefeuilleModel->insert([
            'user_id' => $userId,
            'solde'   => 0,
        ]);

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

        $etat = $this->etatUserModel
            ->where('user_id', session()->get('user_id'))
            ->first();

            $imc = $etat['imc'];

            if ($imc < 18.5) {
            $statut = 'Insuffisant';
            } elseif ($imc < 25) {
            $statut = 'Normal';
            } elseif ($imc < 30) {
            $statut = 'Surpoids';
            } else {
            $statut = 'Obesite';
            }

            session()->set('imc',    $imc);
            session()->set('statut', $statut);

        return redirect()->to(base_url('/register/objectif'));
    }

    public function objectif()
    {
        if (!session()->has('user_id')) {
            return redirect()->to(base_url('/register'));
        }

        $objectifs = $this->objectifModel->orderBy('id', 'ASC')->findAll();

        $data = [
            'title'     => 'Votre objectif',
            'imc'       => session()->get('imc'),
            'statut'    => session()->get('statut'),
            'objectifs' => $objectifs,
            'errors'    => session()->getFlashdata('errors'),
        ];

        return view('pages/objectif', $data);


    }

    public function storeObjectif(): RedirectResponse
    {
        if (!session()->has('user_id')) {
            return redirect()->to(base_url('/register'));
        }

        $rules = [
            'objectif_id' => 'required|is_not_unique[objectifs.id]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $objectifId = $this->request->getPost('objectif_id');

        $existing = $this->userObjectifModel->where('user_id', $userId)->first();

        if ($existing) {
            $this->userObjectifModel->update($existing['id'], [
                'user_id' => $userId,
                'objectif_id' => $objectifId,
            ]);
        } else {
            $this->userObjectifModel->insert([
                'user_id' => $userId,
                'objectif_id' => $objectifId,
            ]);
        }

        return redirect()->to(base_url('/home'));
    }
}