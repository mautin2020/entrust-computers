<?php 

namespace App\Repositories\Eloquent;

use Illuminate\Support\Arr;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;
use Exception;

abstract class BaseRepository implements IBase, ICriteria
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        $result = $this->model->findOrFail($id);
        return $result;
    }
    
    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }
    
    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function whereDate($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    public function whereBetween($column, $value)
    {
        return $this->model->whereBetween($column, $value)->get();
    }

    public function findWhereLast($column, $value)
    {
        return $this->model->where($column, $value)->latest();
    }

    public function paginate($perpage = 10)
    {
        return $this->model->paginate($perpage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);   
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
       $record->update($data);
       return $record;
    }

    public function delete($id)
    {
        $record =$this->find($id);
        return $record->delete();
    }

    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);

        foreach($criteria as $criterion){
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }


    protected function getModelClass()
    {
        if( !method_exists($this, 'model'))
        {
            throw new Exception("No model defined");
        }

        return app()->make($this->model());
    }
}