<?php

namespace App\Controller\Admin;

use App\Entity\Representative;
use App\Repository\RepresentativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/representante')]
class AdminRepresentativeController extends AbstractController
{
    #[Route('', name: 'admin_representative_index')]
    public function index(RepresentativeRepository $repo): Response
    {
        return $this->render('admin/representative/index.html.twig', ['representatives' => $repo->findBy([], ['state' => 'ASC'])]);
    }
    #[Route('/novo', name: 'admin_representative_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) { $r = new Representative(); $this->hydrate($r,$request); $em->persist($r); $em->flush(); $this->addFlash('success','Representante criado!'); return $this->redirectToRoute('admin_representative_index'); }
        return $this->render('admin/representative/form.html.twig', ['representative' => null]);
    }
    #[Route('/{id}/editar', name: 'admin_representative_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, RepresentativeRepository $repo): Response
    {
        $r = $repo->find($id) ?? throw $this->createNotFoundException();
        if ($request->isMethod('POST')) { $this->hydrate($r,$request); $em->flush(); $this->addFlash('success','Representante atualizado!'); return $this->redirectToRoute('admin_representative_index'); }
        return $this->render('admin/representative/form.html.twig', ['representative' => $r]);
    }
    private function hydrate(Representative $r, Request $req): void
    {
        $r->setName($req->request->get('name',''))->setState($req->request->get('state',''))
          ->setCity($req->request->get('city'))->setCompany($req->request->get('company'))
          ->setPhone($req->request->get('phone'))->setWhatsapp($req->request->get('whatsapp'))
          ->setEmail($req->request->get('email'))->setRegion($req->request->get('region'))
          ->setNotes($req->request->get('notes'))->setActive($req->request->get('active')==='1');
    }
}
