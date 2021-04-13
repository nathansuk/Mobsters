<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentType;
use App\Services\NewsService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    /**
     * @param int $id
     * @param NewsService $newsService
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @Route("/news-{id}", name="show_news")
     */
    public function show(int $id, NewsService $newsService, Request $request, UserService $userService): Response {

        $news = $newsService->getNewsById($id);
        $newsList = $newsService->getAllNews();

        if($news == null){
            $this->addFlash('error', "Cet article n'existe pas !");
            return $this->redirectToRoute('news');
        }
        $comments = $news->getComments();

        $comment = new Comments();
        $comment_form = $this->createForm(CommentType::class, $comment);
        $comment_form->handleRequest($request);

        if($comment_form->isSubmitted() && !$comment_form->isValid()){
            $this->addFlash('error', 'Oops, il y a une erreur lors de la publication de votre commentaire.');
            return $this->redirectToRoute('show_news', ['id' => $id]);
        }

        if($comment_form->isSubmitted() && $comment_form->isValid()){
            if($this->getUser()){
                $user = $userService->getUserByUsername($this->getUser()->getUsername());
            } else {
                $this->addFlash('error', 'Il y a eu une erreur');
                return $this->redirectToRoute('show_news', ['id' => $id]);
            }

            $newsService->postComment($comment, $news, $user);
            $this->addFlash('success', 'Votre commentaire a été publié');
            return $this->redirectToRoute('show_news', ['id' => $id]);
        }

        return $this->render('news/show.html.twig', [
           'news' => $news,
            'comments' => $comments,
            'comment_form' => $comment_form->createView(),
            'other_news' => $newsList
        ]);

    }

    /**
     * @param NewsService $newsService
     * @return Response
     * @Route("/news", name="news")
     */
    public function index(NewsService $newsService): Response
    {
        $newsList = $newsService->getAllNews();

        return $this->render('news/index.html.twig', [
            'controller_name' => 'Le times',
            'newsList' => $newsList
        ]);
    }


}
