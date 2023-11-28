<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason\Access;

use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;
use Litalino\Mason\Answer;

class AnswerPolicy extends AbstractPolicy
{
    public function addToDiscussion(User $user, Answer $answer)
    {
        if ($answer->is_suggested || $user->can('useCustomAnswer', $answer->field)) {
            return $this->allow();
        }
    }
}
