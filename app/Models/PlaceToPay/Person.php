<?php

namespace App\Models\PlaceToPay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Person extends Model
{

    public $document = '1020456789';
    public $documentType = 'CC';
    public $firstName = 'Daniel';
    public $lastName = 'Meneses C';
    public $company = 'PlaceToPay';
    public $emailAddress = 'daniel-meneses27@hotmail.com';
    public $address  = 'Cr 59';
    public $city = 'Bello';
    public $province = 'Antioquia';
    public $country = 'CO';
    public $phone = '2069898';
    public $mobile = '3122702409';

    public function __construnct() {

        $person = new Self();
        $person->$document = $this->document;
        $person->$documentType = $this->documentType;
        $person->$firstName = $this->firstName;
        $person->$lastName = $this->lastName;
        $person->$company = $this->company;
        $person->$emailAddress = $this->emailAddress;
        $person->$address = $this->address;
        $person->$city = $this->city;
        $person->$province = $this->province;
        $person->$country = $this->country;
        $person->$phone = $this->phone;
        $person->$mobile = $this->mobile;
        return $person;
    }

}
