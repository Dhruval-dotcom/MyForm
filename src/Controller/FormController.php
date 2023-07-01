<?php

namespace App\Controller;

use App\Entity\Formdata;
use App\Entity\User;
use App\Form\MyFormType;
use App\Form\RegistrationType;
use App\Repository\FormdataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Model\UserRegistrationFormModel;

class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(EntityManagerInterface $em, FormdataRepository $repo, Request $request): Response
    {
        $form = $this->createForm(MyFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formdata = $form->getData();
            $em->persist($formdata);
            $em->flush();
            return $this->redirectToRoute('alldata', [
                'message' => 'Form data created successfully!!!'
            ]);
        }
        return $this->render('Form/form.html.twig', [
            'articleform' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_form')]
    public function edit(Formdata $formData, EntityManagerInterface $em, FormdataRepository $repo, Request $request): Response
    {
        $form = $this->createForm(MyFormType::class, $formData,[
            'include_published_at' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formData);
            $em->flush();
            return $this->redirectToRoute('alldata', [
                'message' => 'Form data Updated successfully!!!'
            ]);
        }
        return $this->render('Form/edit.html.twig', [
            'articleform' => $form->createView(),
        ]);
    }

    #[Route('/user/{name}/{email}', name: 'app_form2')]
    public function AddUser(EntityManagerInterface $em, string $name, string $email): Response
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $em->persist($user);
        $em->flush();
        return new Response(
            'User Created with id = ' . $user->getId()
        );
    }

    #[Route('/form/location-select', name: 'locationSelect')]
    public function getSpecificLocationSelect(Request $request){
        $data = new Formdata();
        $data->setLocation($request->query->get('location'));
        $form = $this->createForm(MyFormType::class,$data);

        if(!$form->has('specificLocationName')){
            return new Response(null, 204);
        }

        return $this->render('Form/_specific_location_name.html.twig', [
            'articleform' => $form->createView()
        ]);
    }
    #[Route('/alldata/{message}', name: 'alldata')]
    public function AllData(EntityManagerInterface $em, string $message = null): Response
    {
        $message == null ?: $this->addFlash('success', $message);
        return $this->render('Form/alldata.html.twig', [
            'article' => $em->getRepository(Formdata::class)->findAll()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function Delete(EntityManagerInterface $em, Formdata $user): Response
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('alldata', [
            'message' => 'Form data Deleted successfully!!!'
        ]);
    }

    #[Route('/AllUser', name: 'AllUser')]
    public function AllUser(EntityManagerInterface $em, string $message = null): Response
    {
        return $this->render('Form/allusers.html.twig', [
            'article' => $em->getRepository(User::class)->findAll()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function Login(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserRegistrationFormModel $userModel */
            $user = new User();
            $userModel = $form->getData();
            $user->setEmail($userModel->email);
            $user->setPassword(
                $userModel->plainPassword
            );

            if(true === $userModel->agreeTerms){
                $user->agreeTerms();
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_crud_index');
        }
        return $this->render('Form/login.html.twig', [
            'articleform' => $form->createView(),
        ]);
    }

    #[Route('/logger', name: 'logger')]
    public function LoggerGenerator(LoggerInterface $logger)
    {
        // $logger->info('I just got the logger');
        // $logger->error('An error occurred');
        // $logger->debug('User {userId} has logged in', [
        //     'userId' => 'aaa',
        // ]);
        // $logger->critical('I left the oven on!', [
        //     // include extra "context" info in your logs
        //     'cause' => 'in_hurry',
        // ]);
        return new Response('Log Created');
    }
}