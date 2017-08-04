<?php

namespace RSnake\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Shortener
{

    /**
     * @var string
     */
    public $fullUrl;

    /**
     * @var string
     */
    public $shortUrl;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('fullUrl', new Assert\NotBlank());
        $metadata->addPropertyConstraint('fullUrl', new Assert\Url(array(
            'protocols' => array('http', 'https')
        )));
        $metadata->addPropertyConstraint('fullUrl', new Assert\Length(array(
            'max'       => 1024
        )));
        $metadata->addPropertyConstraint('shortUrl', new Assert\Regex(array(
            'pattern'   => '/[a-z0-9]{4,255}/',
            'message'   =>
                'Short URL should consists of small case letters, numbers' .
                'and should have length between 4 and 255 characters.'
        )));
    }
}
