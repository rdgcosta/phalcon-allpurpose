<?php

namespace PhalconProject\Api\Routes;

class Index extends Group
{

    const NAME_PREFIX = 'admin-index';

    public function initialize()
    {
        $this->setPaths(
            [
                'controller' => 'Index'
            ]
        );

        $this->setPrefix('');

        $this->addGet('', [
            'action' => 'hello'
        ])->setName(static::NAME_PREFIX . '-index');
    }

}