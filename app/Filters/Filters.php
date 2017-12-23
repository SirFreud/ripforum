<?php

namespace App\Filters;


use Illuminate\Http\Request;
use function method_exists;

abstract class Filters
{
    protected $request, $builder;

    protected $filters = [];

    /**
     * ThreadFilters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->filters as $filter)
        {
            if (! $this->hasFilter($filter)) return;
            $this->$filter($this->request->$filter);

        }

        return $this->builder;
    }

    /**
     * @param $filter
     * @return bool
     */
    protected function hasFilter($filter)
    {
        return method_exists($this, $filter) && $this->request->has($filter);
    }
}