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
class WordRelationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('id', NumberType::class )
                ->add('name', TextType::class, array(
                    'constraints' => array(
                        new NotBlank(),
                    ),))
                ->add('words',CollectionType::class, array(
                    'allow_add'=>true,
                    'allow_delete'=>true,
                    // each entry in the array will be an "'word'" field
                    'entry_type'   => TextType::class,
                    // these options are passed to each "word" type
                   
                ));   
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             'csrf_protection' => false,
             'data_class' => 'AppBundle\Entity\WordGroup'
        ]);
    }
}