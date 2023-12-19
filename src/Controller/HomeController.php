<?php

namespace App\Controller;

use App\Model\ProductManager;
use App\Service\SessionManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class HomeController extends AbstractController
{
    protected $session;
    protected $productManager;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->productManager = new ProductManager();
    }

    /**
     * Display home page
     */
    public function index(): string
    {
        $products = $this->productManager->selectAllWithCategory();
        return $this->twig->render('Home/index.html.twig', [
            'session' => $this->session,
            'products' => $products,
        ]);
    }

    public function scrappy(): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://www.radio-active.net/la-bande-des-comics_rss.php');
        $rssContent = $response->getContent();

        $crawler = new Crawler($rssContent);

        $crawler->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $crawler->registerNamespace('itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
        $crawler->registerNamespace('googleplay', 'http://www.google.com/schemas/play-podcasts/1.0');

        $items = $crawler->filterXPath('//atom:entry')->each(function (Crawler $node) {
            $title = $node->filterXPath('itunes:title')->text();
            $link = $node->filterXPath('link')->attr('href');

            return [
                'title' => $title,
                'link' => $link,

            ];
        });
        var_dump($items);
        return $this->twig->render('Home/index.html.twig', [
            'items' => $items,
        ]);
    }
}
