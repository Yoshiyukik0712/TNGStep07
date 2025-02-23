<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function getLists()
    {
        $companies = Company::pluck('company_name','id');

        return $companies;
    }

    public function products() {
        return $this->hasMany('App\Models\Product');
    }

    public function getAllCompanies() {
        $companies = $this->all();

        return $companies;
    }
}