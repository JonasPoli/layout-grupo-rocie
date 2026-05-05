<?php
namespace App\Controller\Admin;

use App\Entity\Banner;
use App\Repository\BannerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/banner')]
class AdminBannerController extends AbstractController
{
    use UploadTrait;

    #[Route('', name: 'admin_banner_index')]
    public function index(BannerRepository $repo): Response
    {
        return $this->render('admin/banner/index.html.twig', ['banners' => $repo->findBy([], ['sortOrder' => 'ASC'])]);
    }

    #[Route('/novo', name: 'admin_banner_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $b = new Banner();
            $this->hydrate($b, $request);
            $this->processImages($b, $request, $slugger);
            $em->persist($b); $em->flush();
            $this->addFlash('success', 'Banner criado!');
            return $this->redirectToRoute('admin_banner_index');
        }
        return $this->render('admin/banner/form.html.twig', ['banner' => null]);
    }

    #[Route('/{id}/editar', name: 'admin_banner_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, BannerRepository $repo, SluggerInterface $slugger): Response
    {
        $b = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($request->isMethod('POST')) {
            $this->hydrate($b, $request);
            $this->processImages($b, $request, $slugger);
            $em->flush();
            $this->addFlash('success', 'Banner atualizado!');
            return $this->redirectToRoute('admin_banner_index');
        }
        return $this->render('admin/banner/form.html.twig', ['banner' => $b]);
    }

    #[Route('/{id}/excluir', name: 'admin_banner_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, BannerRepository $repo): Response
    {
        $b = $repo->find($id) ?? throw $this->createNotFoundException();
        $em->remove($b); $em->flush();
        $this->addFlash('success', 'Banner excluído.');
        return $this->redirectToRoute('admin_banner_index');
    }

    private function hydrate(Banner $b, Request $req): void
    {
        $b->setTitle($req->request->get('title',''))
          ->setSubtitle($req->request->get('subtitle'))
          ->setText($req->request->get('text'))
          ->setButtonText($req->request->get('buttonText'))
          ->setButtonUrl($req->request->get('buttonUrl'))
          ->setSecondaryButtonText($req->request->get('secondaryButtonText'))
          ->setSecondaryButtonUrl($req->request->get('secondaryButtonUrl'))
          ->setDisplayPage($req->request->get('displayPage','home'))
          ->setSortOrder((int)$req->request->get('sortOrder',0))
          ->setActive($req->request->get('active')==='1');
    }

    private function processImages(Banner $b, Request $req, SluggerInterface $slugger): void
    {
        if ($file = $req->files->get('desktopImageFile')) {
            if ($n = $this->handleUpload($file, $slugger, 'banner', $b->getDesktopImage())) {
                $b->setDesktopImage($n);
            }
        }
        if ($file = $req->files->get('mobileImageFile')) {
            if ($n = $this->handleUpload($file, $slugger, 'banner', $b->getMobileImage())) {
                $b->setMobileImage($n);
            }
        }
    }
}
