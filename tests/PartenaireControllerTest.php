<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartenaireControllerTest extends WebTestCase
{
    
    public function testAjoutOk()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'guisszo',
            'PHP_AUTH_PW' => 'pass',

        ]);
        $crawler = $client->request('POST', '/api/regpart',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{"raison_sociale":"SARL","ninea": 2122658,"numcompte": 22586485,"solde": 26700000}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    // public function testAjoutko()
    // {
    //     $client = static::createClient([],[
    //         'PHP_AUTH_USER' => 'guisszo',
    //         'PHP_AUTH_PW' => 'pass',

    //     ]);
    //     $crawler = $client->request('POST', '/api/regpart',[],[],
    //     ['CONTENT_TYPE'=>"application/json"],
    //     '{
    //         "raison_sociale":"SARL","ninea": "null","numcompte": "22586485","solde": "26700000"
    //     }');
    //     $rep=$client->getResponse();
    //     var_dump($rep);
    //     $this->assertSame(500,$client->getResponse()->getStatusCode());
    // }



    public function testIndex()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'guisszo',
            'PHP_AUTH_PW' => 'pass',

        ]);
        $crawler = $client->request('GET', '/api/listerpart',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "raison_sociale":"SARL",
            "ninea": 2122658,
            "numcompte": 22586485,"solde": 26700000}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(200,$client->getResponse()->getStatusCode());
    }

     public function testDepotok()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'guisszo',
            'PHP_AUTH_PW' => 'pass',

        ]);
        $crawler = $client->request('POST', '/api/depot',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "numcompte":"22586485",
            "solde": "26700000"
        }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    public function testDepotko()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'guisszo',
            'PHP_AUTH_PW' => 'pass',

        ]);
        $crawler = $client->request('POST', '/api/depot',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "numcompte":"22586485",
            "solde": "null"
        }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(500,$client->getResponse()->getStatusCode());
    }
}
