<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\File;
use AppBundle\Repository\FileRepository;

class FileController extends FOSRestController
{
	/**
     * @Rest\Get("/file")
     */
    public function getAction()
    {
        $filerepo = $this->getDoctrine()->getRepository(File::class);
        $restresult=$filerepo->test();

        return $restresult;
    }
    	/**
     * @Rest\Post("/file")
     */
    public function postAction()
    {
         
        $filerepo = $this->getDoctrine()->getRepository(File::class);
        //$restresult=$filerepo->test();
                
        return $restresult;
    }
}
