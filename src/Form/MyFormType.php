<?php

namespace App\Form;

use App\Entity\Formdata;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\UserSelectTextType;

class MyFormType extends AbstractType
{
    public function __construct(
        private UserRepository $ur
    ){

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Formdata|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();
        // $location = $article ? $article->getLocation() : null;
        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy',
                'invalid_message' => 'Symfony is too smart for your hacking',
            ])
            ->add('content', null , [
                'rows' => 5
            ])
            ->add('email', UserSelectTextType::class, [
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
                'disabled' => $isEdit
            ])
            ->add('Location',ChoiceType::class,[
                'placeholder' => 'Choose a Location',
                'choices' => [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar space' => 'interstellar_space'
                ],
                'required' => false
            ])
            
            ;

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event){
                    /** @var Formdata|null $data */
                    $data = $event->getData();
                    if(!$data){
                        return;
                    }
                    $this->setupSpecificLocation(
                        $event->getForm(),
                        $data->getLocation()
                    );
                }
            );
            // if($location){
            //     $builder->add('specificLocationName',ChoiceType::class,[
            //         'placeholder' => 'Where exactly',
            //         'choices' => $this->getLocationNameChoices($location),
            //         'required' => false
            //     ]);
            // }
            if($options['include_published_at']){
                $builder->add('createdAt', null, [
                    'widget' => 'single_text',
                ]);
            } 

            $builder->get('Location')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event){
                    $form = $event->getForm();
                    $this->setupSpecificLocation(
                        $form->getParent(),
                        $form->getData()
                    );
                }
            );

            // ->add('email', ChoiceType::class, [
            //     'placeholder' => 'Choose an Author',
            //     'choices' => $this->ur->findAll(),
            //     'choice_label' => function (?User $user) {
            //         return $user->getEmail();
            //     },
            //     'invalid_message' => 'Symfony is too smart for your hacking!!',
            //     'autocomplete' => true,
            // ])

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formdata::class,
            'include_published_at' => true
        ]);
    }

//     public function buildView(FormView $formView,FormInterface $formInterface)
//     {
//         // $attr = $formView->vars['attr'];
//         // $class = isset($attr['class']) ? $attr['class'].'':'';
//     }


    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Jupiter'
        ];

        $stars = [
            'Polaris',
            'Sirius',
            'Rigel'
        ];

        $locationChoice = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationChoice[$location] ?? null;
    }
    private function setupSpecificLocation(FormInterface $formInterface, ?string $location)
    {
        if (null === $location) {
            $formInterface->remove('specificLocationName');
            return;
        }

        $choices = $this->getLocationNameChoices($location);

        if (null === $choices) {
            $formInterface->remove('specificLocationName');
            return;
        }

        $formInterface->add('specificLocationName',ChoiceType::class,[
            'placeholder' => 'Where exactly',
            'choices' => $choices,
            'required' => false
        ]);
    }
}
