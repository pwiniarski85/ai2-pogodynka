<?php
namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeasurementRepository;

class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(string $country, string $city, LocationRepository $locationRepository, WeatherUtil $util): Response

    {
        $location =  $locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,


        ]);
    }

}