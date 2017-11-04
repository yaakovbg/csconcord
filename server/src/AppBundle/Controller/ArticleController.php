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
use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Form\ArticleForm;
use AppBundle\Service\FileUploader;


class ArticleController extends FOSRestController
{
	/**
     * @Rest\Get("/file")
     */
    public function getAction()
    {
        $filerepo = $this->getDoctrine()->getRepository(Article::class);
        $restresult=$filerepo->test();

        return $restresult;
    }
    /**
     * @Rest\Post("/file")
     */
    public function postAction(Request $req, FileUploader $fileUploader)
    {
       // print_r($req);
       $file=$req->files->get('file');
      
       $res=$fileUploader->upload($file);
        return array('filename'=>$res);
    }
    /**
     * @Rest\Post("/article")
     */
    public function saveArticle(Request $request)
    {

       $data = json_decode($request->getContent(), true);
       $article=new Article;
//        $form = $this->createFormBuilder($article,array( 'csrf_protection' => false))
//                ->add('title', TextType::class, array(
//                    'constraints' => array(
//                        new NotBlank(),
//                    ),))
//                ->add('topic')
//                ->add('filepath')
//                ->getForm();
        $form = $this->createForm(ArticleForm::class, $article);
         $form->submit($data);
        $d= $form->getData();
        $res='';
        $valid=$form->isValid();
        if($valid){
             $filerepo = $this->getDoctrine()->getRepository(Article::class);
             $res=$filerepo->saveArticle($d);
        }
       $err=$form->getErrors(true, false);
       //print_r($err);
     //  print_r($d);
     //  print_r($data);
       
        return array('data'=>$res,'err'=>$err);
    
   
    }
}
