<?php

namespace App\Controllers;

use App\Models\Duree;
use App\Models\Genre;
use App\Models\Objectif;
use Config\Database;

class AdminSettings extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();

        $data = [
            'title' => 'Gestion des Paramètres',
            'genres' => (new Genre())->orderBy('id', 'DESC')->findAll(),
            'objectifs' => (new Objectif())->orderBy('id', 'DESC')->findAll(),
            'durees' => (new Duree())->orderBy('id', 'DESC')->findAll(),
            'goldMembers' => $db->table('users')->where('is_gold', 1)->countAllResults(),
            'goldSubscriptions' => $db->table('abonnements_gold')->where('actif', 1)->countAllResults(),
            'flash_success' => session()->getFlashdata('success'),
            'flash_error' => session()->getFlashdata('error'),
        ];

        return view('admin/settings', $data);
    }

    public function store(string $type)
    {
        return $this->saveItem($type);
    }

    public function update(string $type, int $id)
    {
        return $this->saveItem($type, $id);
    }

    public function delete(string $type, int $id)
    {
        $model = $this->resolveModel($type);

        if ($model === null) {
            session()->setFlashdata('error', 'Type de paramètre invalide.');
            return redirect()->to(base_url('/admin/settings'));
        }

        if (!$model->find($id)) {
            session()->setFlashdata('error', 'Élément introuvable.');
            return redirect()->to(base_url('/admin/settings'));
        }

        $model->delete($id);
        session()->setFlashdata('success', 'Élément supprimé avec succès.');

        return redirect()->to(base_url('/admin/settings'));
    }

    private function saveItem(string $type, ?int $id = null)
    {
        $model = $this->resolveModel($type);

        if ($model === null) {
            session()->setFlashdata('error', 'Type de paramètre invalide.');
            return redirect()->to(base_url('/admin/settings'));
        }

        $data = [];
        $rules = [];

        if ($type === 'genre') {
            $data['nom'] = trim((string) $this->request->getPost('nom'));
            $rules['nom'] = 'required|max_length[20]';
        } elseif ($type === 'objectif') {
            $data['nom'] = trim((string) $this->request->getPost('nom'));
            $data['description'] = trim((string) $this->request->getPost('description'));
            $rules['nom'] = 'required|max_length[100]';
            $rules['description'] = 'permit_empty';
        } elseif ($type === 'duree') {
            $data['nom'] = trim((string) $this->request->getPost('nom'));
            $data['nombre_jours'] = (int) $this->request->getPost('nombre_jours');
            $rules['nom'] = 'required|max_length[50]';
            $rules['nombre_jours'] = 'required|numeric';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Veuillez corriger le formulaire.');
            return redirect()->back()->withInput();
        }

        if ($type === 'genre' || $type === 'objectif') {
            $this->ensureUnique($type, (string) $data['nom'], $id);
        }

        if ($id === null) {
            $model->insert($data);
            session()->setFlashdata('success', 'Élément créé avec succès.');
        } else {
            if (!$model->find($id)) {
                session()->setFlashdata('error', 'Élément introuvable.');
                return redirect()->to(base_url('/admin/settings'));
            }

            $model->update($id, $data);
            session()->setFlashdata('success', 'Élément mis à jour avec succès.');
        }

        return redirect()->to(base_url('/admin/settings'));
    }

    private function resolveModel(string $type)
    {
        return match ($type) {
            'genre' => new Genre(),
            'objectif' => new Objectif(),
            'duree' => new Duree(),
            default => null,
        };
    }

    private function ensureUnique(string $type, string $value, ?int $id = null): void
    {
        $db = Database::connect();
        $table = match ($type) {
            'genre' => 'genres',
            'objectif' => 'objectifs',
            default => null,
        };

        if ($table === null) {
            return;
        }

        $builder = $db->table($table)->where('nom', $value);
        if ($id !== null) {
            $builder->where('id !=', $id);
        }

        if ($builder->countAllResults() > 0) {
            session()->setFlashdata('error', 'Cette valeur existe déjà.');
            redirect()->to(base_url('/admin/settings'))->send();
            exit;
        }
    }
}
