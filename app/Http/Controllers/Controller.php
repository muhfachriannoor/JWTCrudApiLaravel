<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function viewTestLogic()
    {
        for($i = 1; $i <= 30; $i++) {
            if($i % 14 == 0 && $i % 4 == 0) {
                echo 'Unictive Media' . '<br>';
            } elseif($i % 4 == 0) {
                echo 'Unictive' . '<br>';
            } else {
                echo $i . '<br>';
            }
        }
    }
}
