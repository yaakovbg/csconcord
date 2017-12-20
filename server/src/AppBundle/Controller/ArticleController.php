<?php

namespace AppBundle\Controller;

use JMS\Serializer\SerializerBuilder;
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
use AppBundle\Entity\ArticleWord;
use AppBundle\Entity\WordGroup;
use AppBundle\Entity\WordRelation;
use AppBundle\Entity\Relation;
use AppBundle\Classes\GlobalHolder;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Form\ArticleForm;
use AppBundle\Service\FileUploader;
use AppBundle\Service\FileAnalyzer;

class ArticleController extends FOSRestController {

    /**
     * @Rest\Get("/file")
     */
    public function getAction() {
        $filerepo = $this->getDoctrine()->getRepository(Article::class);
        $restresult = $filerepo->test();

        return $restresult;
    }

    /**
     * @Rest\Get("/article/{id}")
     */
    public function getArticle($id) {
        $articlerepo = $this->getDoctrine()->getRepository(Article::class);
        $article = $articlerepo->getArticleById($id);
        $filesDir = $this->container->getParameter('files_directory');
        $fileFullPath = $filesDir . '/' . $article->getFilepath();
        $filecontent = file_get_contents($fileFullPath);
        // $restresult->fileContent = $filecontent;
        return array('fileContent' => $filecontent, 'id' => $article->getId(), 'title' => $article->getTitle());
    }

    /**
     * @Rest\Get("/articles")
     */
    public function getArticles() {
        $filerepo = $this->getDoctrine()->getRepository(Article::class);
        $restresult = $filerepo->findAll();

        return $restresult;
    }

    /**
     * @Rest\Delete("/article")
     */
    public function deleteArticle(Request $request) {
        $data = json_decode($request->getContent(), false);
        $articlerepo = $this->getDoctrine()->getRepository(Article::class);
        if (isset($data->id))
            $restresult = $articlerepo->deleteArticleById($data->id);
        else {
            $restresult = array('error' => 'missing id');
        }

        return $restresult;
    }

    /**
     * @Rest\Post("/file")
     */
    public function postAction(Request $req, FileUploader $fileUploader) {
        // print_r($req);
        $file = $req->files->get('file');

        $res = $fileUploader->upload($file);
        return array('filename' => $res);
    }

    /**
     * @Rest\Post("/article")
     */
    public function saveArticle(Request $request) {
        $data = json_decode($request->getContent(), true);
        $article = new Article;
        $form = $this->createForm(ArticleForm::class, $article);
        $form->submit($data);
        $d = $form->getData();
        $res = '';
        $valid = $form->isValid();
        if ($valid) {
            $filerepo = $this->getDoctrine()->getRepository(Article::class);
            $res = $filerepo->saveArticle($d);
        }
        $err = $form->getErrors(true, false);

        return array('data' => $res, 'err' => $err);
    }

    /**
     * @Rest\Post("/analyzeArticle")
     */
    public function analyzeArticle(Request $request) {
        $data = json_decode($request->getContent());
        $analyzer = $this->container->get(FileAnalyzer::class);
        $articleId = (isset($data->id)) ? $data->id : '';

        $res = $analyzer->analyze($articleId);
        return array('id' => $articleId, 'res' => $res);
    }

    /**
     * @Rest\Post("/articlewords")
     */
    public function getArticleWords(Request $request) {
        $data = json_decode($request->getContent());
        $articleWordrepo = $this->getDoctrine()->getRepository(ArticleWord::class);
        $res = $articleWordrepo->getArticleWords($data);

        return $res;
        //return $filerepo->findAll();
    }

    /**
     * @Rest\Post("/distinctWords")
     */
    public function getDistinctWords(Request $request) {
        $data = json_decode($request->getContent());
        $articleWordrepo = $this->getDoctrine()->getRepository(ArticleWord::class);
        $res = $articleWordrepo->getDistictWords($data);

        return $res;
    }

    /**
     * @Rest\Get("/export")
     */
    public function export(Request $request) {
        $data = json_decode($request->getContent());

        $articleWordrepo = $this->getDoctrine()->getRepository(Article::class);
        $artilces = $articleWordrepo->findAll();

        $grouprepo = $this->getDoctrine()->getRepository(WordGroup::class);
        $wordgroups = $grouprepo->findAll();

        $relationrepo = $this->getDoctrine()->getRepository(Relation::class);
        $relations = $relationrepo->findAll();
        //print_r($res);
        $g = new GlobalHolder();

        $g->articles = $artilces;
        $g->wordGroups = $wordgroups;
        $g->relations = $relations;
        
        $g->removeIds();
        
        return $g;
        //return $filerepo->findAll();
    }

    /**
     * @Rest\Post("/import")
     */
    public function import(Request $req, FileUploader $fileUploader) {
        $file = $req->files->get('file');
        $data = $fileUploader->read($file);
        $serializer = SerializerBuilder::create()->build();
        $g = $serializer->deserialize($data, GlobalHolder::class, 'xml');
        return $g;
    }

}
