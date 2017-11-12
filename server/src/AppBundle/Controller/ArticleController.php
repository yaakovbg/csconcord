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
use AppBundle\Service\FileAnalyzer;


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
     * @Rest\Get("/articles")
     */
    public function getArticles()
    {
        $filerepo = $this->getDoctrine()->getRepository(Article::class);
        $restresult=$filerepo->findAll();

        return $restresult;
    }
     /**
     * @Rest\Delete("/article")
     */
    public function deleteArticle(Request $request)
    {
        $data = json_decode($request->getContent(), false);
        $articlerepo = $this->getDoctrine()->getRepository(Article::class);
        if(isset($data->id))
             $restresult=$articlerepo->deleteArticleById($data->id);
         else {
             $restresult=array('error'=>'missing id');
         }

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

        return array('data'=>$res,'err'=>$err);
    }
        /**
     * @Rest\Post("/testanalyze")
     */
    public function testAnalyze(Request $request)
    {
        $data = json_decode($request->getContent());
        $id=(isset($data->id))?$data->id:'';
        $articlerepo = $this->getDoctrine()->getRepository(Article::class);
        $article=$articlerepo->getArticleById($id);
        $filesDir=$this->container->getParameter('files_directory');   
       $filepath=$filesDir.'/'.$article->getFilepath();
        
// $fullFilePath=$filesDir.'/'.$article->getFilepath();
       // print_r();
        $analyzer= new FileAnalyzer($filepath);
        
        return array('id'=>$id,'article'=>$article);
    }
}
