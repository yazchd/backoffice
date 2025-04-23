<?php

namespace App\Form;

use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormListenerFactory
{
    public function autoSlug(string $field) : callable
    {

        return function (SubmitEvent $event) use ($field) 
        {
            $data = $event->getData();

            if(empty($data->getSlug())){
                $slug = new AsciiSlugger();
                $data->setSlug(strtolower($slug->slug($data[$field])));
                $event->setData($data);
            }
            
        };
    }

    public function timestamp() : callable
    {
        return function (PreSubmitEvent $event)
        {
            $data = $event->getData();

            $data->setUpdatedAt(new \DateTimeImmutable());

            if(!$data->getId()){
                $data->setCreatedAt(new \DateTimeImmutable());
            }
            $event->setData($data);
        };
    }
}
