<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\ArticleRepository;
use Performance\Domain\RankingRepository;

class ReadArticle
{
    /**
     * @var ArticleRepository
     */
	private $articleRepository;

    /**
     * @var RankingRepository
     */
    private $rankingRepository;
    
    public function __construct(
        ArticleRepository $articleRepository,
        RankingRepository $rankingRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->rankingRepository = $rankingRepository;
    }

    public function execute($article_id) {
        $article = $this->articleRepository->findOneById($article_id);
        $this->rankingRepository->increaseViewsByOne($article_id, $article->getAuthor()->getId());
        return $article;
    }
}