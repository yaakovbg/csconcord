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
use Symfony\Component\Form\FormInterface;

class WordRelationController extends FOSRestController {

    /**
     * @Rest\Get("/wordRelations")
     */
    public function getWordRelations() {
        $repo = $this->getDoctrine()->getRepository(WordRelation::class);
        $data = $repo->getAllWordRelations();

        return $data;
    }

    /**
     * @Rest\Post("/wordRelation")
     */
    public function postWordRelation(Request $request) {
        $data = json_decode($request->getContent(), true);
        $wordRelationRepo = $this->getDoctrine()->getRepository(WordRelation::class);
        $relation = new Relation;

        //validate data
        $form = $this->createForm(WordRelationForm::class, $relation);
        $form->submit($data);
        $valid = $form->isValid();
        if ($valid) {
            $d = $form->getData();
            $res = $wordRelationRepo->save($d);
        }else{
             $res = $form->getErrors(true, false);
        }

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
        $relation = new Relation;
        $form = $this->createForm(WordRelationDelForm::class, $relation);
        $form->submit($data);
        $d = $form->getData();
        $valid = $form->isValid();
        if ($valid) {
             $wordRelationRepo = $this->getDoctrine()->getRepository(WordRelation::class);
             $ret = $wordRelationRepo->delete($d);
              $res = array('result'=>$ret);
        } else {
            $res = array('errors'=>$this->getErrorsFromForm($form));
        }

        return $res;
    }
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

}
