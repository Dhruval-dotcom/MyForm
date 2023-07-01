<?php

namespace App\Form\DataTransformer;
use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Callable_;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    public function __construct(
        private UserRepository $repo,
        private $callable
    ){

    }
    // public function
    public function transform($value)
    {
        if(null === $value)
            return '';

        // if(!$value instanceof User) {
        //     throw new \LogicException('The UserSelectTextType can only be of user type');
        // }

        return $value;
    }

    public function reverseTransform($value)
    {
        if(!$value) {
            return;
        }
        $callback = $this->callable;
        $user = $callback($this->repo, $value);
        // $user = $this->repo->findOneBy(['email' => $value]);
        if(!$user){
            throw new TransformationFailedException('No such email');
        }
        return $user;
    }
}