<?php

namespace App\Modules\Blog\Controllers;

use App\Controllers\BaseController;

class BlogController extends BaseController
{
    public function index()
    {
        $data = [
		    'title' => 'Blog',
            'view' => 'App\Modules\Blog\Views\index'
        ];

		return view('template/layout', $data);
    }
}