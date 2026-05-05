<?php

namespace App\Controller\Admin;

use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mensagem')]
class AdminMessageController extends AbstractController
{
    #[Route('', name: 'admin_message_index')]
    public function index(ContactMessageRepository $repo, Request $request): Response
    {
        $status = $request->query->get('status');
        $criteria = $status ? ['status' => $status] : [];
        return $this->render('admin/message/index.html.twig', [
            'messages' => $repo->findBy($criteria, ['createdAt' => 'DESC'], 100),
            'current_status' => $status,
        ]);
    }

    #[Route('/{id}', name: 'admin_message_show')]
    public function show(int $id, ContactMessageRepository $repo): Response
    {
        $msg = $repo->find($id) ?? throw $this->createNotFoundException();
        return $this->render('admin/message/show.html.twig', ['message' => $msg]);
    }

    #[Route('/{id}/status/{status}', name: 'admin_message_status', methods: ['POST'])]
    public function changeStatus(int $id, string $status, ContactMessageRepository $repo, EntityManagerInterface $em): Response
    {
        $msg = $repo->find($id) ?? throw $this->createNotFoundException();
        $allowed = ['nova', 'em_atendimento', 'respondida', 'arquivada'];
        if (in_array($status, $allowed)) { $msg->setStatus($status); $em->flush(); }
        return $this->redirectToRoute('admin_message_show', ['id' => $id]);
    }
}
