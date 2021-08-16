<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class CasesController extends AbstractController
{
    /**
     * @Route("/", name="cases")
     */
     public function index(ChartBuilderInterface $chartBuilder): Response {

        $country = 'South Africa';
        $coronaVirusCases = self::cases_by_country($country);
        $labelsDataArray = [];
        $coronaDataArray = [];
        $maxCases = 3000000;

        foreach($coronaVirusCases as $key => $value) {

            $time = strtotime($value['Date']);
            $labelsDataArray[] = date('Y-m-d', $time);

            $coronaDataArray['Confirmed']['data'][] = $value['Confirmed'];
            $coronaDataArray['Confirmed']['color'] = 'blue';
            $coronaDataArray['Confirmed']['backgroundColor'] = 'rgba(0, 0, 255, 0.1)';

            $coronaDataArray['Deaths']['data'][] = $value['Deaths'];
            $coronaDataArray['Deaths']['color'] = 'red';
            $coronaDataArray['Deaths']['backgroundColor'] = 'rgba(255, 0, 0, 0.4)';

            $coronaDataArray['Recovered']['data'][] = $value['Recovered'];
            $coronaDataArray['Recovered']['color'] = 'green';
            $coronaDataArray['Recovered']['backgroundColor'] = 'rgba(0, 255, 0, 0.2)';

            $coronaDataArray['Active']['data'][] = $value['Active'];
            $coronaDataArray['Active']['color'] = 'yellow';
            $coronaDataArray['Active']['backgroundColor'] = 'rgba(240, 255, 0, 0.3)';
           
        }

        $casesDataArray = [];

        foreach($coronaDataArray as $caseType => $caseData) {

            $tempArray = [];
            $tempArray['label'] = $caseType;
            $tempArray['backgroundColor'] = $caseData['backgroundColor'];
            $tempArray['borderColor'] = $caseData['color'];
            $tempArray['data'] = $caseData['data'];
            $casesDataArray[] = $tempArray;
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labelsDataArray,
            'datasets' => $casesDataArray
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => $maxCases]],
                ],
            ],

            'title'=> [
                'display' => true,
                'text' => 'Corona Virus Line Chart'
              ]
        ]);


        return $this->render('cases/index.html.twig', [
            'chart' => $chart,
            'country' => $country
        ]);
    }

    private function cases_by_country($country) {

        $countrySlug = str_replace(' ', '-', strtolower($country)); 
        $apiEndpoint = 'https://api.covid19api.com/total/country/'.$countrySlug;

        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $apiEndpoint);

            $statusCode = $response->getStatusCode();
            $responseArray = $response->toArray();
        } catch (Exception $e) {
            print "ERROR!!!!! : " . $e->getMessage() . $e->getTraceAsString() . "\r\n";
        }

        return $responseArray;

    }
}
