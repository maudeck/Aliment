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

        if (!$userId) {
            return redirect()->to(base_url('/regimes'));
        }

        // Export global: achatId = 0
        if ($achatId === 0) {
            $db = \Config\Database::connect();

            $rows = $db->table('user_regimes ur')
                ->select('
                    ur.id           AS achat_id,
                    ur.prix_paye,
                    ur.created_at   AS date_achat,
                    r.id            AS regime_id,
                    r.nom           AS regime_nom,
                    r.description   AS regime_description,
                    r.variation_poids,
                    r.pourcentage_viande,
                    r.pourcentage_poisson,
                    r.pourcentage_volaille,
                    d.nom           AS duree_nom,
                    d.nombre_jours
                ')
                ->join('regimes r', 'r.id = ur.regime_id')
                ->join('durees d',  'd.id = ur.duree_id')
                ->where('ur.user_id', $userId)
                ->orderBy('ur.created_at', 'DESC')
                ->get()
                ->getResultArray();

            if (empty($rows)) {
                return redirect()->to(base_url('/regimes'));
            }

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'NutriLife - Mes Régimes Achetés', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Ln(4);

            foreach ($rows as $index => $regimeData) {
                if ($index > 0) {
                    $pdf->Ln(2);
                    $pdf->Cell(0, 0, str_repeat('-', 95), 0, 1);
                    $pdf->Ln(4);
                }

                $pdf->SetFont('Arial', 'B', 13);
                $pdf->MultiCell(0, 7, $regimeData['regime_nom']);

                $pdf->SetFont('Arial', '', 10);
                if (!empty($regimeData['regime_description'])) {
                    $pdf->MultiCell(0, 5, 'Description: ' . $regimeData['regime_description']);
                    $pdf->Ln(1);
                }

                $pdf->Cell(0, 5, 'Variation poids: ' . ((float) $regimeData['variation_poids'] > 0 ? '+' : '') . $regimeData['variation_poids'] . ' kg', 0, 1);
                $pdf->Cell(0, 5, 'Duree: ' . $regimeData['duree_nom'] . ' (' . $regimeData['nombre_jours'] . ' jours)', 0, 1);
                $pdf->Cell(0, 5, 'Prix paye: ' . number_format((float) $regimeData['prix_paye'], 0, ',', ' ') . ' Ar', 0, 1);
                $pdf->Cell(0, 5, 'Achete le: ' . date('d/m/Y a H:i', strtotime($regimeData['date_achat'])), 0, 1);
                $pdf->Ln(3);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 7, 'Repartition Nutritionnelle', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(45, 6, 'Viande', 1);
                $pdf->Cell(45, 6, 'Poisson', 1);
                $pdf->Cell(45, 6, 'Volaille', 1);
                $pdf->Ln();
                $pdf->Cell(45, 6, $regimeData['pourcentage_viande'] . '%', 1, 0, 'C');
                $pdf->Cell(45, 6, $regimeData['pourcentage_poisson'] . '%', 1, 0, 'C');
                $pdf->Cell(45, 6, $regimeData['pourcentage_volaille'] . '%', 1, 1, 'C');
                $pdf->Ln(3);

                $activites = $this->activiteModel->getByRegime((int) $regimeData['regime_id']);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 7, 'Activites Recommandees', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                if (!empty($activites)) {
                    foreach ($activites as $activite) {
                        $nom = $activite['nom'] ?? '';
                        if ($nom === '') {
                            continue;
                        }
                        if (!empty($activite['calories_brulees_heure'])) {
                            $nom .= ' (' . (int) $activite['calories_brulees_heure'] . ' kcal/h)';
                        }
                        $pdf->Cell(10, 6, '•', 0, 0);
                        $pdf->MultiCell(0, 6, $nom);
                    }
                } else {
                    $pdf->Cell(0, 6, 'Aucune activite recommandee.', 0, 1);
                }
            }

            $filename = 'mes_regimes_' . date('Y-m-d') . '.pdf';
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($pdf->Output('S'));
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