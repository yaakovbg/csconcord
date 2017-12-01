<?php
namespace AppBundle\Form;

use AppBundle\Entity\WordGroup;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class WordGroupDelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
               
                ->add('id', NumberType::class, array(
                    'constraints' => array(
                        new NotBlank(),
                    ),))
                ;
                
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
             'csrf_protection' => false,
             'data_class' => 'AppBundle\Entity\WordGroup'
        ]);
    }
}