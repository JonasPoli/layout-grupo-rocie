<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use App\Repository\ShowroomRepository;
use App\Repository\RepresentativeRepository;
use App\Repository\BannerRepository;
use App\Repository\ContactMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    #[Route('/', name: 'admin_dashboard_slash')]
    public function index(
        ProductRepository $productRepo,
        CategoryRepository $categoryRepo,
        BrandRepository $brandRepo,
        ShowroomRepository $showroomRepo,
        RepresentativeRepository $repRepo,
        BannerRepository $bannerRepo,
        ContactMessageRepository $messageRepo
    ): Response {
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'products' => $productRepo->count(['active' => true]),
                'categories' => $categoryRepo->count([]),
                'brands' => $brandRepo->count([]),
                'showrooms' => $showroomRepo->count([]),
                'representatives' => $repRepo->count([]),
                'banners' => $bannerRepo->count([]),
                'messages_new' => $messageRepo->count(['status' => 'nova']),
                'messages_total' => $messageRepo->count([]),
            ],
            'latest_messages' => $messageRepo->findBy([], ['createdAt' => 'DESC'], 5),
            'featured_products' => $productRepo->findBy(['isFeatured' => true, 'active' => true], ['sortOrder' => 'ASC'], 5),
        ]);
    }
}
