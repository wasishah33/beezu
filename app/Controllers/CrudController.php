<?php

namespace App\Controllers;

use Core\Controller;

class CrudController extends Controller
{
    /**
     * Home page
     */
    public function index(): void
    {
        $this->crud->generate([]);
        echo "CRUD generation completed.";
    }
}
