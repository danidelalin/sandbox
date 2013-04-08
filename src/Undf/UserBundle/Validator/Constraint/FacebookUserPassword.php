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

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FacebookUserPassword extends Constraint
{
    public $message = 'This value should be the user current password.';

    public function validatedBy()
    {
        return 'security.validator.facebook_user_password';
    }
}
