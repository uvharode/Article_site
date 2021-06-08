<?php

namespace App\Controller;

use App\Document\User;
use App\Document\Article;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="DisplayAll")
     */
    public function showAllArticle(DocumentManager $dm)
    {
        $article = $dm->getRepository(Article::class)->findAll();
        $user = $dm->getRepository(User::class)->findAll();
        // $userA = $dm->getRepository(User::class)->findBy($userA->id);
        if(!$article){
            throw $this->createNotFoundException(
                'No Article posted yet'
            );
        }
        if(!$user){
            throw $this->createNotFoundException(
                'No User yet'
            );
        }
           return $this->render('Admin/AdminDisplay.html.twig'
        ,[
            'articles' => $article,
            'users' => $user,
        ]
    );
    }

    /**
     * @Route("/admin/showAll", name="ShowAllUsers")
     */
    public function showAllUsers(DocumentManager $dm)
    {
        $user = $dm->getRepository(User::class)->findAll();
       
        if(!$user){
            throw $this->createNotFoundException(
                'No User Register yet'
            );
        }

        return $this->render('Account/showAllUsers.html.twig'
        ,[
            'users' => $user,
        ]
    );
    }

    /**
     * @Route("/admin/delete/{id}")
     * @Method({"DELETE"})
     */
    public function deleteUser(Request $request, $id, DocumentManager $dm)
    {
        $user = $dm->getRepository(User::class)->find($id);
        $dm->remove($user);
        $dm->flush();

        $response = new Response();
        $response->send();
    }
}