<?php

namespace App\Controllers;

class AdminRegimes extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Gestion des Régimes'];
        return view('admin/regimes', $data);
    }
}
