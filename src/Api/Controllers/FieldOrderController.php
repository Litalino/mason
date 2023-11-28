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
use Litalino\Mason\Api\Serializers\FieldSerializer;
use Litalino\Mason\Repositories\FieldRepository;
use Litalino\Mason\Validators\OrderValidator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FieldOrderController extends AbstractListController
{
    public $serializer = FieldSerializer::class;

    protected $validator;
    protected $fields;

    public function __construct(OrderValidator $validator, FieldRepository $fields)
    {
        $this->validator = $validator;
        $this->fields = $fields;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertAdmin();

        $attributes = $request->getParsedBody();

        $this->validator->assertValid($attributes);

        $order = Arr::get($attributes, 'sort');

        $this->fields->sorting($order);

        // Return updated sorting values
        return $this->fields->all();
    }
}
