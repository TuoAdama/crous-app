<?php

namespace App\Services;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;
use Symfony\Contracts\Cache\CacheInterface;

class IdToolService
{
    public function __construct(
        private readonly CacheInterface $cache, private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function crawlIdTool(): ?int
    {
        $idTool = null;
        $client = Client::createSeleniumClient('http://chrome:4444/wd/hub', DesiredCapabilities::chrome());
        $crawler = $client
            ->request('GET', 'https://trouverunlogement.lescrous.fr');
        $button = $crawler->selectButton("Lancer une recherche");
        sleep(5);
        $button->click();
        sleep(6);
        $currentUrl = $client->getCurrentURL();

        if (preg_match('#/tools/(\d+)/#', $currentUrl, $matches)) {
            $idTool = $matches[1];
        }
        $client->takeScreenshot("screenshot/screenshot.png");
        $client->quit();

        return $idTool;
    }

    /**
     * @throws Exception
     */
    private function getIdToolFromCache(): int
    {
        try {
            return  $this->cache->get('id_tool', function ($item) {
                $id = $this->crawlIdTool();
                $item->expiresAfter(3600 * 24);
                return $id;
            });
        } catch (InvalidArgumentException $e) {
            throw new Exception('Cache error: ' . $e->getMessage());
        }
    }


    public function getIdTool(): ?int
    {
        try {
            return $this->getIdToolFromCache();
        } catch (Exception $e) {
            $this->logger->error('Error retrieving ID tool from cache', [
                'exception' => $e,
            ]);
        }
        return null;
    }


    /**
     * @throws Exception
     */
    public function updateIdTool(): void
    {
        $this->getIdToolFromCache();
    }
}
