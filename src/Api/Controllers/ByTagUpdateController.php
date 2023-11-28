<?php

/*
 * This file is part of litalino/mason.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litalino\Mason\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Litalino\Mason\Api\Serializers\ByTagSerializer;
use Litalino\Mason\Repositories\ByTagRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ByTagUpdateController extends AbstractShowController
{
    public $serializer = ByTagSerializer::class;

    // public $include = [
    //     'allAnswers',
    // ];

    protected $bytags;

    public function __construct(ByTagRepository $bytags)
    {
        $this->bytags = $bytags;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertAdmin();

        $id = Arr::get($request->getQueryParams(), 'id');

        $field = $this->bytags->findOrFail($id);

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        return $this->bytags->update($field, $attributes);
    }
}
