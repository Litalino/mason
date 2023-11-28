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

use Flarum\Api\Controller\AbstractListController;
use Litalino\Mason\Api\Serializers\ByTagSerializer;
use Litalino\Mason\Repositories\ByTagRepository;
use Litalino\Mason\Validators\OrderValidator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ByTagOrderController extends AbstractListController
{
    public $serializer = ByTagSerializer::class;

    protected $validator;
    protected $bytags;

    public function __construct(OrderValidator $validator, ByTagRepository $bytags)
    {
        $this->validator = $validator;
        $this->bytags = $bytags;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertAdmin();

        $attributes = $request->getParsedBody();

        $this->validator->assertValid($attributes);

        $order = Arr::get($attributes, 'sort');

        $this->bytags->sorting($order);

        // Return updated sorting values
        return $this->bytags->all();
    }
}
