<?php

/*
 * This file is part of the Omed project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Omed\Laravel\User\Model\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Doctrine\Common\Inflector\Inflector;

class UserResource extends JsonResource
{
    protected $keywordFilters = [
        'password',
        'salt',
        'token',
        'identifier',
        'jwt',
        'canonical'
    ];

    public function toArray($request)
    {
        /** @var \Omed\Laravel\User\Model\User $resource */
        $resource = $this->resource;
        $retVal = [];

        $r = new \ReflectionObject($resource);
        foreach($r->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            $name = $method->getName();
            $key = strtr($name,[
                'get' => '',
                'is' => '',
            ]);

            if($this->validateProperties($name)){
                $key = Inflector::camelize($key);
                $retVal[$key] = call_user_func([$resource, $name]);
            }
        }

        $retVal['_self'] = route('omed_user.show', ['omed_user' => $resource->getId()]);

        return $retVal;
    }

    protected function validateProperties($name)
    {
        if(
            false === strpos($name, 'get')
            && false == strpos($name, 'is')
        ){
            return false;
        }

        $lower = strtolower($name);
        foreach($this->keywordFilters as $filter){
            if(false !== strpos($lower,$filter)){
                return false;
            }
        }
        return true;
    }
}
