<?php

namespace App\Http\Forms;

use App\Models\User;
use App\Models\Profession;
use App\Models\Skill;
use Illuminate\Contracts\Support\Responsable;

class UserForm implements Responsable
{
    private $view;
    private $user;

    public function __construct($view, User $user)
    {
        $this->view = $view;
        $this->user = $user;
    }

    /**
     * Convertir un objeto en una respuesta HTTP
     */
    public function toResponse($request)
    {
        return view($this->view, [
      'professions' => Profession::orderBy('title', 'ASC')->get(),
      'skills' => Skill::orderBy('name', 'ASC')->get(),
      'roles' => trans('users.roles'),
      'user' => $this->user,
    ]);
    }
}
