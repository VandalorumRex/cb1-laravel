<?php
declare(strict_types=1);
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Http\Controllers\Api;

use App\Models\Response\HttpCode;
use Illuminate\Routing\Controller;
/**
 * Description of OffersController
 *
 * @author Mansur
 */
class OffersController extends Controller
{
    private string $path;
    
    public function __construct()
    {
        //parent::__construct();
        $this->path = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml/offers.xml';
    }


    public function index()
    {
        $superResponse = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        if (file_exists($this->path)) {
            $xmlString = (string)file_get_contents($this->path);
            //$xml = Xml::build($xmlString);
            $dom = new \DomDocument;
            $dom->loadXML($xmlString);
            $superResponse = [];
            foreach ($xml as $offer) {
                $response = ['internalId' => (string)$offer[0]->attributes()->{'internal-id'}[0]];
                foreach ($offer[0] as $field => $value) {
                    $isObject = count($value[0]) > 1;
                    // camel-case => camelCase
                    $feld = Inflector::variable($field, '-');
                    $response[$feld] =  $isObject ? $value[0] : (string)$value[0];
                    if (!$isObject) {
                        $response[$feld] =  (string)$value[0];
                    } else {
                        $response[$feld] = [];
                        foreach ($value[0] as $subField => $subValue) {
                            $response[$feld][Inflector::variable($subField, '-')] = (string)$subValue[0];
                        }
                    }
                }
                array_push($superResponse, $response);
            }
        }
        //$this->json($superResponse);
        return response()->json($superResponse);
    }
}
