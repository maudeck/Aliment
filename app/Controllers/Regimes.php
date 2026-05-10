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

            $pdf = $this->createPdfBase(
                'NutriLife - Mes Regimes Achetes',
                'Document genere le ' . date('d/m/Y a H:i')
            );

            foreach ($rows as $index => $regimeData) {
                if ($index > 0) {
                    $pdf->Ln(1);
                    $pdf->SetDrawColor(210, 210, 210);
                    $pdf->Line(12, $pdf->GetY(), 198, $pdf->GetY());
                    $pdf->Ln(4);
                }

                $activites = $this->activiteModel->getByRegime((int) $regimeData['regime_id']);
                $this->renderRegimeCard($pdf, $regimeData, $activites, $index + 1);
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
        $normalizedRegime = [
            'regime_nom'            => $regimeData['nom'] ?? '',
            'regime_description'    => $regimeData['description'] ?? '',
            'variation_poids'       => $regimeData['variation_poids'] ?? 0,
            'duree_nom'             => $regimeData['duree_nom'] ?? '',
            'nombre_jours'          => $regimeData['nombre_jours'] ?? 0,
            'prix_paye'             => $regimeData['prix_paye'] ?? 0,
            'date_achat'            => $regimeData['created_at'] ?? null,
            'pourcentage_viande'    => $regimeData['pourcentage_viande'] ?? 0,
            'pourcentage_poisson'   => $regimeData['pourcentage_poisson'] ?? 0,
            'pourcentage_volaille'  => $regimeData['pourcentage_volaille'] ?? 0,
        ];

        $pdf = $this->createPdfBase(
            'NutriLife - Regime Detaille',
            'Document genere le ' . date('d/m/Y a H:i')
        );
        $this->renderRegimeCard($pdf, $normalizedRegime, $activites, 1);

        $filename = 'regime_' . $regimeData['nom'] . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
    }

    private function createPdfBase(string $title, string $subtitle = ''): FPDF
    {
        $pdf = new FPDF();
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetFillColor(30, 64, 103);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 11, $this->pdfText($title), 0, 1, 'C', true);

        if ($subtitle !== '') {
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 8, $this->pdfText($subtitle), 0, 1, 'C', true);
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(3);

        return $pdf;
    }

    private function renderRegimeCard(FPDF $pdf, array $regimeData, array $activites, int $position): void
    {
        if ($pdf->GetY() > 235) {
            $pdf->AddPage();
        }

        $pdf->SetFont('Arial', 'B', 13);
        $title = sprintf('Regime %d: %s', $position, $regimeData['regime_nom'] ?? 'Sans nom');
        $pdf->MultiCell(0, 8, $this->pdfText($title));

        $pdf->SetFont('Arial', '', 10);
        if (!empty($regimeData['regime_description'])) {
            $pdf->MultiCell(0, 5, $this->pdfText('Description: ' . $regimeData['regime_description']));
            $pdf->Ln(1);
        }

        $variation = (float) ($regimeData['variation_poids'] ?? 0);
        $this->labelValue($pdf, 'Variation poids', ($variation > 0 ? '+' : '') . $variation . ' kg');

        $dureeNom = (string) ($regimeData['duree_nom'] ?? '-');
        $nbJours  = (int) ($regimeData['nombre_jours'] ?? 0);
        $this->labelValue($pdf, 'Duree', $dureeNom . ' (' . $nbJours . ' jours)');

        $prix = number_format((float) ($regimeData['prix_paye'] ?? 0), 0, ',', ' ') . ' Ar';
        $this->labelValue($pdf, 'Prix paye', $prix);

        $dateAchat = $regimeData['date_achat'] ?? null;
        if (!empty($dateAchat)) {
            $this->labelValue($pdf, 'Achete le', date('d/m/Y a H:i', strtotime((string) $dateAchat)));
        }

        $pdf->Ln(2);
        $this->renderNutritionTable($pdf, $regimeData);
        $pdf->Ln(2);
        $this->renderActivities($pdf, $activites);
    }

    private function labelValue(FPDF $pdf, string $label, string $value): void
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(35, 6, $this->pdfText($label . ':'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 6, $this->pdfText($value));
    }

    private function renderNutritionTable(FPDF $pdf, array $regimeData): void
    {
        $this->sectionTitle($pdf, 'Repartition nutritionnelle');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(232, 239, 247);
        $pdf->Cell(58, 7, $this->pdfText('Viande'), 1, 0, 'C', true);
        $pdf->Cell(58, 7, $this->pdfText('Poisson'), 1, 0, 'C', true);
        $pdf->Cell(58, 7, $this->pdfText('Volaille'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(58, 7, (string) ($regimeData['pourcentage_viande'] ?? 0) . '%', 1, 0, 'C');
        $pdf->Cell(58, 7, (string) ($regimeData['pourcentage_poisson'] ?? 0) . '%', 1, 0, 'C');
        $pdf->Cell(58, 7, (string) ($regimeData['pourcentage_volaille'] ?? 0) . '%', 1, 1, 'C');
    }

    private function renderActivities(FPDF $pdf, array $activites): void
    {
        $this->sectionTitle($pdf, 'Activites recommandees');
        $pdf->SetFont('Arial', '', 10);

        if (empty($activites)) {
            $pdf->MultiCell(0, 6, $this->pdfText('Aucune activite recommandee.'));
            return;
        }

        foreach ($activites as $activite) {
            $nom = trim((string) ($activite['nom'] ?? ''));
            if ($nom === '') {
                continue;
            }

            if (!empty($activite['calories_brulees_heure'])) {
                $nom .= ' (' . (int) $activite['calories_brulees_heure'] . ' kcal/h)';
            }

            $pdf->MultiCell(0, 6, $this->pdfText('- ' . $nom));
        }
    }

    private function sectionTitle(FPDF $pdf, string $title): void
    {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(240, 245, 251);
        $pdf->SetDrawColor(210, 220, 232);
        $pdf->Cell(0, 8, $this->pdfText($title), 1, 1, 'L', true);
        $pdf->Ln(1);
    }

    private function pdfText(string $text): string
    {
        $converted = @iconv('UTF-8', 'windows-1252//TRANSLIT', $text);

        if ($converted === false) {
            return utf8_decode($text);
        }

        return $converted;
    }
}