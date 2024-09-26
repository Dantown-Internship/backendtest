<?php

namespace App\Actions;

abstract class BaseAction {

    private array $data = [];

    private array $params = [];

    public function withParams( array $params ): static
    {
       $this->params = [...$this->params, ...$params ];
       
       return $this;
    }

    protected function getParams(): array
    {
        return $this->params;
    }

    protected function setData( array $param ): static
    {
        $this->data = [...$this->data, ...$param ];

        return $this;
    }

    public function getData(): array
    {
       return $this->data;
    }
}

