<?php

namespace App\Controllers;

class AdminActivites extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Gestion des Activités Sportives'];
        return view('admin/activites', $data);
    }
}
