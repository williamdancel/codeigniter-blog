<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\AuthService;

class Services extends BaseService
{
    
    public static function authService(bool $getShared = true): AuthService
    {
          if ($getShared) {
              return static::getSharedInstance('authService');
          }
     
         return new AuthService();
    }
     
}
