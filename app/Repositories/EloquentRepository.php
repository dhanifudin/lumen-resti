<?php

namespace App\Repositories;

use App\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Model;

class EloquentRepository implements RepositoryContract
{
    protected $model;

    public function setModel(Model $model) {
        $this->model = $model;
    }

    public function all() {
        return $this->model->all();
    }

    public function store(array $request) {
        return $this->model->create($request);
    }

    public function update(array $request, $id) {
        $data = $this->model->find($id);
        $data->fill($request);
        return $data->save();
    }

    public function destroy($id) {
        return $this->model->destroy($id);
    }
}
