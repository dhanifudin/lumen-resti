<?php

namespace App\Contracts;

interface RepositoryContract
{
    public function all();

    public function store(array $request);

    public function update(array $request, $id);

    public function destroy($id);
}
