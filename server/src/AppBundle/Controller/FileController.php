<?php

namespace AppBundle\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
    public function postAction(Request $req)
    {
       // print_r($req);
        $file = new File();
        print_r($req->files);
        $form = $this->createFormBuilder($file)
                   ->add('file', FileType::class)
                   ->getForm();
//        $restresult=$filerepo->test();
            $form->handleRequest($req);
          
        $file = $form->getData();
        echo $file->getName();
        print_r($file);
         $file->upload();
      // print_r($form);
       // print_r($task);
//        $filerepo = $this->getDoctrine()->getRepository(File::class);
//        $restresult=$filerepo->test();
         
        return 'test';
    }
}
