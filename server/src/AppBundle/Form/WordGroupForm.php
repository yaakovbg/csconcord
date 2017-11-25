<?php
namespace AppBundle\Form;

use AppBundle\Entity\WordGroup;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class WordGroupForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, array(
                    'constraints' => array(
                        new NotBlank(),
                    ),))
                ->add('words',CollectionType::class, array(
                    // each entry in the array will be an "email" field
                    'entry_type'   => TextType::class,
                    // these options are passed to each "email" type
                   
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