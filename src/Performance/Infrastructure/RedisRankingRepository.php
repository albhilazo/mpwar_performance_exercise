<?php

namespace Performance\Infrastructure;


use Performance\Domain\RankingRepository;
use Predis\Client;

class RedisRankingRepository implements RankingRepository
{
    const GLOBAL_RANKING_KEY = "articles:ranking:global";
    const INCREMENT_BY_ONE = 1;
    const FIRST_POSITION = 0;
    const LAST_POSITION = 4;

    private $predis;

    public function __construct(Client $predis)
    {
        $this->predis = $predis;
    }

    /**
     * @param $article_id
     * @param $user_id
     */
    public function increaseViewsByOne($article_id, $user_id)
    {
        $userKey = "articles:ranking:$user_id";
        $this->predis->zincrby(
            self::GLOBAL_RANKING_KEY, 
            self::INCREMENT_BY_ONE, 
            $article_id
        );
        $this->predis->zincrby(
            $userKey, 
            self::INCREMENT_BY_ONE, 
            $article_id
        );
    }

    /**
     * @return array|null
     */
    public function getGlobalTop5()
    {
        return $this->predis->zrevrange(
            self::GLOBAL_RANKING_KEY, 
            self::FIRST_POSITION,
            self::LAST_POSITION
        );
    }

    /**
     * @param $user_id
     * @return array|null
     */
    public function getUserTop5($user_id)
    {
        $userKey = "articles:ranking:$user_id";
        return $this->predis->zrevrange(
            $userKey,
            self::FIRST_POSITION,
            self::LAST_POSITION
        );
    }
}

