<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Jenssegers\Mongodb\Eloquent\Model;

class Mongo extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    public $nested;
    public $nestedMany;

    public function hasManyNested($model, \Closure $next = null)
    {
        $related = (string) Str::of($model)
            ->lower()
            ->plural()
            ->basename();

        if ($this->nestedMany === null){
            $this->nestedMany = new \stdClass();
        }

        if (!isset($this->nestedMany->{$related})){
            $this->nestedMany->{$related} = collect($this->getAttribute($related))->map(function ($attributes) use ($model){
                return new $model($attributes);
            });
        }

        if ($next){
            $this->nestedMany->{$related} = $next($this->nestedMany->{$related});
        }

        return $this->nestedMany->{$related};
    }

    public function hasNested($model, \Closure $next = null)
    {
        $related = (string) Str::of($model)
            ->lower()
            ->singular()
            ->basename();

        if ($this->nested === null){
            $this->nested = new \stdClass();
        }

        if (!isset($this->nested->{$related})){
            $this->nested->{$related} = new $model($this->getAttribute($related) ?? []);
        }

        if ($next){
            $next($this->nested->{$related});
        }

        return $this->nested->{$related};
    }

    public function save(array $options = [])
    {
        $this->fill($this->prepare($this));
        return parent::save($options);
    }

    public function prepare($entity = null, &$attributes = [])
    {
        if ($entity->nested !== null){
           $models = get_object_vars($entity->nested);
           foreach ($models as $key => $model){
                $attributes[$key] = $model->toArray();
                $this->prepare($model, $attributes[$key]);
           }
        }

        if ($entity->nestedMany !== null){
            $collections = get_object_vars($entity->nestedMany);
            foreach ($collections as $key => $models){
                foreach ($models as $model){
                    $attributes[$key][] = $model->toArray();
                    $this->prepare($model, $attributes[$key]);
                }
            }
        }

        return $attributes;
    }

}
