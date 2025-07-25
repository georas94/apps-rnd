<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class TrackingController extends AbstractController
{
    #[Route('/api/track', name: 'api_track')]
    public function track(Request $request, HubInterface $hub): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vehicleId = $data['vehicleId'] ?? 'default';
        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;

        if (!$lat || !$lng) {
            return new JsonResponse(['error' => 'Missing coordinates'], 400);
        }

        $update = new Update(
            topics: "/vehicles/$vehicleId",
            data: json_encode(['lat' => $lat, 'lng' => $lng])
        );

        dump($hub->publish($update));

        return new JsonResponse(['status' => 'OK']);
    }

    #[Route('/driver', name: 'driver')]
    public function driver(): Response
    {
        return $this->render('public/driver.html.twig');
    }

    #[Route('/map', name: 'map')]
    public function map(): Response
    {
        return $this->render('public/map.html.twig');
    }
}
