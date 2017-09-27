<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Class ServiceUserInvitationSendEmailType
 * @package AppBundle\Form
 */
class ServiceUserInvitationSendEmailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'link',
                UrlType::class,
                array(
                    "label" => false,
                    "constraints" => new Constraints\Url(),
                    "attr" => array(
                        "class" => "col-md-10",
                        "readonly" => true,
                    ),
                )
            )
            ->add(
                'message',
                TextareaType::class,
                array(
                    "label" => false,
                    "attr" => array(
                        "placeholder" => "Message",
                    ),
                )
            )
            ->add(
                'emails',
                TextareaType::class,
                array(
                    "label" => "Send invitation by email",
                    "attr" => array(
                        "placeholder" => "Comma separated list of email addresses",
                    ),
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }
}