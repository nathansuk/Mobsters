<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentType;
use App\Services\NewsService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    /**
     * @param int $id
     * @param NewsService $newsService
     * @return Response
     * @Route("/news-{id}", name="show_news")
     */
    public function show(int $id, NewsService $newsService, Request $request, EntityManagerInterface $entityManager, UserService $userService): Response {

        $news = $newsService->getNewsById($id);
        $comments = $news->getComments();

        $comment = new Comments();
        $comment_form = $this->createForm(CommentType::class, $comment);
        $comment_form->handleRequest($request);

        if($comment_form->isSubmitted() && !$comment_form->isValid()){
            $this->addFlash('error', 'Oops, il y a une erreur lors de la publication de votre commentaire.');
            return $this->redirectToRoute('show_news', ['id' => $id]);
        }

        if($comment_form->isSubmitted() && $comment_form->isValid()){
            $user = $userService->getUserByUsername($this->getUser()->getUsername());
            $comment->setArticle($news)
                ->setUser($user)
                ->setDate(new \DateTime("now"));

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été publié');
            return $this->redirectToRoute('show_news', ['id' => $id]);

        }

        return $this->render('news/show.html.twig', [
           'news' => $news,
            'comments' => $comments,
            'comment_form' => $comment_form->createView()
        ]);

    }

    /**
     * @param NewsService $newsService
     * @return Response
     * @Route("/news", name="news")
     */
    public function index(NewsService $newsService): Response
    {
        $newsList = array_reverse($newsService->getAllNews());

        return $this->render('news/index.html.twig', [
            'controller_name' => 'Le times',
            'newsList' => $newsList
        ]);
    }


}
