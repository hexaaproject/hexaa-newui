<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServicePropertiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $datas)
    {
        $builder
            ->add(
                'serviceName',
                TextType::class,
                array(
                    "label" => "Name",
                    "label_attr" => array('class' => 'formlabel'),
                    'data' => $datas['data']['properties']['serviceName'],
                    'attr'  => array('class' => 'pull-right'),
                    'required' => true
                    )
                )
            ->add(
                'serviceDescription',
                TextareaType::class,
                array(
                    "label" => "Description",
                    "label_attr" => array('class' => 'formlabel'),
                    'data' => $datas['data']['properties']['serviceDescription'],
                    'attr'  => array('class' => 'pull-right', 'cols'=>'30', 'rows'=>'1'),
                    'required' => false
                    )
                )
            ->add(
                'serviceURL',
                TextType::class,
                array(
                    "label" => "Home page",
                    "label_attr" => array('class' => 'formlabel'),
                    'data' => $datas['data']['properties']['serviceURL'],
                    'attr'  => array('class' => 'pull-right'),
                    'required' => false
                    )
                )
            ->add(
                'serviceSAML',
                TextType::class,
                array(
                    "label" => "SAML SP Entity ID",
                    "label_attr" => array('class' => 'formlabel'),
                    'data' => $datas['data']['properties']['serviceSAML'],
                    'attr'  => array('class' => 'pull-right'),
                    'required' => true
                    )
                )
            /*->add(
               'submitButton',
               ButtonType::class,
                array(
                    "label" => "Click",
                    'attr'  => array('class' => 'sendbutton pull-right')
                    )                   
               )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }
}
