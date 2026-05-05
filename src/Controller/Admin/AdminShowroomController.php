<?php
namespace App\Controller\Admin;

use App\Entity\Showroom;
use App\Repository\ShowroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/showroom')]
class AdminShowroomController extends AbstractController
{
    use UploadTrait;

    #[Route('', name: 'admin_showroom_index')]
    public function index(ShowroomRepository $repo): Response
    {
        return $this->render('admin/showroom/index.html.twig', ['showrooms' => $repo->findBy([], ['sortOrder' => 'ASC'])]);
    }

    #[Route('/novo', name: 'admin_showroom_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $s = new Showroom();
            $this->hydrate($s, $request);
            if ($file = $request->files->get('mainImageFile')) {
                if ($n = $this->handleUpload($file, $slugger, 'showroom')) { $s->setMainImage($n); }
            }
            $em->persist($s); $em->flush();
            $this->addFlash('success', 'Showroom criado!');
            return $this->redirectToRoute('admin_showroom_index');
        }
        return $this->render('admin/showroom/form.html.twig', ['showroom' => null]);
    }

    #[Route('/{id}/editar', name: 'admin_showroom_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, ShowroomRepository $repo, SluggerInterface $slugger): Response
    {
        $s = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($request->isMethod('POST')) {
            $this->hydrate($s, $request);
            if ($file = $request->files->get('mainImageFile')) {
                if ($n = $this->handleUpload($file, $slugger, 'showroom', $s->getMainImage())) { $s->setMainImage($n); }
            }
            $em->flush();
            $this->addFlash('success', 'Showroom atualizado!');
            return $this->redirectToRoute('admin_showroom_index');
        }
        return $this->render('admin/showroom/form.html.twig', ['showroom' => $s]);
    }

    #[Route('/{id}/excluir', name: 'admin_showroom_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em, ShowroomRepository $repo): Response
    {
        $s = $repo->find($id) ?? throw $this->createNotFoundException();
        $em->remove($s); $em->flush();
        $this->addFlash('success', 'Showroom excluído.');
        return $this->redirectToRoute('admin_showroom_index');
    }

    private function hydrate(Showroom $s, Request $req): void
    {
        $s->setName($req->request->get('name',''))->setCity($req->request->get('city',''))->setState($req->request->get('state',''))
          ->setNeighborhood($req->request->get('neighborhood'))->setAddress($req->request->get('address'))
          ->setNumber($req->request->get('number'))->setZipcode($req->request->get('zipcode'))
          ->setPhone($req->request->get('phone'))->setWhatsapp($req->request->get('whatsapp'))
          ->setEmail($req->request->get('email'))->setOpeningHours($req->request->get('openingHours'))
          ->setGoogleMapsUrl($req->request->get('googleMapsUrl'))
          ->setActive($req->request->get('active') === '1')->setSortOrder((int)$req->request->get('sortOrder',0));
    }
}
