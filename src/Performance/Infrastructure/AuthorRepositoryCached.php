<?php

namespace Performance\Infrastructure;

use Performance\Domain\Author;
use Performance\Domain\AuthorRepository;
use Predis\Client;

class AuthorRepositoryCached implements AuthorRepository
{
    const AUTHOR_KEY_BY_AUTHOR_ID = "author_id:";
    const AUTHOR_KEY_BY_USERNAME= "author_username:";
    const TTL = 300;

    private $predis;
    private $authorRepository;

    public function __construct(Client $predis, AuthorRepository $authorRepository)
    {
        $this->predis = $predis;
        $this->authorRepository = $authorRepository;
    }

    public function save(Author $author)
    {
        $this->authorRepository->save($author);

        $ttl = self::TTL;
        $serializedAuthor = serialize($author);

        $key = self::AUTHOR_KEY_BY_AUTHOR_ID. $author->getId();
        $this->predis->set($key, $serializedAuthor);
        $this->predis->expire($key, $ttl);

        $key = self::AUTHOR_KEY_BY_USERNAME. $author->getUsername();
        $this->predis->set($key, $serializedAuthor);
        $this->predis->expire($key, $ttl);

     }

    /**
     * @param $author_id
     * @return null|Author
     */
    public function findOneById($author_id)
    {
        $key = self::AUTHOR_KEY_BY_AUTHOR_ID . $author_id;
        if($this->predis->exists($key)){
          $author = unserialize($this->predis->get($key));
        }
        else{
            $author = $this->authorRepository->findOneById($author_id);
            $ttl = self::TTL;
            $this->predis->set($key, serialize($author));
            $this->predis->expire($key, $ttl);
        }

        return $author;
    }

    /**
     * @param $username
     * @return null|Author
     */
    public function findOneByUsername($username)
    {
        $key = self::AUTHOR_KEY_BY_USERNAME. $username;
        if($this->predis->exists($key)){
            $author = unserialize($this->predis->get($key));
        }
        else{
            $author = $this->authorRepository->findOneByUsername($username);
            $ttl = self::TTL;
            $this->predis->set($key, serialize($author));
            $this->predis->expire($key, $ttl);
        }

        return $author;
    }
}

