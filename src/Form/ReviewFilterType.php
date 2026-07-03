<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFilterType extends AbstractType
{
    /**
     * Felépíti a véleménylista szűrésére szolgáló űrlapot.
     *
     * Az űrlap lehetőséget biztosít cégnév szerinti keresésre,
     * valamint az oldalanként megjelenő elemek számának kiválasztására.
     *
     * @param FormBuilderInterface $builder Az űrlap építéséért felelős builder.
     * @param array $options Az űrlap konfigurációs beállításai.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('search', SearchType::class, [
                'label' => false,
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Keresés cégnévre...',
                    'maxlength' => 255,
                    'autocomplete' => 'off',
                    'spellcheck' => 'false',
                ],
            ])

            ->add('limit', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'choices' => [
                    '10 / oldal' => 10,
                    '25 / oldal' => 25,
                    '50 / oldal' => 50,
                    '100 / oldal' => 100,
                ],
                'data' => 10,
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-select',
                    'onchange' => 'this.form.submit()',
                    'title' => 'Oldalanként megjelenő elemek száma'
                ],
            ]);
    }

    /**
     * Beállítja az űrlap alapértelmezett konfigurációját.
     *
     * A szűrő GET metódussal működik, ezért CSRF védelem nem szükséges.
     *
     * @param OptionsResolver $resolver Az opciók konfigurálásáért felelős objektum.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    /**
     * Eltávolítja az alapértelmezett form prefixet,
     * így a mezők közvetlenül a query paraméterek között jelennek meg.
     *
     * @return string Üres prefix.
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}