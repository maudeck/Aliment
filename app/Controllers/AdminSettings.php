<?php

namespace App\Controllers;

class AdminSettings extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Gestion des Paramètres'];
        return view('admin/settings', $data);
    }
}
