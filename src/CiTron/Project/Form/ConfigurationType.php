<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Form;

use CiTron\Project\Entity\Configuration;
use CiTron\Project\Form\DataTransformer\ArrayToJsonDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', ChoiceType::class, [
                'choices' => Configuration::LANGUAGES
            ])
            ->add('envVars', null, ['required' => false])
            ->add('preparationScript', null, ['required' => false])
            ->add('launchScript', null, ['required' => false])
            ->add('vcs', ChoiceType::class, [
                'choices' => Configuration::VCS,
                'required' => 'false',
            ])
        ;

        $builder->get('envVars')->addModelTransformer(new ArrayToJsonDataTransformer());
        $builder->get('preparationScript')->addModelTransformer(new ArrayToJsonDataTransformer());
        $builder->get('launchScript')->addModelTransformer(new ArrayToJsonDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
