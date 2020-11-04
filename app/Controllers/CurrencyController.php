<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Models\Currency;
use Sabre\Xml\Service;
use Doctrine\DBAL\DriverManager;

class CurrencyController
{
    private array $currencies;

    public function index()
    {
        $currencyQuery = query()
            ->select('*')
            ->from('Currency_input')
            ->orderBy('Country', 'asc')
            ->execute()
            ->fetchAllAssociative();

        $currencies = [];

        foreach ($currencyQuery as $currency) {
            $currencies[] = new Currency(
                (string)$currency['Country'],
                (float)$currency['Rate']
            );
        }

        return require_once __DIR__ . '/../Views/CurrencyView.php';
    }


    public function add()
    {

        $xml = file_get_contents('https://www.bank.lv/vk/ecb.xml');
        $service = new Service();

        $service->elementMap = [
            'Currency' => 'Sabre\Xml\Deserializer\keyValue'
        ];

        $items = $service->parse($xml);
        $Country = [];
        $Rates = [];
        foreach ($items[1]['value'] as $item) {

            $shown = query()
                ->select('*')
                ->from('Currency_input')
                ->where('Country = :Country')
                ->setParameter('Country', $item['value'][0]['value'])
                ->setParameter('Rate', $item['value'][1]['value'])
                ->orderBy('Country', 'desc')
                ->execute()
                ->fetchAllAssociative();

            if (!empty($shown)) {
                query()
                    ->update('Currency_input')
                    ->set('Country', ':Country')
                    ->set('Rate', ':Rate')
                    ->setParameter('Country', $item['value'][0]['value'])
                    ->setParameter('Rate', $item['value'][1]['value'])
                    ->where('Country = :Country')
                    ->execute();
            } else {
                query()
                    ->insert('Currency_input')
                    ->values([
                        'Country' => ':Country',
                        'Rate' => ':Rate'
                    ])
                    ->setParameter('Country', $item['value'][0]['value'])
                    ->setParameter('Rate', $item['value'][1]['value'])
                    ->execute();
            }

//            array_push($Country, $item['value'][0]['value']);
            //echo($item['value'][0]['value']);
//            array_push($Rates, $item['value'][1]['value']);
//
         //   echo ($item['value'][1]['value']) . '<br/>';
        }

        header('Location: /');
    }


}
