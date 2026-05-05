<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categoria')]
class AdminCategoryController extends AbstractController
{
    use UploadTrait;
    #[Route('', name: 'admin_category_index')]
    public function index(CategoryRepository $repo): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $repo->findBy([], ['sortOrder' => 'ASC']),
        ]);
    }

    #[Route('/nova', name: 'admin_category_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $c = new Category();
            $name = $request->request->get('name', '');
            $slug = $request->request->get('slug') ?: strtolower($slugger->slug($name));

            $c->setName($name)
              ->setSlug($slug)
              ->setShortDescription($request->request->get('shortDescription'))
              ->setActive($request->request->get('active') === '1')
              ->setShowOnHome($request->request->get('showOnHome') === '1')
              ->setSortOrder((int)$request->request->get('sortOrder', 0));

            // Upload de imagem
            $file = $request->files->get('imageFile');
            if ($file) {
                $filename = $this->handleUpload($file, $slugger, 'category');
                if ($filename) {
                    $c->setImage($filename);
                }
            }

            $em->persist($c);
            $em->flush();
            $this->addFlash('success', 'Categoria criada!');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/form.html.twig', ['category' => null]);
    }

    #[Route('/{id}/editar', name: 'admin_category_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, CategoryRepository $repo, SluggerInterface $slugger): Response
    {
        $c = $repo->find($id) ?? throw $this->createNotFoundException();

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name', '');
            $slug = $request->request->get('slug') ?: $c->getSlug();

            $c->setName($name)
              ->setSlug($slug)
              ->setShortDescription($request->request->get('shortDescription'))
              ->setActive($request->request->get('active') === '1')
              ->setShowOnHome($request->request->get('showOnHome') === '1')
              ->setSortOrder((int)$request->request->get('sortOrder', 0));

            // Upload de imagem
            $file = $request->files->get('imageFile');
            if ($file) {
                // Remove imagem antiga
                if ($c->getImage()) {
                    $old = $this->getParameter('kernel.project_dir') . '/public/uploads/category/' . $c->getImage();
                    if (file_exists($old)) {
                        unlink($old);
                    }
                }
                $filename = $this->handleUpload($file, $slugger, 'category', $c->getImage());
                if ($filename) {
                    $c->setImage($filename);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Categoria atualizada!');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/form.html.twig', ['category' => $c]);
    }

    #[Route('/{id}/excluir', name: 'admin_category_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, CategoryRepository $repo): Response
    {
        $c = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($c->getImage()) {
            $path = $this->getParameter('kernel.project_dir') . '/public/uploads/category/' . $c->getImage();
            if (file_exists($path)) unlink($path);
        }
        $em->remove($c);
        $em->flush();
        $this->addFlash('success', 'Categoria excluída.');
        return $this->redirectToRoute('admin_category_index');
    }
}
