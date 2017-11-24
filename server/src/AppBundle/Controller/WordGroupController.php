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
use AppBundle\Entity\WordGroup;
use AppBundle\Entity\WordGroupWord;
use AppBundle\Repository\WordGroupRepository;
use AppBundle\Form\WordGroupForm;
use AppBundle\Service\FileUploader;
use AppBundle\Service\FileAnalyzer;

class WordGroupController extends FOSRestController {

    /**
     * @Rest\Get("/wordGroups")
     */
    public function getWordGroups() {
        $filerepo = $this->getDoctrine()->getRepository(WordGroup::class);
        $restresult = $filerepo->findAll();

        return $restresult;
    }

  

}
