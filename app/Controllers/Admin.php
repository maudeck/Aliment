<?php

namespace App\Controllers;

use Config\Database;

class Admin extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();

        // Compteurs généraux
        $totalUsers     = $db->table('users')->countAllResults();
        $totalGold      = $db->table('users')->where('is_gold', 1)->countAllResults();
        $totalRegimes   = $db->table('regimes')->countAllResults();
        $totalActivites = $db->table('activites_sportives')->countAllResults();

        // Codes portefeuille
        $codesTotal    = $db->table('codes_recharge')->countAllResults();
        $codesUtilises = $db->table('codes_recharge')->where('est_utilise', 1)->countAllResults();
        $codesDispo    = $codesTotal - $codesUtilises;

        // Chiffre d'affaires
        $caRow = $db->table('user_regimes')->selectSum('prix_paye')->get()->getRowArray();
        $chiffreAffaires = $caRow['prix_paye'] ?? 0;

        // Distribution IMC
        $imcRows = $db->table('etat_user')
            ->select('imc')
            ->where('imc IS NOT NULL')
            ->get()
            ->getResultArray();

        $imcCategories = [
            'Maigreur (<18.5)'   => 0,
            'Normal (18.5-24.9)' => 0,
            'Surpoids (25-29.9)' => 0,
            'Obésité (≥30)'      => 0,
        ];

        $imcValues = [];
        foreach ($imcRows as $row) {
            $imc = (float) $row['imc'];
            $imcValues[] = round($imc, 1);
            if ($imc < 18.5)   $imcCategories['Maigreur (<18.5)']++;
            elseif ($imc < 25) $imcCategories['Normal (18.5-24.9)']++;
            elseif ($imc < 30) $imcCategories['Surpoids (25-29.9)']++;
            else               $imcCategories['Obésité (≥30)']++;
        }

        $imcMoyenne = count($imcValues) > 0 ? round(array_sum($imcValues) / count($imcValues), 1) : 0;
        $imcMin     = count($imcValues) > 0 ? min($imcValues) : 0;
        $imcMax     = count($imcValues) > 0 ? max($imcValues) : 0;

        // Distribution par objectif
        $objectifRows = $db->table('user_objectifs uo')
            ->select('o.nom, COUNT(uo.id) as total')
            ->join('objectifs o', 'o.id = uo.objectif_id')
            ->groupBy('uo.objectif_id')
            ->get()->getResultArray();

        // Distribution par genre
        $genreRows = $db->table('users u')
            ->select('g.nom, COUNT(u.id) as total')
            ->join('genres g', 'g.id = u.genre_id')
            ->groupBy('u.genre_id')
            ->get()->getResultArray();

        // Inscriptions par mois (6 derniers mois)
        $inscriptionsRows = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as mois, COUNT(*) as total
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY MIN(created_at) ASC
        ")->getResultArray();

        // Top régimes achetés
        $topRegimesRows = $db->table('user_regimes ur')
            ->select('r.nom, COUNT(ur.id) as total')
            ->join('regimes r', 'r.id = ur.regime_id')
            ->groupBy('ur.regime_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Utilisateurs récents
        $recentUsers = $db->table('users u')
            ->select('u.nom, u.email, u.is_gold, u.created_at, g.nom as genre')
            ->join('genres g', 'g.id = u.genre_id', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $data = [
            'title'              => 'Admin Dashboard',
            'totalUsers'         => $totalUsers,
            'totalGold'          => $totalGold,
            'totalRegimes'       => $totalRegimes,
            'totalActivites'     => $totalActivites,
            'codesTotal'         => $codesTotal,
            'codesUtilises'      => $codesUtilises,
            'codesDispo'         => $codesDispo,
            'chiffreAffaires'    => $chiffreAffaires,
            'imcCategories'      => $imcCategories,
            'imcMoyenne'         => $imcMoyenne,
            'imcMin'             => $imcMin,
            'imcMax'             => $imcMax,
            'imcCount'           => count($imcValues),
            'objectifRows'       => $objectifRows,
            'genreRows'          => $genreRows,
            'inscriptionsRows'   => $inscriptionsRows,
            'topRegimesRows'     => $topRegimesRows,
            'recentUsers'        => $recentUsers,
        ];

        return view('admin/admin', $data);
    }
}