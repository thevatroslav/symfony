<?php

namespace App\Form;

use App\Entity\Thread;
use App\Entity\User;
use App\Entity\Message;

use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('messages', CollectionType::class, [
                'entry_type' => MessageType::class,
                'entry_options'=>['label' => false],
            ]);




    }


}