<?php
namespace App\Controllers;

use App\Models\Regime;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Controller;

class RegimeCrud extends Controller
{
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

    public function store(): RedirectResponse
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
            return redirect()->to('/regimes');
        } else {
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
            return redirect()->to('/regimes');
        } else {
            $data['regime'] = $model->find($id);
            $data['validation'] = $this->validator;
            return view('regimes/ajouter', $data);
        }
    }

    public function delete($id)
    {
        $model = new Regime();
        $model->delete($id);
        return redirect()->to('/regimes');
    }
}
