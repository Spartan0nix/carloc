<?php

namespace App\Helper;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FixtureHashPassword
{
    public function __construct(private UserPasswordEncoderInterface $encoder){}

    public function hashPassword(string $plainTextPassword): string {
        return $this->encoder->encodePassword(new User(), $plainTextPassword);
    }
}