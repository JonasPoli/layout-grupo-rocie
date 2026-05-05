<?php

namespace App\Controller\Pub;

use App\Entity\ContactMessage;
use App\Repository\ShowroomRepository;
use App\Repository\RepresentativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contato', name: 'pub_contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $error = null;
        $success = false;

        if ($request->isMethod('POST')) {
            $msg = new ContactMessage();
            $msg->setName($request->request->get('name', ''));
            $msg->setCompany($request->request->get('company'));
            $msg->setEmail($request->request->get('email', ''));
            $msg->setPhone($request->request->get('phone'));
            $msg->setWhatsapp($request->request->get('whatsapp'));
            $msg->setState($request->request->get('state'));
            $msg->setCity($request->request->get('city'));
            $msg->setSubject($request->request->get('subject'));
            $msg->setDepartment($request->request->get('department'));
            $msg->setMessage($request->request->get('message', ''));
            $msg->setFormType('contact');

            if (!$msg->getName() || !$msg->getEmail() || !$msg->getMessage()) {
                $error = 'Por favor, preencha os campos obrigatórios.';
            } else {
                $em->persist($msg);
                $em->flush();
                $success = true;
            }
        }

        return $this->render('pub/contact/index.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    #[Route('/sac', name: 'pub_sac')]
    public function sac(Request $request, EntityManagerInterface $em): Response
    {
        $error = null;
        $success = false;

        if ($request->isMethod('POST')) {
            $msg = new ContactMessage();
            $msg->setName($request->request->get('name', ''));
            $msg->setEmail($request->request->get('email', ''));
            $msg->setPhone($request->request->get('phone'));
            $msg->setSubject($request->request->get('subject'));
            $msg->setMessage($request->request->get('message', ''));
            $msg->setFormType('sac');

            if (!$msg->getName() || !$msg->getEmail() || !$msg->getMessage()) {
                $error = 'Por favor, preencha os campos obrigatórios.';
            } else {
                $em->persist($msg);
                $em->flush();
                $success = true;
            }
        }

        return $this->render('pub/contact/sac.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    #[Route('/torne-se-um-representante', name: 'pub_representative')]
    public function representative(Request $request, EntityManagerInterface $em): Response
    {
        $error = null;
        $success = false;

        if ($request->isMethod('POST')) {
            $msg = new ContactMessage();
            $msg->setName($request->request->get('name', ''));
            $msg->setCompany($request->request->get('company'));
            $msg->setDocument($request->request->get('cnpj'));
            $msg->setEmail($request->request->get('email', ''));
            $msg->setPhone($request->request->get('phone'));
            $msg->setWhatsapp($request->request->get('whatsapp'));
            $msg->setState($request->request->get('state'));
            $msg->setCity($request->request->get('city'));
            $msg->setMessage($request->request->get('message', 'Interesse em ser representante'));
            $msg->setSubject('Torne-se um representante');
            $msg->setFormType('representative');

            if (!$msg->getName() || !$msg->getEmail()) {
                $error = 'Por favor, preencha os campos obrigatórios.';
            } else {
                $em->persist($msg);
                $em->flush();
                $success = true;
            }
        }

        return $this->render('pub/contact/representative.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    #[Route('/showroom', name: 'pub_showroom')]
    public function showroom(ShowroomRepository $showroomRepo): Response
    {
        $showrooms = $showroomRepo->findBy(['active' => true], ['sortOrder' => 'ASC']);

        return $this->render('pub/showroom/index.html.twig', [
            'showrooms' => $showrooms,
        ]);
    }
}
