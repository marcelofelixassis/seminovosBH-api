<?php

namespace App\Http\Controllers;
use App\Entity\Car as CarEntity;

class CarsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    private function createSearchUrl($params, $page)
    {
        $url = env('URL_SEMINOVOS')."resultadobusca/index";
        $searchParams = explode('/', $params);
        $seminovosParams = explode(',', env('CARS_PARAMS_SEMINOVOS'));

        for ($i=0; $i < count($searchParams); $i++) {
            if($searchParams[$i] != " " && $searchParams[$i] != "" && $searchParams[$i] != null)
                $url .= "/".$seminovosParams[$i]."/".$searchParams[$i];
        }

        $url .= "/pagina/".$page;

        return $url;
    }

    private function createSearchOneUrl($brand, $model, $year, $cod)
    {
        $url = env('URL_SEMINOVOS')."comprar";
        $url .= "/".$brand."/".$model."/".$year."/".$cod;
        
        return $url;
    }

    private function numberOfPages($doc)
    {
        $pTags = $doc->getElementsByTagName("p");
        foreach ($pTags as $pTag) {
            if($pTag->getAttribute("class") == "paginacao-resumo")
            {
                $final = $pTag->getElementsByTagName("strong")->item(1)->nodeValue;
            }
        }

        return $final;
    }

    public function searchCars($params = null)
    {
        $carsArray = array();
        $url = $this->createSearchUrl($params, 1);

        $doc = new \DOMDocument('1.0', 'UTF-8');
        @$doc->loadHTML(file_get_contents($url));

        $numberOfPages = $this->numberOfPages($doc);
       
        for ($i = 1; $i <= $numberOfPages; $i++) {

            if($i != 1)
            {
                $url = $this->createSearchUrl($params, $i);
            }
        
            @$doc->loadHTML(file_get_contents($url));
            $casrslist = $doc->getElementsByTagName("dl");
            
            foreach ($casrslist as $car)
            {
                if($car->getAttribute("class") != "")
                {
                    $titleAndValue = $car->getElementsByTagName("dd")->item(0)->getElementsByTagName("h4")->item(0)->nodeValue;
                    $titleAndValue = explode(" R$ ", $titleAndValue);
                    $km = $car->getElementsByTagName("dd")->item(2)->getElementsByTagName("p")->item(1)->nodeValue;
                    
                    $pattern = '/' . PHP_EOL . '/';
                    if(preg_match($pattern, $km))
                    {
                        $km = explode(PHP_EOL, $km)[1];
                    } else {
                        $km = "";
                    }
                    
                    $year = $car->getElementsByTagName("dd")->item(2)->getElementsByTagName("p")->item(0)->nodeValue;
                    
                    $car = new CarEntity();
                    $car->setModel($titleAndValue[0]);
                    $car->setValue($titleAndValue[1]);
                    $car->setKm($km);
                    $car->setYear($year);

                    array_push($carsArray, $car);
                }
            }
        }

        $carsResponse = array();
        foreach ($carsArray as $car) {
            array_push($carsResponse, $car->toArray());
        }
       
        return response()->json($carsResponse);
    }


    public function searchOneCar($brand, $model, $year, $cod)
    {
        $url = $this->createSearchOneUrl($brand, $model, $year, $cod);
       
        $doc = new \DOMDocument('1.0', 'UTF-8');
        @$doc->loadHTML(file_get_contents($url));
        
        $conteudoAcessorios = $doc->getElementsByTagName('div');
        foreach ($conteudoAcessorios as $div) {
            if($div->getAttribute('id') == "conteudoAcessorios")
            {
                $firstDetails = $div->getElementsByTagName('div')->item(0)
                ->getElementsByTagName('span')->item(0)->getElementsByTagName('ul');

                if($firstDetails->item(0)->getElementsByTagName('li')->length < 3)
                {
                    $year = $firstDetails->item(0)->getElementsByTagName('li')->item(0)->nodeValue;
                    $km = "0 km";
                    $fuel = $firstDetails->item(0)->getElementsByTagName('li')->item(1)->nodeValue;
                    $secondDetails = $div->getElementsByTagName('div')->item(2)->getElementsByTagName('ul');
                    $thirdDetails = $div->getElementsByTagName('div')->item(3)->getElementsByTagName('ul');
                } else {
                    $year = $firstDetails->item(0)->getElementsByTagName('li')->item(0)->nodeValue;
                    $km = $firstDetails->item(0)->getElementsByTagName('li')->item(1)->nodeValue;
                    $fuel = $firstDetails->item(0)->getElementsByTagName('li')->item(2)->nodeValue;
                    $secondDetails = $div->getElementsByTagName('div')->item(1)->getElementsByTagName('ul');
                    $thirdDetails = $div->getElementsByTagName('div')->item(2)->getElementsByTagName('ul');
                }

                $color = $firstDetails->item(1)->getElementsByTagName('li')->item(1)->nodeValue;

                $details = "";
                foreach ($secondDetails as $second) {
                    $details .= $second->nodeValue;
                }
                $detailsArray = explode(PHP_EOL, $details);

                $note = $thirdDetails->item(0)->getElementsByTagName('p')->item(0)->nodeValue;

                $car = new CarEntity();
                $car->setYear($year);
                $car->setKm($km);
                $car->setFuel($fuel);
                $car->setColor($color);
                $car->setDetails($detailsArray);
                $car->setNote($note);

                return response()->json($car->toArray());
            }
        }
    }
}
