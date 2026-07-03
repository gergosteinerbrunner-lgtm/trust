<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    /**
     * Felépíti az új vélemény létrehozására szolgáló űrlapot.
     *
     * Az űrlap tartalmazza a cég nevét, az értékelést,
     * a vélemény szövegét és a szerző e-mail címét.
     *
     * @param FormBuilderInterface $builder Az űrlap építéséért felelős builder.
     * @param array $options Az űrlap konfigurációs beállításai.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('companyName', TextType::class, [
                'label' => 'Cégnév',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Pl. Netflix',
                    'maxlength' => 255,
                    'autocomplete' => 'organization',
                ],
            ])

            ->add('rating', ChoiceType::class, [
                'label' => 'Értékelés',
                'placeholder' => 'Válassz értékelést...',
                'choices' => [
                    '★★★★★ (5)' => 5,
                    '★★★★☆ (4)' => 4,
                    '★★★☆☆ (3)' => 3,
                    '★★☆☆☆ (2)' => 2,
                    '★☆☆☆☆ (1)' => 1,
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
            ])

            ->add('reviewText', TextareaType::class, [
                'label' => 'Vélemény',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6,
                    'maxlength' => 5000,
                    'placeholder' => 'Írd le a tapasztalataidat...',
                ],
            ])

            ->add('authorEmail', EmailType::class, [
                'label' => 'E-mail cím',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'pelda@email.com',
                    'maxlength' => 255,
                    'autocomplete' => 'email',
                ],
            ]);
    }

    /**
     * Beállítja az űrlap alapértelmezett konfigurációját.
     *
     * Az űrlap a Review entitáshoz van kötve.
     *
     * @param OptionsResolver $resolver Az opciók konfigurálásáért felelős objektum.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}