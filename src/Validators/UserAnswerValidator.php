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
use Litalino\Mason\Field;

class UserAnswerValidator extends AbstractValidator
{
    /**
     * @param Field $field
     *
     * @return $this
     */
    public function setField(Field $field)
    {
        $rules = [];

        if ($field->min_answers_count > 0) {
            $rules[] = 'required';
        }

        if ($field->validation) {
            $rules = array_merge($rules, explode('|', $field->validation));
        }

        $this->rules = [$field->name => $rules];

        return $this;
    }
}
