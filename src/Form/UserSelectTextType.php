<?php

namespace App\Form;
use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserSelectTextType extends AbstractType
{
    public function __construct(
        private UserRepository $repo
    ){

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer(
            $this->repo,
            $options['finder_callback']
        ));
    }

    public function getParent(){
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'User Not Found',
            'finder_callback' => function (UserRepository $userRepository, string $email){
                return $userRepository->findOneBy(['email' => $email]);
            }
        ]);
    }
}