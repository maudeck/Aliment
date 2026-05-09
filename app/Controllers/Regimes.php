<?php

namespace App\Controllers;

use App\Models\UserRegime;
use App\Models\ActiviteSportive;
use CodeIgniter\HTTP\RedirectResponse;
use FPDF;

class Regimes extends BaseController
{
    protected $userRegimeModel;
    protected $activiteModel;

    public function __construct()
    {
        $this->userRegimeModel = new UserRegime();
        $this->activiteModel   = new ActiviteSportive();
    }

    public function index(): string|RedirectResponse
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/register'));
        }

        // Tous les régimes achetés avec infos complètes
        $regimes = $this->userRegimeModel->getRegimesAchetes($userId);

        // Pour chaque régime acheté, on charge ses activités
        foreach ($regimes as &$regime) {
            $regime['activites'] = $this->activiteModel->getByRegime($regime['regime_id']);
        }

        $data = [
            'regimes' => $regimes,
        ];

        return view('pages/regimes', $data);
    }

    public function exportPdf(int $achatId = 0)
    {
        $userId = session()->get('user_id');

        if (!$userId || $achatId <= 0) {
            return redirect()->to(base_url('/regimes'));
        }

        $db = \Config\Database::connect();
        $regimeData = $db->table('user_regimes ur')
            ->select('
                ur.id,
                ur.prix_paye,
                ur.created_at,
                r.id AS regime_id,
                r.nom,
                r.description,
                r.variation_poids,
                r.pourcentage_viande,
                r.pourcentage_poisson,
                r.pourcentage_volaille,
                d.nom AS duree_nom,
                d.nombre_jours
            ')
            ->join('regimes r', 'r.id = ur.regime_id')
            ->join('durees d', 'd.id = ur.duree_id')
            ->where('ur.id', $achatId)
            ->where('ur.user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$regimeData) {
            return redirect()->to(base_url('/regimes'));
        }

        $activites = $this->activiteModel->getByRegime($regimeData['regime_id']);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'NutriLife - Régime Détaillé', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->Ln(5);

   
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, $regimeData['nom'], 0, 1);

      
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(3);

        if (!empty($regimeData['description'])) {
            $pdf->MultiCell(0, 5, 'Description: ' . $regimeData['description']);
            $pdf->Ln(2);
        }

        $pdf->Cell(0, 5, 'Variation poids: ' . ($regimeData['variation_poids'] > 0 ? '+' : '') . $regimeData['variation_poids'] . ' kg', 0, 1);
        $pdf->Cell(0, 5, 'Durée: ' . $regimeData['duree_nom'] . ' (' . $regimeData['nombre_jours'] . ' jours)', 0, 1);
        $pdf->Cell(0, 5, 'Prix payé: ' . number_format($regimeData['prix_paye'], 0, ',', ' ') . ' Ar', 0, 1);
        $pdf->Cell(0, 5, 'Acheté le: ' . date('d/m/Y à H:i', strtotime($regimeData['created_at'])), 0, 1);

     
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, 'Répartition Nutritionnelle', 0, 1);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(45, 6, 'Viande', 1);
        $pdf->Cell(45, 6, 'Poisson', 1);
        $pdf->Cell(45, 6, 'Volaille', 1);
        $pdf->Ln();

        $pdf->Cell(45, 6, $regimeData['pourcentage_viande'] . '%', 1, 0, 'C');
        $pdf->Cell(45, 6, $regimeData['pourcentage_poisson'] . '%', 1, 0, 'C');
        $pdf->Cell(45, 6, $regimeData['pourcentage_volaille'] . '%', 1, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, 'Activités Recommandées', 0, 1);

        $pdf->SetFont('Arial', '', 10);
        if (!empty($activites)) {
            foreach ($activites as $activite) {
                $pdf->Cell(10, 6, '•', 0, 0);
                $nom = $activite['nom'];
                if (!empty($activite['calories_brulees_heure'])) {
                    $nom .= ' (' . $activite['calories_brulees_heure'] . ' kcal/h)';
                }
                $pdf->MultiCell(0, 6, $nom);
            }
        } else {
            $pdf->Cell(0, 6, 'Aucune activité recommandée.', 0, 1);
        }

        $filename = 'regime_' . $regimeData['nom'] . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
    }
}