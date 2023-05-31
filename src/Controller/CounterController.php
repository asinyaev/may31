<?php

namespace App\Controller;

use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CounterController extends AbstractController
{
    private const HASH_MAP_NAME = 'countries';
    private const INCR_BY = 1;

    public function __construct(private readonly Client $client)
    {
    }

    #[Route('/update/{code}', name: 'app_update', requirements: ['code' => '^[a-z]{2}$'])]
    public function update(string $code): Response
    {
        $this->client->hincrby(self::HASH_MAP_NAME, $code, self::INCR_BY);

        return new Response('OK');
    }

    #[Route('/stats', name: 'app_stats')]
    public function stats(): JsonResponse
    {
        return $this->json($this->client->hgetall(self::HASH_MAP_NAME));
    }
}
