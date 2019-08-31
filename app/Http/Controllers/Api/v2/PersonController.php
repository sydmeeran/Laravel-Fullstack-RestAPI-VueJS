<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Resources\v2\PersonResource;
use App\Http\Resources\v2\PersonResourceCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Person;

class PersonController extends Controller
{
    /**
     * @param Person $person
     * @return PersonResource
     */
    public function show(Person $person) :PersonResource
    {
        return new PersonResource($person);
    }

    /**
     * @return PersonResourceCollection
     */
    public function index() :PersonResourceCollection
    {
        return new PersonResourceCollection(Person::paginate());
    }

    public function store(Request $request) :PersonResource
    {
        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'city' => 'required'
            ]
        );

        $person = Person::create($request->all());

        return new PersonResource($person);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @return PersonResource
     */
    public function update(Person $person, Request $request) :PersonResource
    {
        $person->update($request->all());

        return new PersonResource($person);
    }

    /**
     * @param Person $person
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Person $person)
    {
        $person->delete();

        return response()->json();
    }
}
