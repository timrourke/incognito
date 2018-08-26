<?php

declare(strict_types=1);

namespace Incognito\Entity\UserAttribute;

/**
 * Class MissingAttribute
 *
 * An "optional" object, useful for when a user attribute lookup finds nothing.
 *
 * @package Incognito\Entity\UserAttribute
 */
class MissingAttribute extends UserAttribute
{
    /**
     * MissingAttribute constructor.
     */
    public function __construct()
    {
        parent::__construct('missing_attribute', '');
    }
}
