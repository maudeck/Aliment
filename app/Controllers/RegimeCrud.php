<?php
namespace App\Controllers;

use App\Models\Regime;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Controller;

class RegimeCrud extends Controller
{
    private function isAjaxRequest(): bool
    {
        return strtolower($this->request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
    }

    public function api()
    {
        $db = \Config\Database::connect();
        $type = $this->request->getGet('type') ?? 'regimes';
        $search = trim((string) $this->request->getGet('search'));

        if ($type === 'objectifs') {
            $result = $db->table('objectifs')->select('id, nom')->get()->getResultArray();
            return $this->response->setJSON($result);
        }

        if ($type === 'activities') {
            $result = $db->table('activites_sportives')->select('id, nom')->get()->getResultArray();
            return $this->response->setJSON($result);
        }

        // Par défaut: retourner les régimes
        $builder = $db->table('regimes r')
            ->select('r.id, r.nom, r.description, r.variation_poids, rp.prix, r.pourcentage_viande, r.pourcentage_poisson, r.pourcentage_volaille, o.id AS objectif_id, o.nom AS objectif_nom, GROUP_CONCAT(DISTINCT a.id ORDER BY a.id SEPARATOR ",") AS activity_ids, GROUP_CONCAT(DISTINCT a.nom ORDER BY a.nom SEPARATOR ", ") AS activity_noms')
            ->join('regime_objectifs ro', 'ro.regime_id = r.id', 'left')
            ->join('objectifs o', 'o.id = ro.objectif_id', 'left')
            ->join('regime_activites ra', 'ra.regime_id = r.id', 'left')
            ->join('activites_sportives a', 'a.id = ra.activite_id', 'left')
            ->join('regime_prix rp', 'rp.regime_id = r.id AND rp.duree_id = 3', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('r.nom', $search)
                ->orLike('r.description', $search)
                ->orLike('o.nom', $search)
                ->orLike('a.nom', $search)
                ->groupEnd();
        }

        $result = $builder->groupBy('r.id')
            ->orderBy('r.id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($result);
    }

    public function index(): string
    {
        $model = new Regime();
        $data['regimes'] = $model->findAll();
        $data['validation'] = session()->getFlashdata('validation');
        return view('pages/regimes', $data);
    }

    public function create(): string
    {
        helper(['form']);
        return view('regimes/ajouter');
    }

    public function store()
    {
        helper(['form']);
        $model = new Regime();
        
        $rules = [
            'name' => 'required|max_length[150]',
            'description' => 'required',
            'price' => 'required|decimal',
            'prix' => 'required|decimal',
            'objectif' => 'required|numeric',
            'proteines' => 'permit_empty|numeric',
            'glucides' => 'permit_empty|numeric',
            'lipides' => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            session()->setFlashdata('validation', $this->validator);
            return redirect()->back()->withInput();
        }

        $activityIds = $this->request->getPost('activity_id') ?? [];
        if (!is_array($activityIds)) {
            $activityIds = [$activityIds];
        }
        $activityIds = array_values(array_unique(array_filter(array_map('intval', $activityIds), static fn ($value) => $value > 0)));
        if (empty($activityIds)) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => ['activity_id' => 'Veuillez sélectionner au moins une activité.']
                ]);
            }
            session()->setFlashdata('validation', ['activity_id' => 'Veuillez sélectionner au moins une activité.']);
            return redirect()->back()->withInput();
        }

        try {
            $regimeData = [
                'nom' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'variation_poids' => $this->request->getPost('price') ?? 0,
                'pourcentage_viande' => $this->request->getPost('proteines') ?? 30,
                'pourcentage_poisson' => $this->request->getPost('glucides') ?? 40,
                'pourcentage_volaille' => $this->request->getPost('lipides') ?? 30,
            ];

            $regimeId = $model->insert($regimeData);
            $prix = (float) ($this->request->getPost('prix') ?? 0);
            $model->upsertPrix((int) $regimeId, 3, $prix);

            // Ajouter l'objectif
            $objectifId = (int) $this->request->getPost('objectif');
            if ($objectifId) {
                $db = \Config\Database::connect();
                $db->table('regime_objectifs')->insert([
                    'regime_id' => $regimeId,
                    'objectif_id' => $objectifId
                ]);
            }

            // Ajouter les activités
            $db = \Config\Database::connect();
            foreach ($activityIds as $activityId) {
                $db->table('regime_activites')->insert([
                    'regime_id' => $regimeId,
                    'activite_id' => $activityId
                ]);
            }

            if ($this->isAjaxRequest()) {
                $regime = $model->find($regimeId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Régime créé avec succès.',
                    'data' => $regime
                ]);
            }

            return redirect()->to('/admin/regimes');
        } catch (\Exception $e) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Erreur serveur: ' . $e->getMessage()
                ]);
            }
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id): string
    {
        $model = new Regime();
        $data['regime'] = $model->find($id);
        return view('regimes/ajouter', $data);
    }

    public function update($id)
    {
        helper(['form']);
        $model = new Regime();
        
        $rules = [
            'name' => 'required|max_length[150]',
            'description' => 'required',
            'price' => 'required|decimal',
            'prix' => 'required|decimal',
            'objectif' => 'required|numeric',
            'proteines' => 'permit_empty|numeric',
            'glucides' => 'permit_empty|numeric',
            'lipides' => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            $data['regime'] = $model->find($id);
            $data['validation'] = $this->validator;
            return view('pages/regimes', $data);
        }

        $activityIds = $this->request->getPost('activity_id') ?? [];
        if (!is_array($activityIds)) {
            $activityIds = [$activityIds];
        }
        $activityIds = array_values(array_unique(array_filter(array_map('intval', $activityIds), static fn ($value) => $value > 0)));
        if (empty($activityIds)) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => ['activity_id' => 'Veuillez sélectionner au moins une activité.']
                ]);
            }
            $data['regime'] = $model->find($id);
            $data['validation'] = ['activity_id' => 'Veuillez sélectionner au moins une activité.'];
            return view('pages/regimes', $data);
        }

        try {
            $regimeData = [
                'nom' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'variation_poids' => $this->request->getPost('price') ?? 0,
                'pourcentage_viande' => $this->request->getPost('proteines') ?? 30,
                'pourcentage_poisson' => $this->request->getPost('glucides') ?? 40,
                'pourcentage_volaille' => $this->request->getPost('lipides') ?? 30,
            ];

            $model->update($id, $regimeData);
            $prix = (float) ($this->request->getPost('prix') ?? 0);
            $model->upsertPrix((int) $id, 3, $prix);

            // Mettre à jour l'objectif
            $db = \Config\Database::connect();
            $db->table('regime_objectifs')->where('regime_id', $id)->delete();
            $objectifId = (int) $this->request->getPost('objectif');
            if ($objectifId) {
                $db->table('regime_objectifs')->insert([
                    'regime_id' => $id,
                    'objectif_id' => $objectifId
                ]);
            }

            // Mettre à jour l'activité unique
            $db->table('regime_activites')->where('regime_id', $id)->delete();
            foreach ($activityIds as $activityId) {
                $db->table('regime_activites')->insert([
                    'regime_id' => $id,
                    'activite_id' => $activityId
                ]);
            }

            if ($this->isAjaxRequest()) {
                $regime = $model->find($id);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Régime mis à jour avec succès.',
                    'data' => $regime
                ]);
            }

            return redirect()->to('/admin/regimes');
        } catch (\Exception $e) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Erreur serveur: ' . $e->getMessage()
                ]);
            }
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $model = new Regime();
        try {
            $db = \Config\Database::connect();
            // Supprimer les relations
            $db->table('regime_objectifs')->where('regime_id', $id)->delete();
            $db->table('regime_activites')->where('regime_id', $id)->delete();
            $db->table('regime_prix')->where('regime_id', $id)->delete();
            
            // Supprimer le régime
            $model->delete($id);
            
            if ($this->isAjaxRequest()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Régime supprimé avec succès.'
                ]);
            }
            return redirect()->to('/admin/regimes');
        } catch (\Exception $e) {
            if ($this->isAjaxRequest()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ]);
            }
            session()->setFlashdata('error', 'Erreur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
