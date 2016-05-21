<?php

namespace Performance\Domain;


class Ranking
{
    private $globalRanking = null;
    private $userRanking = null;

    /**
     * @return null|array
     */
    public function getGlobalRanking()
    {
        return $this->globalRanking;
    }

    /**
     * @param array $globalRanking
     */
    public function setGlobalRanking($globalRanking)
    {
        $this->globalRanking = $globalRanking;
    }

    /**
     * @return null|array
     */
    public function getUserRanking()
    {
        return $this->userRanking;
    }

    /**
     * @param array $userRanking
     */
    public function setUserRanking($userRanking)
    {
        $this->userRanking = $userRanking;
    }    
}

