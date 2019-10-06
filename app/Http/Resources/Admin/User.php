<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\Activity as ActivityResource;
use App\Http\Resources\Admin\Permission as PermissionResource;
use App\Http\Resources\Admin\Role as RoleResource;
use App\Http\Resources\Admin\User as UserResource;
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
            'email_verified_at' => $this->email_verified_at,
            'username' => $this->username,
            'created_by_id' => $this->created_by_id,
            'updated_by_id' => $this->updated_by_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => new UserResource($this->whenLoaded('created_by')),
            'updated_by' => new UserResource($this->whenLoaded('updated_by')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'activity' => ActivityResource::collection($this->whenLoaded('activity')),
            'caused_activity' => ActivityResource::collection($this->whenLoaded('caused_activity')),
        ];
    }
}
