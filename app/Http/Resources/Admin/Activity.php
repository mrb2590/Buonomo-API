<?php

namespace App\Http\Resource\Admin;

use App\Http\Resources\Admin\Role as RoleResource;
use App\Http\Resources\Admin\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource
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
            'log_name' => $this->log_name,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'subject_type' => $this->subject_type,
            'subject' => $this->when($this->relationLoaded('subject'), function () {
                if ($this->subject instanceof \App\Models\Role) {
                    return new RoleResource($this->subject);
                }

                if ($this->subject instanceof \App\Models\User) {
                    return new RoleResource($this->subject);
                }
            }),
            'causer_id' => $this->causer_id,
            'causer_type' => $this->causer_type,
            'causer' => new UserResource($this->whenLoaded('causer')),
            'properties' => $this->properties,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
