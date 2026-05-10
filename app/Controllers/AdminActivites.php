<?php

namespace App\Controllers;

use App\Models\ActiviteSportive;

class AdminActivites extends BaseController
{
    public function index(): string
    {
        $model = new ActiviteSportive();

        $data = [
            'title' => 'Gestion des Activités Sportives',
            'activites' => $model->orderBy('id', 'DESC')->findAll(),
            'flash_success' => session()->getFlashdata('success'),
            'flash_error' => session()->getFlashdata('error'),
        ];

        return view('admin/activites', $data);
    }

    public function store()
    {
        return $this->saveActivity();
    }

    public function update(int $id)
    {
        return $this->saveActivity($id);
    }

    public function delete(int $id)
    {
        $model = new ActiviteSportive();
        $activity = $model->find($id);

        if (!$activity) {
            session()->setFlashdata('error', 'Activité introuvable.');
            return redirect()->to(base_url('/admin/activites'));
        }

        $model->delete($id);
        session()->setFlashdata('success', 'Activité supprimée avec succès.');

        return redirect()->to(base_url('/admin/activites'));
    }

    private function saveActivity(?int $id = null)
    {
        $model = new ActiviteSportive();

        $rules = [
            'nom' => 'required|max_length[100]',
            'description' => 'required',
            'calories_brulees_heure' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Veuillez corriger les champs de l’activité.');
            return redirect()->back()->withInput();
        }

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'calories_brulees_heure' => (int) $this->request->getPost('calories_brulees_heure'),
        ];

        if ($id === null) {
            $model->insert($data);
            session()->setFlashdata('success', 'Activité créée avec succès.');
        } else {
            if (!$model->find($id)) {
                session()->setFlashdata('error', 'Activité introuvable.');
                return redirect()->to(base_url('/admin/activites'));
            }

            $model->update($id, $data);
            session()->setFlashdata('success', 'Activité mise à jour avec succès.');
        }

        return redirect()->to(base_url('/admin/activites'));
    }
}
