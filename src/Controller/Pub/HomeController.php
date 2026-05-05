<?php

namespace App\Controller\Pub;

use App\Repository\BannerRepository;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ShowroomRepository;
use App\Repository\RepresentativeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'pub_home')]
    public function index(
        BannerRepository $bannerRepo,
        CategoryRepository $categoryRepo,
        BrandRepository $brandRepo,
        ShowroomRepository $showroomRepo,
        RepresentativeRepository $repRepo
    ): Response {
        $banners = $bannerRepo->findBy(['active' => true, 'displayPage' => 'home'], ['sortOrder' => 'ASC'], 5);
        $categories = $categoryRepo->findBy(['active' => true, 'showOnHome' => true], ['sortOrder' => 'ASC'], 8);
        $brands = $brandRepo->findBy(['active' => true, 'showOnHome' => true], ['sortOrder' => 'ASC'], 12);
        $showrooms = $showroomRepo->findBy(['active' => true], ['sortOrder' => 'ASC'], 4);
        $representatives = $repRepo->findBy(['active' => true], ['state' => 'ASC']);

        return $this->render('pub/home/index.html.twig', [
            'banners' => $banners,
            'categories' => $categories,
            'brands' => $brands,
            'showrooms' => $showrooms,
            'representatives' => $representatives,
        ]);
    }

    #[Route('/a-empresa', name: 'pub_about')]
    public function about(): Response
    {
        return $this->render('pub/about/index.html.twig');
    }
}
