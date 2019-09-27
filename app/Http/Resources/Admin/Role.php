<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\Permission as PermissionResource;
use App\Http\Resources\Admin\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
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
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'created_by_id' => $this->created_by_id,
            'updated_by_id' => $this->updated_by_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => new UserResource($this->whenLoaded('created_by')),
            'updated_by' => new UserResource($this->whenLoaded('updated_by')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
