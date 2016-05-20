<?php

namespace Performance\Domain;


interface RankingRepository
{
    /**
     * @param $article_id
     * @param $user_id
     */
    public function increaseViewsByOne($article_id, $user_id); 
    
    /**
     * @return array
     */
    public function getGlobalTop5();

    /**
     * @param $user_id
     * @return array
     */
    public function getUserTop5($user_id);
}

