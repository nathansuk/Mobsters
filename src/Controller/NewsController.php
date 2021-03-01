<?php

namespace App\Controller;

use App\Services\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(int $id, NewsService $newsService): Response {

        $news = $newsService->getNewsById($id);

        return $this->render('news/show.html.twig', [
           'news' => $news
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
