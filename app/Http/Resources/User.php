<?php

namespace App\Http\Resources;

use App\Http\Resources\Activity as ActivityResource;
use App\Http\Resources\Permission as PermissionResource;
use App\Http\Resources\Role as RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'activity' => ActivityResource::collection($this->whenLoaded('activity')),
            'caused_activity' => ActivityResource::collection($this->whenLoaded('caused_activity')),
        ];
    }
}
