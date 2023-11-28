<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason;

use Flarum\Api\Controller\ShowForumController;
use Flarum\User\User;
use Litalino\Mason\Repositories\ByTagRepository;
use Litalino\Mason\Repositories\FieldRepository;
use Psr\Http\Message\ServerRequestInterface;

class LoadForumFieldsRelationship
{
    public function __invoke(ShowForumController $controller, &$data, ServerRequestInterface $request)
    {
        /**
         * @var User $actor
         */
        $actor = $request->getAttribute('actor');

        /**
         * @var FieldRepository $fields
         */
        $fields = resolve(FieldRepository::class);

        /**
         * @var ByTagRepository $fields
         */
        $bytags = resolve(ByTagRepository::class);

        // Fields need to be pre-loaded for the discussion composer, and also to be able to show empty fields on discussions
        // We first try the permissions the users are most likely to have
        if ($actor->can('litalino-mason.see-other-fields') || $actor->can('litalino-mason.fill-fields') || $actor->can('litalino-mason.see-own-fields')) {
            $data['masonFields'] = $fields->all();
            $data['masonByTags'] = $bytags->all();
        } else {
            // Fill empty set. Without this, installs with visible notices will get "Undefined index: masonFields"
            $data['masonFields'] = [];
            $data['masonByTags'] = [];
        }
    }
}
