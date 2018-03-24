<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleMapService;

class GoogleMapController extends Controller
{
    /**
     * @param Request $request: HTTP Request Parser.
     * @param GoogleMapService $googelMapService: Google map service.
     * @param String $query: location name that you want to search on.
     * @return array(JSON object)
     */
    public function find(Request $request, GoogleMapService $googelMapService, String $query)
    {
        try {
            return $googelMapService
                ->setEndPoint('textsearch')
                ->format('json')
                ->addParameter('query', $query)
                ->setParameters($request->query())
                ->getData();

        } catch (\Exception $e) {
            return  [
                'error' => 'Something went wrong',
            ];
        }
    }
}
