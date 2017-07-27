<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleController extends FOSRestController
{
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
