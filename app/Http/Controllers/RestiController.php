<?php

namespace App\Http\Controllers;

use App\Contracts\RepositoryContract as Repository;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class RestiController extends BaseController
{
    protected $repository;

    public function setRepository(Repository $repository) {
        $this->repository = $repository;
    }

    public function all() {
        $response = $this->repository->all();

        return response()->json($response);
    }

    public function store(Request $request) {
        $response = $this->repository->store($request->all());

        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $response = $this->repository->update($request->all(), $id);

        return response()->json($response);
    }

    public function destroy($id) {
        $response = $this->repository->destroy($id);

        return response()->json($response);
    }
}
