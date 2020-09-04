<?php

namespace App\Http\Controllers\API;

use App\CEO;
use App\Http\Resources\CEOResource;
use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class CEOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ceos = CEO::all();
        return response([
            'ceos' => CEOResource::collection($ceos),
            'message' => 'retrieved successfully'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          try {
              $this->validate($request,[
                  'name' => 'required|max:255',
                  'company_name' => 'required|max:255',
                  'year' => 'required|max:255',
                  'company_headquarters' => 'required|max:255',
                  'what_company_does' => 'required'
              ]);
          } catch (ValidationException $error) {
            return response(['error' => $error, 'message' => 'validation failed']);
          }

          $ceo = new CEO;
          $ceo->name = $request->name;
          $ceo->year = $request->year;
          $ceo['company_name'] = $request['company_name'];
          $ceo['company_headquarters'] = $request['company_headquarters'];
          $ceo['what_company_does'] = $request['what_company_does'];
          $ceo->save();

          return response([
              'ceo' => new CEOResource($ceo),
              'message' => 'CEO information created successfully'
          ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function show(CEO $ceo)
    {
        return response([
            'ceo' => new CEOResource($ceo), 'message' => 'Retrieved successfully'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CEO $ceo)
    {
        $ceo->update($request->all());

        return response([ 'ceo' => new CEOResource($ceo), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CEO  $cEO
     * @return \Illuminate\Http\Response
     */
    public function destroy(CEO $ceo)
    {
        $ceo->delete();

        return response(['message' => 'Deleted']);
    }
}
