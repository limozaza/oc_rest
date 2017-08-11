<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Representation\Articles;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleController extends FOSRestController
{

    /**
     * @Rest\View(
     *     statusCode=200
     * )
     * @Rest\Get(
     *     path="/articles",
     *     name="app_articles_list"
     * )
     *
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="the keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default=2,
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset."
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository("AppBundle:Article")->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        $articles = new Articles($pager);
        //return $pager->getCurrentPageResults();
        return $articles;
    }
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/articles/{id}",
     *     name="app_articles_show",
     *     requirements={"id"="\d+"}
     * )
     */
    public function showAction(Article $article)
    {
        return $article;
    }


    /**
     * @Rest\View(
     *  statusCode=201
     * )
     * @Rest\Post(
     *     path="/articles",
     *     name="app_articles_create"
     * )
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function createAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_articles_show',['id' => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }
}
