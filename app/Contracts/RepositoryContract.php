<?php

namespace App\Contracts;

Interface RepositoryContract
{
    public function all();

    public function get($id);

    public function store(array $request);

    public function update(array $request, $id);

    public function destroy($id);
}
