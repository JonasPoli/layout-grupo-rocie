<?php

namespace App\Controller\Pub;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/produtos', name: 'pub_products')]
    public function index(
        Request $request,
        ProductRepository $productRepo,
        CategoryRepository $categoryRepo,
        BrandRepository $brandRepo
    ): Response {
        $categorySlug = $request->query->get('categoria');
        $brandSlug = $request->query->get('marca');
        $search = $request->query->get('busca');
        $featured = $request->query->get('destaque');

        $filters = ['active' => true];

        $categories = $categoryRepo->findBy(['active' => true], ['sortOrder' => 'ASC']);
        $brands = $brandRepo->findBy(['active' => true], ['name' => 'ASC']);

        $products = $productRepo->findFiltered($categorySlug, $brandSlug, $search, $featured);

        return $this->render('pub/product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'current_category' => $categorySlug,
            'current_brand' => $brandSlug,
            'search' => $search,
        ]);
    }

    #[Route('/produto/{slug}', name: 'pub_product_show')]
    public function show(string $slug, ProductRepository $productRepo): Response
    {
        $product = $productRepo->findOneBy(['slug' => $slug, 'active' => true]);

        if (!$product) {
            throw $this->createNotFoundException('Produto não encontrado.');
        }

        $related = $productRepo->findRelated($product, 4);

        return $this->render('pub/product/show.html.twig', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
