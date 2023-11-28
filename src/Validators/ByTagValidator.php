<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason\Validators;

use Flarum\Foundation\AbstractValidator;

class ByTagValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'tag_name' => 'string',
            'tag_id' => 'integer',
            'field_name' => 'string',
            'switch' => 'boolean',
        ];
    }
}
