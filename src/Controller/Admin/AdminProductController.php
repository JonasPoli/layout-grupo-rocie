<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produto')]
class AdminProductController extends AbstractController
{
    #[Route('', name: 'admin_product_index')]
    public function index(ProductRepository $repo, Request $request): Response
    {
        $search = $request->query->get('q');
        $products = $search
            ? $repo->findFiltered(null, null, $search, null)
            : $repo->findBy([], ['createdAt' => 'DESC'], 100);
        return $this->render('admin/product/index.html.twig', ['products' => $products, 'search' => $search]);
    }

    #[Route('/novo', name: 'admin_product_new')]
    public function new(Request $request, EntityManagerInterface $em, CategoryRepository $catRepo, BrandRepository $brandRepo, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $p = new Product();
            $this->hydrateProduct($p, $request, $slugger);
            $p->setMainCategory($catRepo->find($request->request->get('mainCategory')));
            $p->setBrand($brandRepo->find($request->request->get('brand')));
            $em->persist($p); $em->flush();
            $this->addFlash('success', 'Produto criado com sucesso!');
            return $this->redirectToRoute('admin_product_index');
        }
        return $this->render('admin/product/form.html.twig', [
            'product' => null, 'categories' => $catRepo->findBy(['active' => true], ['name' => 'ASC']),
            'brands' => $brandRepo->findBy(['active' => true], ['name' => 'ASC']),
        ]);
    }

    #[Route('/{id}/editar', name: 'admin_product_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, CategoryRepository $catRepo, BrandRepository $brandRepo, ProductRepository $repo, SluggerInterface $slugger): Response
    {
        $p = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($request->isMethod('POST')) {
            $this->hydrateProduct($p, $request, $slugger);
            $p->setMainCategory($catRepo->find($request->request->get('mainCategory')));
            $p->setBrand($brandRepo->find($request->request->get('brand')));
            $em->flush();
            $this->addFlash('success', 'Produto atualizado!');
            return $this->redirectToRoute('admin_product_index');
        }
        return $this->render('admin/product/form.html.twig', [
            'product' => $p, 'categories' => $catRepo->findBy(['active' => true], ['name' => 'ASC']),
            'brands' => $brandRepo->findBy(['active' => true], ['name' => 'ASC']),
        ]);
    }

    #[Route('/{id}/excluir', name: 'admin_product_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, ProductRepository $repo): Response
    {
        $p = $repo->find($id) ?? throw $this->createNotFoundException();
        $em->remove($p); $em->flush();
        $this->addFlash('success', 'Produto excluído.');
        return $this->redirectToRoute('admin_product_index');
    }

    private function hydrateProduct(Product $p, Request $req, SluggerInterface $slugger): void
    {
        $name = $req->request->get('name', '');
        $p->setName($name)
          ->setSlug($p->getSlug() ?: strtolower($slugger->slug($name)))
          ->setSubtitle($req->request->get('subtitle'))
          ->setInternalCode($req->request->get('internalCode'))
          ->setSku($req->request->get('sku'))
          ->setShortDescription($req->request->get('shortDescription'))
          ->setFullDescription($req->request->get('fullDescription'))
          ->setAboutItems($req->request->get('aboutItems'))
          ->setMaterial($req->request->get('material'))
          ->setColor($req->request->get('color'))
          ->setWeight($req->request->get('weight'))
          ->setDimensions($req->request->get('dimensions'))
          ->setCapacity($req->request->get('capacity'))
          ->setPackaging($req->request->get('packaging'))
          ->setOrigin($req->request->get('origin'))
          ->setWarranty($req->request->get('warranty'))
          ->setRatingAverage($req->request->get('ratingAverage') ?: null)
          ->setRatingCount($req->request->get('ratingCount') ? (int)$req->request->get('ratingCount') : null)
          ->setActive($req->request->get('active') === '1')
          ->setIsFeatured($req->request->get('isFeatured') === '1')
          ->setIsNew($req->request->get('isNew') === '1')
          ->setSeoTitle($req->request->get('seoTitle'))
          ->setSeoDescription($req->request->get('seoDescription'))
          ->setSortOrder((int) $req->request->get('sortOrder', 0));
    }
}
