<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestProcessor
{
    /**
     * The request object.
     * 
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The model's class name.
     * 
     * @var string
     */
    protected $model;

    /**
     * The query to execute.
     * 
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Validation array for loading relationships.
     * 
     * @var array
     */
    protected $loadValidation = [
        'load' => ['nullable', 'array'],
        'load.*' => ['required_with:load', 'string'],
    ];

    /**
     * Create a new instance of the request processor.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $model
     * @return void
     */
    public function __construct(Request $request, $model = null)
    {
        $this->request = $request;
        $this->model = $model;
        $this->query = $model ? $model::query() : null;
    }

    /**
     * Validate the given request for paginated results.
     * 
     * @return void
     */
    protected function validator()
    {
        return Validator::make($this->request->all(), [
            'load' => ['nullable', 'array'],
            'load.*' => ['required_with:load', 'string'],
        ]);
    }

    /**
     * Validate the given request for paginated results.
     * 
     * @return void
     */
    protected function validatePaginiation()
    {
        $validator = $this->validator();
        $validator->addRules([
            'limit' => ['nullable', 'string', 'in:-1,10,25,50,100'],
            'page' => ['nullable', 'integer'],
            'sort' => ['nullable', 'string', 'regex:/('.implode('|', $this->model::$sortableColumns).'),(asc|desc)/'],
            'search' => ['nullable', 'string'],
        ]);
        $validator->validate();
    }

    /**
     * Return paginated models with requested relationships.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function index(Builder $query = null)
    {
        $this->validatePaginiation();

        if ($query) {
            $this->query = $query;
        }

        if ($this->request->has('search')) {
            $this->query->where(function ($query) {
                foreach ($this->model::$searchableColumns as $column) {
                    $query->orWhere($column, 'like', '%'.$this->request->search.'%');
                }
            });
        }

        if ($this->request->has('sort')) {
            $sortParts = explode(',', $this->request->sort, 2);

            $this->query->orderBy($sortParts[0], $sortParts[1]);
        }

        $limit = 10;

        if ($this->request->has('limit')) {
            $limit = $this->request->limit == -1 ? $this->query->count() : $this->request->limit;
        }

        $this->query->with($this->request->load ?? []);

        return $this->query->paginate($limit);
    }

    /**
     * Return a single model with requested relationships.
     * 
     * @param  mixed  $model
     * @return mixed
     */
    public function show($model)
    {
        $this->validator()->validate();

        $model->load($this->request->load ?? []);

        return $model;
    }
}
