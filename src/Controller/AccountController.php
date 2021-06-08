<?php

namespace App\Controller;

use App\Document\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('Default/index.html.twig');
    }
    
    /**
     * @Route("/register", name="register")
     */
    
    public function register(HttpFoundationRequest $request, DocumentManager $dm,
                     UserPasswordEncoderInterface $passwordEncoder,EncoderFactoryInterface $encoderFactory)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {

        $encoder = $encoderFactory->getEncoder(new User());
        $user->setPlainPassword(
            $encoder->encodePassword
                ($user->getPlainPassword(),null
            )
        );

            $user->setRole('ROLE_USER');
            // $user->setRole('ROLE_ADMIN');

            $dm->persist($user);
            $dm->flush();

            return $this->redirectToRoute('newArticle',
            array('username' => $user->getUsername(),
        ));
        }

        return $this->render('Account/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function edit(HttpFoundationRequest $request, $id,
                        UserPasswordEncoderInterface $passwordEncoder ,
                        DocumentManager $dm)
    {
        $user = new User();
        $user = $dm->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user, $user->getPlainPassword()
                )
            );        
            $dm->flush();

            return $this->redirectToRoute('ShowAllUsers');
        }

        return $this->render('Account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/delete/{id}")
     * @Method({"DELETE"})
     */
    public function deleteUser(HttpFoundationRequest $request, $id, DocumentManager $dm)
    {
        $user = $dm->getRepository(User::class)->find($id);
        $dm->remove($user);
        $dm->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/demoUser")
     */
    public function demoUser(DocumentManager $dm)
    {
        $user = new User();
        $user->setUsername('abcd');
        $dm->persist($user);
        $dm->flush();

        return $this->redirectToRoute('demoCreate',
                        array(
                        'userid'=> $user->getId())
                    );
        // return new response ('record creted with id'.$user->getId());
    }

}