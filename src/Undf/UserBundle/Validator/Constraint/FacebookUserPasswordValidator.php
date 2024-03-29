<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Undf\UserBundle\Validator\Constraint;

use Symfony\Component\Security\Core\User\UserInterface;
use Undf\UserBundle\Entity\FacebookUserInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class FacebookUserPasswordValidator extends ConstraintValidator
{

    private $securityContext;
    private $encoderFactory;

    public function __construct(SecurityContextInterface $securityContext, EncoderFactoryInterface $encoderFactory)
    {
        $this->securityContext = $securityContext;
        $this->encoderFactory = $encoderFactory;
    }

    public function validate($password, Constraint $constraint)
    {
        $user = $this->securityContext->getToken()->getUser();

        if (!$user instanceof UserInterface) {
            throw new ConstraintDefinitionException('The User must extend UserInterface');
        }

        if (!$user instanceof FacebookUserInterface) {
            throw new ConstraintDefinitionException('The User must implement FacebookUserInterface');
        }

        $userPassword = $user->getPassword();
        if (!$user->hasFacebookRole() || !empty($userPassword)) {

            $encoder = $this->encoderFactory->getEncoder($user);

            if (!$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
                $this->context->addViolation($constraint->message);
            }
        }
    }

}
