<?php

namespace App\Controllers;

class AdminCodes extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Validation des Codes Portefeuille'];
        return view('admin/codes', $data);
    }
}
