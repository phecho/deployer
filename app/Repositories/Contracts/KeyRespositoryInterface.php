<?php

namespace REBELinBLUE\Deployer\Repositories\Contracts;

interface KeyRepositoryInterface
{
    public function getAll();
    public function create(array $fields);
    public function updateById(array $fields, $model_id);
    public function deleteById($model_id);
}
