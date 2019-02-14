<?php

namespace App\Http\Controllers;

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

    

    public function searchCars($vehicle, $conservation = "", $brand = "", $model = "", $city = "", $value1 = "", $value2 = "", $year1 = "", $year2 = "", $user = "todos")
    {

        $final = 1;
        $x = 1;


        $url = env("URL_SEMINOVOS")."resultadobusca/index/veiculo/".$vehicle;
        $url .= "/estado-conservacao/".$conservation;
        $url .= "/marca/".$brand;
        $url .= "/modelo/".$model;
        $url .= "/cidade/".$city;
        $url .= "/valor1/".$value1;
        $url .= "/valor2/".$value2;
        $url .= "/ano1/".$year1;
        $url .= "/ano2/".$year2;
        $url .= "/usuario/".$user;
        $url .= "/pagina/1";

        $doc = new \DOMDocument('1.0', 'UTF-8');
        @$doc->loadHTML(file_get_contents($url));
       
        $pTags = $doc->getElementsByTagName("p");
        foreach ($pTags as $pTag) {
            if($pTag->getAttribute("class") == "paginacao-resumo")
            {
                $final = $pTag->getElementsByTagName("strong")->item(1)->nodeValue;
            }
        }

        for ($i = 1; $i <= $final; $i++) {
            
            $url = env("URL_SEMINOVOS")."resultadobusca/index/veiculo/".$vehicle;
            $url .= "/estado-conservacao/".$conservation;
            $url .= "/marca/".$brand;
            $url .= "/modelo/".$model;
            $url .= "/cidade/".$city;
            $url .= "/valor1/".$value1;
            $url .= "/valor2/".$value2;
            $url .= "/ano1/".$year1;
            $url .= "/ano2/".$year2;
            $url .= "/usuario/".$user;
            $url .= "/pagina/".$i;

            dump($url);
    
            $doc = new \DOMDocument('1.0', 'UTF-8');
            @$doc->loadHTML(file_get_contents($url));
            $casrslist = $doc->getElementsByTagName("dl");
            
            foreach ($casrslist as $car)
            {
                if($car->getAttribute("class") != "")
                {
                    $titleAndValue = $car->getElementsByTagName("dd")->item(0)->getElementsByTagName("h4")->item(0)->nodeValue;
                    $titleAndValue = explode(" R$ ", $titleAndValue);
                    dump($titleAndValue);
                    echo $x++;
                }
            }
        }
    }

    public function searchOneCar($cod)
    {
        dd($cod);
    }
}
