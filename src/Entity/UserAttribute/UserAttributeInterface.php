<?php

declare(strict_types=1);

namespace Incognito\Entity\UserAttribute;

interface UserAttributeInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function value(): string;
}
