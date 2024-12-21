<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\BaseServiceInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface as BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class BaseService  implements BaseServiceInterface
{
    public function __construct() {}
    public function currentLanguage()
    {
        return 1;
    }
}
