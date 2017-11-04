<?php
namespace AppBundle\Form;

use AppBundle\Entity\Article;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', TextType::class, array(
                    'constraints' => array(
                        new NotBlank(),
                    ),))
                ->add('topic')
                ->add('filepath')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             'csrf_protection' => false,
             'data_class' => 'AppBundle\Entity\Article'
        ]);
    }
}