<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class SecurityController extends AbstractController
{

  /**
 * @Route("/login", name="login")
 */
public function ch()
{
    return $this->render('Security/login.html.twig');
}

/**
 * @Route("/check", name="checkIn")
 */
public function Check(Request $request, DocumentManager $dm, EncoderFactoryInterface $encoder, UserPasswordEncoder $UserPasswordencoder ) 
                        
{
    $email = $request->request->get('emai');
    $username = $request->request->get('username');
    $plainPassword = $request->request->get('plainPassword');

    $user = $dm->getRepository(User::class)->findOneBy(
        ['email' => $email, 'username' => $username]);

    if(!$user){
        throw $this->createNotFoundException(
            'Wrong Username or Password'
        );
        }

    $pw = $user->getPlainPassword();
    $encoder = $encoder->getEncoder(new User());

    if(!$encoder->isPasswordValid($pw, $plainPassword, null))
     {
       throw $this->createNotFoundException('Password incorrect');
     }

     if($user->getRole() == 'ROLE_USER')
     {
        return $this->redirectToRoute('timeline',
        array('username' => $username ));
     }
     else
     {
        return $this->redirectToRoute('DisplayAll');
     }

    return $this->render('Security/login.html.twig');

}

/**
 * @Route("/userDetails/{username}", name="userDetails")
 */
public function UserDetails($username, DocumentManager $dm)
{
    $user = $dm->getRepository(User::class)->findOneBy(['username'=> $username]);

    if (!$username) {
        throw $this->createNotFoundException('username not found' . $username);
    }

    return $this->render('Security/userdetails.html.twig',
            ['username' => $user->getUserName()] );
}

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        $this->session->clear();
    }

}