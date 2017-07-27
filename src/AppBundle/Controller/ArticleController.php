<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get(
     *     path="/articles/{id}",
     *     name="app_articles_show",
     *     requirements={"id"="\d+"}
     * )
     */
    public function showAction(/*Article $article*/)
    {
        $article = new Article();
        $article->setTitle('Titre article');
        $article->setContent('Le contenude larticle');
        /*$em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();*/

        //dump($article);exit();
        return $article;
    }
}
