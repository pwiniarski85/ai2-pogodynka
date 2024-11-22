<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    public function __construct(private WeatherUtil $weatherUtil) {}

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format,
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            return $this->render(sprintf('weather_api/index.%s.twig', $format), [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn($m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        }

        if ($format === 'csv') {
            $csv = "city,country,date,celsius\n";
            $csv .= implode("\n", array_map(fn($m) => sprintf(
                '%s,%s,%s,%s',
                $city,
                $country,
                $m->getDate()->format('Y-m-d'),
                $m->getCelsius(),
                $m->getFahrenheit(),
            ), $measurements));

            return new Response($csv, Response::HTTP_OK);
        }
    }
}
