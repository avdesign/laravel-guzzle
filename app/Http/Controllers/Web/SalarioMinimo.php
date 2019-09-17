<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
class SalarioMinimo extends Controller
{

    public function index()
    {
        $client = new \GuzzleHttp\Client;
        $response = $client->get("http://www.guiatrabalhista.com.br/guia/salario_minimo.htm");
        $html = $response->getBody()->getContents();
        $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
        $rows = $crawler->filterXPath('//table')->eq(0)
            ->filter('tr')
            ->each(function ($tr, $i) {
                return $tr->filter('td')->each(function ($td, $i) {
                    return trim($td->text());
                });
            });

        unset($rows[0]);

        return $this->getContent($rows);
    }

    /**
     * Get array
     *
     * @param $rows
     * @return array
     */
    private function getContent($rows)
    {
        for ($i = 1; $i <= count($rows); $i++) {
            $arr[$i]['vigencia']     = $rows[$i][0];
            $arr[$i]['valor_mensal'] = $rows[$i][1];
        }

        return collect($arr)->values();

    }

}
