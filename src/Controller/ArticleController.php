<?php

namespace App\Controller;

use App\Document\User;
use App\Document\Article;
use App\Document\Comment;
use App\Form\Type\ArticleType;
use App\Form\Type\CommentType;
use App\Service\FileUploader;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{

    /**
     * @Route("/article/new/{username}", name="newArticle")
     */
    public function createArticle(HttpFoundationRequest $request, $username ,DocumentManager $dm, FileUploader $fileUploader)
    {
        // $userA = new User();

        $user = $dm->getRepository(User::class)->findOneBy(['username' => $username]);
        if(!$user){
            throw $this->createNotFoundException(
                'No User found for this Username : '.$user
            );
        }
        $article = new Article();

        // $article->setUser($userA);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
         if($form->isSubmitted() && $form->isValid()) 
         {
            $img = $form->get('image')->getData();
            if($img == !null)
            {
                $imageU = $fileUploader->uploading($img);
                $article->setImage($imageU);
            }
            $dm->persist($article);
            $dm->flush(); 
             return $this->redirectToRoute('ShowOne',
                        array('id' => $article->getId(),
                        'username'=> $user->getUserName()),
                    );
         }
        return $this->render('Article/article.html.twig',[
            'form' => $form->createView(),
            'username' => $user->getUserName(),
        ]);
    }

    /**
     * @Route("/article/edit/{username}/{id}", name="edit_article")
     */
    public function edit(HttpFoundationRequest $request, $username ,$id, DocumentManager $dm, FileUploader $fileUploader)
    {
        $article = new Article();
        $user = $dm->getRepository(User::class)->findOneBy(['username' => $username]);

        $article = $dm->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('image')->getData();
            if($img == !null)
            {
                $imageU = $fileUploader->uploading($img);
                $article->setImage($imageU);
            }
        $dm->flush(); 

        // return $this->redirectToRoute('ShowOne',
        //         array('id' => $article->getId(), 
        //             'username' => $user->getUsername()));
        return $this->redirectToRoute('timeline',
                   array('username' => $user->getUsername()));
                    
        }
        return $this->render('Article/edit.html.twig', array(
          'form' => $form->createView()  
        ));
    }

    /**
     * @Route("/article/show/{username}/{id}", name = "ShowOne")
     */
    public function showArticle($id,$username,DocumentManager $dm)
    {
        $article = $dm->getRepository(Article::class)->find($id);
        $articles = $dm->getRepository(Article::class)->findAll();

        $articlesA = $dm->getRepository(Article::class)
                ->findBy(
                        array(),
                        array('id' => 'ASC'),
                        2,
                        0
                         );
        $articlesD = $dm->getRepository(Article::class)
                ->findBy(
                        array(),
                        array('id' => 'DESC'),
                        2,
                        0
                        );

        $user = $dm->getRepository(User::class)->findOneBy(['username'=> $username]);
       
        if(!$article) {
            throw $this->createNotFoundException(
                'No Article found for this Id '.$article
            );
        }
        return $this->render('Article/ShowOne.html.twig', array(
            'article' => $article,
            'user' => $user,
            'articles' => $articles,
            'articlesA' => $articlesA,
            'articlesD' => $articlesD,
        ));
    }

     /**
     * @Route("/article/showA/{id}/{username}", name = "ShowArticle")
     */
    public function show($id, $username ,DocumentManager $dm, HttpFoundationRequest $request)
    {
        $article = $dm->getRepository(Article::class)->find($id);
        if(!$article) {
            throw $this->createNotFoundException(
                'No Article found for this Id '.$article
            );
        }
        $user = $dm->getRepository(User::class)->findOneBy(['username' => $username]);


        // // ------------------//
        // $comment = new Comment();
        // $form = $this->createForm(CommentType::class, $comment);
        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()) 
        // {
        //     $data = $form->get('commentData')->getData();
        //     $comment->setCommentData($data);
        //     $dm->persist($comment);
        //     $dm->flush(); 
        // }
       
        //-------------------//
        return $this->render('Article/show-Article.html.twig', array(
            'article' => $article,
            'user' => $user,
            'username' => $user->getUsername(),
            // 'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public function deleteArticle(HttpFoundationRequest $request, $id, DocumentManager $dm)
    {
        $article = $dm->getRepository(Article::class)->find($id);
        $dm->remove($article);
        $dm->flush();
        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/timeline/{username}", name="timeline")
     */
    public function timeline(SluggerInterface $slug, $username ,DocumentManager $dm)
    {
        $username = $dm->getRepository(User::class)->findOneBy(['username' => $username]);
        if(!$username){
            throw $this->createNotFoundException(
                'No User found for this Username : '.$username
            );
        }
        $article = $dm->getRepository(Article::class)->findAll();
        $articlesD = $dm->getRepository(Article::class)
        ->findBy(
                array(),
                array('id' => 'DESC'),
                0
                );
        $user = $dm->getRepository(User::class)->findAll();
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
           return $this->render('Article/timeline.html.twig'
        ,[
            'articles' => $article,
            'articlesD' => $articlesD,
            'users' => $user,
            'username' => $username->getUsername(),
            'email' => $username->getEmail(),
        ]
    );
    }
// -----------------------------//
     /**
     * @Route("/demoCreate", name="demoCreate")
     */
    public function demoCreate(DocumentManager $dm)
    {
       $id="60bdd2a2351d00000e007b27";
        $user = $dm->find(User::class, $id);

        if(!$user){
            throw $this->createNotFoundException(
                'No User found for this Username : '.$user
            );
        }
        $article = new Article();
        $article->setAuthor('UV');
        $article->setUser($user);

        $dm->persist($article);
// dd($article);
        $dm->flush(); 

        return new Response("User record creaded with id".$article->getId());

        // $form = $this->createForm(ArticleType::class, $article);
        // $form->handleRequest($request);
        
        //  if($form->isSubmitted() && $form->isValid()) 
        //  {
        //     $img = $form->get('image')->getData();
        //     if($img == !null)
        //     {
        //         $imageU = $fileUploader->uploading($img);
        //         $article->setImage($imageU);
        //     }
        //     $dm->persist($article);
        //     $dm->flush(); 
        //      return $this->redirectToRoute('ShowOne',
        //                 array('id' => $article->getId(),
        //                 'userid'=> $user->getId()),
        //             );
        //  }
        // return $this->render('Article/article.html.twig',[
        //     'form' => $form->createView(),
        //     'username' => $user->getUserName(),
        // ]);
    }

}
