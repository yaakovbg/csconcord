<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Entity\ArticleWord;


class StatisticsController extends FOSRestController {

    /**
     * @Rest\Get("/wordStatistics")
     */
    public function getWordStatistics() {
        $articleWordrepo = $this->getDoctrine()->getRepository(ArticleWord::class);
        $ret=$articleWordrepo->getWordStatistics();
        return $ret;
    }
    /**
     * @Rest\Get("/letterStatistics")
     */
    public function getLetterStatistics() {
        $articleWordrepo = $this->getDoctrine()->getRepository(ArticleWord::class);
        $ret=$articleWordrepo->getLetterStatistics();
        return $ret;
    }
 /**
     * @Rest\Get("/wordOcuranceStatistics")
     */
    public function getWordOcuranceStatistics() {
        $articleWordrepo = $this->getDoctrine()->getRepository(ArticleWord::class);
        $ret=$articleWordrepo->getWordOcuranceStatistics();
        return $ret;
    }
    

}
