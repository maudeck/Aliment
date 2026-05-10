<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class AdminRegimes extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Gestion des Régimes'];
        return view('admin/regimes', $data);
    }

    public function store(): ResponseInterface
    {
        $name = trim((string) $this->request->getPost('name'));
        $objectif = trim((string) $this->request->getPost('objectif'));
        $price = trim((string) $this->request->getPost('price'));
        $description = trim((string) $this->request->getPost('description'));
        $proteines = trim((string) $this->request->getPost('proteines'));
        $glucides = trim((string) $this->request->getPost('glucides'));
        $lipides = trim((string) $this->request->getPost('lipides'));
        $activities = $this->request->getPost('activities') ?? [];

        if ($name === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Le nom du régime est requis.'
            ]);
        }

        if (!is_array($activities)) {
            $activities = [$activities];
        }

        $activities = array_values(array_filter(array_map('trim', $activities), static fn ($value) => $value !== ''));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Régime ajouté avec succès.',
            'data' => [
                'name' => $name,
                'objectif' => $objectif,
                'price' => $price,
                'description' => $description,
                'proteines' => $proteines,
                'glucides' => $glucides,
                'lipides' => $lipides,
                'activities' => $activities,
            ],
        ]);
    }
}
