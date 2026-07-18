<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON(['message' => 'Connected to CI4 API!']);
    }
}
