<?php

declare(strict_types=1);

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Http\Controllers\Api;

use App\Lib\Utils;
use App\Models\Response\HttpCode;
use Illuminate\Http\Request;
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

    public function destroy(string $guid)
    {
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $dom = new \DomDocument();
            $dom->loadXML($xmlString);

            // Найдем элемент который необходимо удалить
            $xpath = new \DOMXpath($dom);
            $nodelist = $xpath->query("/offers/offer[@internal-id='" . $guid . "']");
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер не найден'];
            $oldnode = $nodelist->item(0);
            if ($oldnode) {
                // Удаляем элемент
                $oldnode->parentNode->removeChild($oldnode);
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $savedXml = $dom->saveXML();
                $xmlPretty = str_replace("  \n", '', $savedXml);
                file_put_contents($this->path, $xmlPretty);
                $response = ['message' => 'Оффер успешно удалён'];
            }
        }
        return response()->json($response);
    }

    public function index()
    {
        $superResponse = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        if (file_exists($this->path)) {
            $xmlString = (string)file_get_contents($this->path);
            //$xml = Xml::build($xmlString);
            //$dom = new \DomDocument;
            //$dom->loadXML($xmlString);
            $xml = new \SimpleXMLElement($xmlString);
            $superResponse = [];
            foreach ($xml as $offer) {
                $response = ['internalId' => (string)$offer[0]->attributes()->{'internal-id'}[0]];
                foreach ($offer[0] as $field => $value) {
                    $isObject = count($value[0]) > 1;
                    // camel-case => camelCase
                    $feld = $field;//Inflector::variable($field, '-');
                    $response[$feld] =  $isObject ? $value[0] : (string)$value[0];
                    if (!$isObject) {
                        $response[$feld] =  (string)$value[0];
                    } else {
                        $response[$feld] = [];
                        foreach ($value[0] as $subField => $subValue) {
                            //$response[$feld][Inflector::variable($subField, '-')] = (string)$subValue[0];
                            $response[$feld][$subField] = (string)$subValue[0];
                        }
                    }
                }
                array_push($superResponse, $response);
            }
        }
        //$this->json($superResponse);
        return response()->json($superResponse);
    }

    public function show(string $guid)
    {
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            //$xml = Xml::build($xmlString);
            $xml = new \SimpleXMLElement($xmlString);
            //$xml->loadXML($xmlString);
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер на найден'];
            $offer = $xml->xpath("//offer[@internal-id='" . $guid . "']");
            if ($offer) {
                $response = ['internalId' => $guid];
                foreach ($offer[0] as $field => $value) {
                    $isObject = count($value[0]) > 1;
                    // camel-case => camelCase
                    $feld = $field;//Inflector::variable($field, '-');
                    $response[$feld] =  $isObject ? $value[0] : (string)$value[0];
                    if (!$isObject) {
                        $response[$feld] =  (string)$value[0];
                    } else {
                        $response[$feld] = [];
                        foreach ($value[0] as $subField => $subValue) {
                            //$response[$feld][Inflector::variable($subField, '-')] = (string)$subValue[0];
                            $response[$feld][$subField] = (string)$subValue[0];
                        }
                    }
                }
                //print_r($response);
            }
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {
        /** @var array<string, string|array<string, string>> $offer */
        $offer = $request->all();//$this->request->getData();
        //print_r($offer);
        //return response()->json($offer);
        if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml')) {
            mkdir(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml');
        }
        if (!file_exists($this->path)) {
            $xmlString = '<?xml version="1.0" encoding="UTF-8"?><offers></offers>';
        } else {
            $xmlString = (string)file_get_contents($this->path);
        }
        $offers = new \SimpleXMLElement($xmlString);
        $child = $offers->addChild('offer');
        foreach ($offer as $field => $item) {
            if (is_array($item)) {
                $onyq = $child->addChild($field);
                //print_r($item);
                foreach ($item as $subField => $subItem) {
                    // Превращаем camelCase в camel-case
                    //$onyq->addChild(Inflector::dasherize($subField), $subItem);
                    //print_r($subItem);
                    $onyq->addChild($subField, $subItem);
                }
            } else {
                if ($field === 'creationDate' && !$item) {
                    $item = date('c');
                }
                // Превращаем camelCase в camel-case согласно
                // https://yandex.ru/support/realty/ru/requirements/requirements-sale-housing#in_common
                //$child->addChild(Inflector::dasherize($field), $item);
                $child->addChild($field, $item);
            }
        }
        $child->addAttribute('internal-id', Utils::GUIDv4());

        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML((string)$offers->asXML());
        $xmlPretty = $dom->saveXML();
        file_put_contents($this->path, $xmlPretty);
        return response()->json(['code' => HttpCode::CREATED, 'message' => 'Принято']);
    }

    public function update(Request $request, string $guid)
    {
        $offer = $request->all();
        //print_r($offer);
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $dom = new \DomDocument();
            $dom->loadXML($xmlString);

            // Найдем элемент который необходимо изменить
            $xpath = new \DOMXpath($dom);
            $nodelist = $xpath->query("/offers/offer[@internal-id='" . $guid . "']");
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер не найден'];
            $foundNode = $nodelist->item(0);
            if ($foundNode) {
                foreach ($offer as $field => $value) {
                    //$node = $foundNode->getElementsByTagName(Inflector::dasherize($field));
                    $node = $foundNode->getElementsByTagName($field);
                    if ($node->item(0) && $field !== 'creationDate') {
                        if (is_string($value)) {
                            $node->item(0)->nodeValue = $value;
                        } else {
                            foreach ($value as $subField => $subValue) {
                                //$subNode = $node->item(0)->getElementsByTagName(Inflector::dasherize($subField));
                                $subNode = $node->item(0)->getElementsByTagName($subField);
                                if ($subNode->item(0)) {
                                    $subNode->item(0)->nodeValue = $subValue;
                                }
                            }
                        }
                    }
                }
                $savedXml = $dom->saveXML();
                file_put_contents($this->path, $savedXml);
                $response = ['message' => 'Сохранено'];
            }
        }
        return response()->json($response);
    }
}
