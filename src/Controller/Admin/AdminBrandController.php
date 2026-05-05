<?php
namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/marca')]
class AdminBrandController extends AbstractController
{
    use UploadTrait;

    #[Route('', name: 'admin_brand_index')]
    public function index(BrandRepository $repo): Response
    {
        return $this->render('admin/brand/index.html.twig', ['brands' => $repo->findBy([], ['name' => 'ASC'])]);
    }

    #[Route('/nova', name: 'admin_brand_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $b = new Brand();
            $name = $request->request->get('name', '');
            $b->setName($name)
              ->setSlug(strtolower($slugger->slug($request->request->get('slug') ?: $name)))
              ->setShortDescription($request->request->get('shortDescription'))
              ->setOfficialWebsite($request->request->get('officialWebsite'))
              ->setActive($request->request->get('active') === '1')
              ->setShowOnHome($request->request->get('showOnHome') === '1')
              ->setSortOrder((int)$request->request->get('sortOrder', 0));

            if ($file = $request->files->get('logoFile')) {
                if ($name = $this->handleUpload($file, $slugger, 'brand')) {
                    $b->setLogo($name);
                }
            }
            $em->persist($b); $em->flush();
            $this->addFlash('success', 'Marca criada!');
            return $this->redirectToRoute('admin_brand_index');
        }
        return $this->render('admin/brand/form.html.twig', ['brand' => null]);
    }

    #[Route('/{id}/editar', name: 'admin_brand_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, BrandRepository $repo, SluggerInterface $slugger): Response
    {
        $b = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($request->isMethod('POST')) {
            $b->setName($request->request->get('name', ''))
              ->setShortDescription($request->request->get('shortDescription'))
              ->setOfficialWebsite($request->request->get('officialWebsite'))
              ->setActive($request->request->get('active') === '1')
              ->setShowOnHome($request->request->get('showOnHome') === '1')
              ->setSortOrder((int)$request->request->get('sortOrder', 0));

            if ($file = $request->files->get('logoFile')) {
                if ($name = $this->handleUpload($file, $slugger, 'brand', $b->getLogo())) {
                    $b->setLogo($name);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Marca atualizada!');
            return $this->redirectToRoute('admin_brand_index');
        }
        return $this->render('admin/brand/form.html.twig', ['brand' => $b]);
    }

    #[Route('/{id}/excluir', name: 'admin_brand_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, BrandRepository $repo): Response
    {
        $b = $repo->find($id) ?? throw $this->createNotFoundException();
        $em->remove($b); $em->flush();
        $this->addFlash('success', 'Marca excluída.');
        return $this->redirectToRoute('admin_brand_index');
    }
}
