<?php

namespace App\Controllers;

use Config\Database;

class AdminCodes extends BaseController
{
  
    public function index(): string
    {
        $db = Database::connect();

        // Tous les codes avec info utilisateur si utilisé
        $codes = $db->table('codes_recharge cr')
            ->select('cr.*, u.nom as utilisateur_nom, u.email as utilisateur_email, rp.created_at as utilise_le')
            ->join('recharge_portefeuille rp', 'rp.code_id = cr.id', 'left')
            ->join('users u', 'u.id = rp.user_id', 'left')
            ->orderBy('cr.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Statistiques rapides
        $total     = count($codes);
        $utilises  = count(array_filter($codes, fn($c) => $c['est_utilise']));
        $disponibles = $total - $utilises;
        $totalMontant = array_sum(array_column($codes, 'montant'));

        $data = [
            'title'       => 'Validation des Codes Portefeuille',
            'codes'       => $codes,
            'total'       => $total,
            'utilises'    => $utilises,
            'disponibles' => $disponibles,
            'totalMontant'=> $totalMontant,
            'flash_succes'=> session()->getFlashdata('succes'),
            'flash_erreur'=> session()->getFlashdata('erreur'),
        ];

        return view('admin/codes', $data);
    }

    // ── Créer un nouveau code ────────────────────────────────────
    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = Database::connect();

        $code    = strtoupper(trim($this->request->getPost('code') ?? ''));
        $montant = (float) ($this->request->getPost('montant') ?? 0);

        if (empty($code) || $montant <= 0) {
            session()->setFlashdata('erreur', 'Code et montant sont obligatoires.');
            return redirect()->to(base_url('/admin/codes'));
        }

        // Vérifier unicité
        $exists = $db->table('codes_recharge')->where('code', $code)->countAllResults();
        if ($exists > 0) {
            session()->setFlashdata('erreur', "Le code « {$code} » existe déjà.");
            return redirect()->to(base_url('/admin/codes'));
        }

        $db->table('codes_recharge')->insert([
            'code'       => $code,
            'montant'    => $montant,
            'est_utilise'=> 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('succes', "Code « {$code} » créé avec succès (montant : " . number_format($montant, 0, ',', ' ') . " Ar).");
        return redirect()->to(base_url('/admin/codes'));
    }

    // ── Supprimer un code (non utilisé seulement) ────────────────
    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = Database::connect();

        $code = $db->table('codes_recharge')->where('id', $id)->get()->getRowArray();

        if (!$code) {
            session()->setFlashdata('erreur', 'Code introuvable.');
            return redirect()->to(base_url('/admin/codes'));
        }

        if ($code['est_utilise']) {
            session()->setFlashdata('erreur', 'Impossible de supprimer un code déjà utilisé.');
            return redirect()->to(base_url('/admin/codes'));
        }

        $db->table('codes_recharge')->where('id', $id)->delete();
        session()->setFlashdata('succes', "Code « {$code['code']} » supprimé.");
        return redirect()->to(base_url('/admin/codes'));
    }

    // ── Générer des codes en lot ─────────────────────────────────
    public function generateBatch(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db      = Database::connect();
        $montant = (float) ($this->request->getPost('montant') ?? 0);
        $nombre  = (int)   ($this->request->getPost('nombre') ?? 1);
        $prefix  = strtoupper(trim($this->request->getPost('prefix') ?? 'NUTRI'));

        if ($montant <= 0 || $nombre < 1 || $nombre > 50) {
            session()->setFlashdata('erreur', 'Montant invalide ou nombre entre 1 et 50.');
            return redirect()->to(base_url('/admin/codes'));
        }

        $created = 0;
        for ($i = 0; $i < $nombre; $i++) {
            $code = $prefix . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            // S'assurer de l'unicité
            $exists = $db->table('codes_recharge')->where('code', $code)->countAllResults();
            if ($exists === 0) {
                $db->table('codes_recharge')->insert([
                    'code'       => $code,
                    'montant'    => $montant,
                    'est_utilise'=> 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $created++;
            }
        }

        session()->setFlashdata('succes', "{$created} code(s) générés avec succès.");
        return redirect()->to(base_url('/admin/codes'));
    }
}