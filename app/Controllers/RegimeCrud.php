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

    public function api()
    {
        $model = new Regime();
        $search = trim((string) $this->request->getGet('search'));
        
        if ($search !== '') {
            $regimes = $model->like('nom', $search)
                ->orLike('description', $search)
                ->findAll();
        } else {
            $regimes = $model->findAll();
        }
        
        return $this->response->setJSON($regimes);
    }

    public function filter()
    {
        $model = new Regime();
        $search = trim((string) $this->request->getGet('q'));

        if ($search !== '') {
            $regimes = $model->groupStart()
                ->like('nom', $search)
                ->orLike('description', $search)
                ->groupEnd()
                ->findAll();
        } else {
            $regimes = $model->findAll();
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $regimes,
        ]);
    }

    public function store(): RedirectResponse|string
    {
        helper(['form']);
        $model = new Regime();
        $rules = [
            'nom' => 'required',
            'description' => 'required',
            'variation_poids' => 'required|decimal',
            'pourcentage_viande' => 'permit_empty|decimal',
            'pourcentage_poisson' => 'permit_empty|decimal',
            'pourcentage_volaille' => 'permit_empty|decimal',
        ];
        if ($this->validate($rules)) {
            $model->save([
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
                'variation_poids' => $this->request->getPost('variation_poids'),
                'pourcentage_viande' => $this->request->getPost('pourcentage_viande'),
                'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson'),
                'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            ]);
            // Check if AJAX request
            if ($this->isAjaxRequest()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Régime enregistré avec succès.',
                    'data' => $model->find($model->getInsertID())
                ]);
            }
            return redirect()->to('/regimes');
        } else {
            if ($this->isAjaxRequest()) {
                $errors = $this->validator->getErrors();
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => $errors
                ]);
            }
            session()->setFlashdata('validation', $this->validator);
            return redirect()->to('/regimes')->withInput();
        }
    }

    public function edit($id): string
    {
        $model = new Regime();
        $data['regime'] = $model->find($id);
        return view('regimes/ajouter', $data);
    }

    public function update($id): RedirectResponse|string
    {
        helper(['form']);
        $model = new Regime();
        $rules = [
            'nom' => 'required',
            'description' => 'required',
            'variation_poids' => 'required|decimal',
            'pourcentage_viande' => 'permit_empty|decimal',
            'pourcentage_poisson' => 'permit_empty|decimal',
            'pourcentage_volaille' => 'permit_empty|decimal',
        ];
        if ($this->validate($rules)) {
            $model->update($id, [
                'nom' => $this->request->getPost('nom'),
                'description' => $this->request->getPost('description'),
                'variation_poids' => $this->request->getPost('variation_poids'),
                'pourcentage_viande' => $this->request->getPost('pourcentage_viande'),
                'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson'),
                'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            ]);
            // Check if AJAX request
            if ($this->isAjaxRequest()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Régime mis à jour avec succès.',
                    'data' => $model->find($id)
                ]);
            }
            return redirect()->to('/regimes');
        } else {
            if ($this->isAjaxRequest()) {
                $errors = $this->validator->getErrors();
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => $errors
                ]);
            }
            $data['regime'] = $model->find($id);
            $data['validation'] = $this->validator;
            return view('regimes/ajouter', $data);
        }
    }

    public function delete($id): RedirectResponse|string
    {
        $model = new Regime();
        $model->delete($id);
        if ($this->isAjaxRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Régime supprimé avec succès.'
            ]);
        }
        return redirect()->to('/regimes');
    }

}
