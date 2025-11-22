<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
/**
 * Description of OffersController
 *
 * @author Mansur
 */
class OffersController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'ok']);
    }
}
