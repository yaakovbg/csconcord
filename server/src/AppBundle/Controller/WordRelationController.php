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
use AppBundle\Entity\Relation;
use AppBundle\Entity\WordRelation;
use AppBundle\Repository\WordRelationRepository;
use AppBundle\Form\WordRelationForm;
use AppBundle\Form\WordRelationDelForm;
use AppBundle\Service\FileUploader;
use AppBundle\Service\FileAnalyzer;

class WordRelationController extends FOSRestController {

    /**
     * @Rest\Get("/wordRelations")
     */
    public function getWordRelations() {
        $filerepo = $this->getDoctrine()->getRepository(WordRelation::class);
        $data = $filerepo->getAllWordRelations();

        return $data;
    }

    /**
     * @Rest\Post("/wordRelation")
     */
    public function postWordRelation(Request $request) {
        $data = json_decode($request->getContent(), true);
        $wordRelationRepo = $this->getDoctrine()->getRepository(WordRelation::class);
        $wordgroup = new WordRelation;

        //validate data
        $form = $this->createForm(WordRelationForm::class, $wordgroup);
        $form->submit($data);
        $d = $form->getData();
        $res = $wordRelationRepo->save($d);

        // $wordgroup->setWords($data['words']);
        // $d=$wordgroup;
        //return array('form'=>$d,'data'=>$data);
        return array('result' => $res);
    }

    /**
     * @Rest\Delete("/wordRelation")
     */
    public function deleteWordRelation(Request $request) {
        $data = json_decode($request->getContent(), true);
        $wordgroup = new WordRelation;
        $form = $this->createForm(WordRelationDelForm::class, $wordgroup);
        $form->submit($data);
        $d = $form->getData();
        $valid = $form->isValid();
        if ($valid) {
             $wordRelationRepo = $this->getDoctrine()->getRepository(WordRelation::class);
             $res = $wordRelationRepo->delete($d);
        } else {
            $res = $form->getErrors(true, false);
        }

        return $res;
    }

}
