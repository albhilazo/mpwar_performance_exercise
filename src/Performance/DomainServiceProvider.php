<?php

namespace Performance;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DomainServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['useCases.signUp'] = function () use ($app) {
            return new \Performance\Domain\UseCase\SignUp($app['orm.em']->getRepository('Performance\Domain\Author'));
        };

        $app['useCases.login'] = function () use ($app) {
            return new \Performance\Domain\UseCase\Login($app['author.repository.cached'], $app['session']);
        };

        $app['useCases.writeArticle'] = function () use ($app) {
            return new \Performance\Domain\UseCase\WriteArticle($app['article.repository.cached'], $app['author.repository.cached'], $app['session']);
        };

        $app['useCases.editArticle'] = function () use ($app) {
            return new \Performance\Domain\UseCase\EditArticle($app['article.repository.cached'], $app['author.repository.cached'], $app['session']);
        };

        $app['useCases.readArticle'] = function () use ($app) {
            return new \Performance\Domain\UseCase\ReadArticle($app['article.repository.cached'], $app['ranking.repository']);
        };

        $app['useCases.listArticles'] = function () use ($app) {
            return new \Performance\Domain\UseCase\ListArticles($app['article.repository.cached']);
        };
        
        $app['useCases.getRanking'] = function () use ($app) {
            return new \Performance\Domain\UseCase\GetRanking($app['ranking.repository'], $app['article.repository.cached']);
        };

        $app['controllers.readArticle'] = function () use ($app) {
            return new \Performance\Controller\ArticleController($app['twig'], $app['useCases.readArticle']);
        };

        $app['controllers.writeArticle'] = function () use ($app) {
            return new \Performance\Controller\WriteArticleController($app['twig'], $app['url_generator'], $app['useCases.writeArticle'], $app['session']);
        };

        $app['controllers.editArticle'] = function () use ($app) {
            return new \Performance\Controller\EditArticleController($app['twig'], $app['url_generator'], $app['useCases.editArticle'], $app['useCases.readArticle'], $app['session']);
        };

        $app['controllers.login'] = function () use ($app) {
            return new \Performance\Controller\LoginController($app['twig'], $app['url_generator'], $app['useCases.login'], $app['session']);
        };

        $app['controllers.signUp'] = function () use ($app) {
            return new \Performance\Controller\RegisterController($app['twig'], $app['url_generator'], $app['useCases.signUp']);
        };

        $app['controllers.home'] = function () use ($app) {
            return new \Performance\Controller\HomeController($app['twig'], $app['useCases.listArticles'], $app['session'], $app['useCases.getRanking']);
        };
        
        $app['predis.client'] = function () use ($app) {
            return new \Predis\Client([
                'scheme' => $app['redis.options']['scheme'],
                'host'   => $app['redis.options']['host'],
                'port'   => $app['redis.options']['port']
            ]);
        };
        
        $app['session.storage.handler'] = function () use ($app) {
            return new \Predis\Session\Handler($app['predis.client']);
        };

        $app['ranking.repository'] = function () use ($app) {
            return new \Performance\Infrastructure\RedisRankingRepository($app['predis.client']);
        };

        $app['author.repository.cached'] = function () use ($app) {
            return new \Performance\Infrastructure\AuthorRepositoryCached($app['predis.client'],$app['orm.em']->getRepository('Performance\Domain\Author'));
        };
        
        $app['article.repository.cached'] = function () use ($app) {
            return new \Performance\Infrastructure\ArticleRepositoryCached($app['predis.client'],$app['orm.em']->getRepository('Performance\Domain\Article'));
        };
    }
}