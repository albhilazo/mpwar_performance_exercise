<?php

namespace Performance\Infrastructure;

use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;
use Predis\Client;

class ArticleRepositoryCached implements ArticleRepository
{

    const ARTICLE_KEY_BY_ARTICLE_ID = "article_id:";
    const ALL_ARTICLES = "all_articles";
    const TTL = 300;

    private $predis;
    private $articleRepository;

    public function __construct(Client $predis, ArticleRepository $articleRepository)
    {
        $this->predis = $predis;
        $this->articleRepository = $articleRepository;
    }

    public function save(Article $article)
    {
        $this->articleRepository->save($article);

        // delete existent cache
        $articleKey =  self::ARTICLE_KEY_BY_ARTICLE_ID. $article->getId();
        $allArticlesKey = self::ALL_ARTICLES;
        $keysToRemove = [ $articleKey, $allArticlesKey ];

        foreach ($keysToRemove as $key){
            if($this->predis->exists($key)) {
                $this->predis->del($key);
            }
        }

        // cache new result
        $ttl = self::TTL;
        $serializedArticle = serialize($article);
        $this->predis->set($articleKey, $serializedArticle);
        $this->predis->expire($articleKey, $ttl);
    }

    /**
     * @param $article_id
     * @return null|Article
     */
    public function findOneById($article_id)
    {
        $key = self::ARTICLE_KEY_BY_ARTICLE_ID . $article_id;
        if($this->predis->exists($key)){
            $article = unserialize($this->predis->get($key));
        }
        else{
            $article = $this->articleRepository->findOneById($article_id);
            $ttl = self::TTL;
            $this->predis->set($key, serialize($article));
            $this->predis->expire($key, $ttl);
        }

        return $article;
    }

    public function findAll()
    {
        $key = self::ALL_ARTICLES;
        if($this->predis->exists($key) && !(is_null($this->predis->get($key)))){
            $allArticles = unserialize($this->predis->get($key));
        }
        else{
            $allArticles = $this->articleRepository->findAll();
            $ttl = self::TTL;
            $this->predis->set($key, serialize($allArticles));
            $this->predis->expire($key, $ttl);
        }
        return $allArticles;
    }
}

