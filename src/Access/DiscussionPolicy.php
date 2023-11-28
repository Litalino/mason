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

use Flarum\Discussion\Discussion;
use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class DiscussionPolicy extends AbstractPolicy
{
    public function seeMasonAnswers(User $actor, Discussion $discussion)
    {
        if ($actor->can('litalino-mason.see-other-fields')) {
            return $this->allow();
        }

        if ($actor->can('litalino-mason.see-own-fields') && $discussion->user_id == $actor->id) {
            return $this->allow();
        }
    }

    public function fillMasonAnswers(User $actor, Discussion $discussion)
    {
        if ($actor->can('litalino-mason.fill-fields')) {
            return $this->allow();
        }
    }

    public function updateMasonAnswers(User $actor, Discussion $discussion)
    {
        if ($actor->can('litalino-mason.update-other-fields')) {
            return $this->allow();
        }

        if ($actor->can('litalino-mason.update-own-fields') && $discussion->user_id == $actor->id) {
            return $this->allow();
        }
    }
}
