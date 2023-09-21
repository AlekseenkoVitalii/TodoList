<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Todo' => 'todo',
                    'Done' => 'done',
                ],
                'invalid_message' => 'Choose a valid status: todo or done.',
            ])
            ->add('priorityStart', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Choose a valid priorityStart: number from 1 to 5.',
                    ]),
                ],
            ])
            ->add('priorityFinish', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'Choose a valid priorityFinish: number from 1 to 5.',
                    ]),
                ],
            ])
            ->add('orderPriority', ChoiceType::class, [
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC',
                ],
                'invalid_message' => 'Choose a valid orderPriority: ASC or DESC.',
            ])
            ->add('orderCreatedAt', ChoiceType::class, [
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC',
                ],
                'invalid_message' => 'Choose a valid orderPriority: ASC or DESC.',
            ])
            ->add('orderCompletedAt', ChoiceType::class, [
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC',
                ],
                'invalid_message' => 'Choose a valid orderCompletedAt: ASC or DESC.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'validation_groups' => ['Default'],
        ]);
    }
}