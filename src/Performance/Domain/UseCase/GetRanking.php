<?php

namespace Performance\Domain\UseCase;


use Performance\Domain\ArticleRepository;
use Performance\Domain\Ranking;
use Performance\Domain\RankingRepository;

class GetRanking
{
    /**
     * @var RankingRepository
     */
    private $rankingRepository;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * GetRanking constructor.
     * @param RankingRepository $rankingRepository
     * @param \Performance\Domain\ArticleRepository $articleRepository
     */
    public function __construct(
        RankingRepository $rankingRepository,
        ArticleRepository $articleRepository
    ) {
        $this->rankingRepository = $rankingRepository;
        $this->articleRepository = $articleRepository;
    }

    public function execute($user_id = null)
    {
        $ranking     = new Ranking();
        $globalTop5  = $this->rankingRepository->getGlobalTop5();
        $global5List = [];
        foreach ($globalTop5 as $top5) {
            $global5List[] = $this->articleRepository->findOneById($top5);
        }
        $ranking->setGlobalRanking($global5List);

        if (is_null($user_id)) {
            return $ranking;
        }

        $userTop5  = $this->rankingRepository->getUserTop5($user_id);
        $user5List = [];
        foreach ($userTop5 as $top5) {
            $user5List[] = $this->articleRepository->findOneById($top5);
        }
        $ranking->setUserRanking($user5List);
        return $ranking;
    }
}

