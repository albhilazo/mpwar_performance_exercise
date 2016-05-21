<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\GetRanking;
use Symfony\Component\HttpFoundation\Response;
use Performance\Domain\UseCase\ListArticles;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var ListArticles
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var GetRanking
     */
    private $getRanking;

    public function __construct(
        \Twig_Environment $templating,
        ListArticles $useCase,
        SessionInterface $session,
        GetRanking $getRanking
    ) {
        $this->template   = $templating;
        $this->useCase    = $useCase;
        $this->session    = $session;
        $this->getRanking = $getRanking;
    }

    public function get()
    {
        $ranking = $this->getRanking->execute($this->session->get('author_id', null));
        $articles = $this->useCase->execute();
        
        return new Response($this->template->render('home.twig', [
            'globalRanking' => $ranking->getGlobalRanking(),
            'userRanking' => $ranking->getUserRanking(),
            'articles' => $articles
        ]));
    }
}