<?php

namespace App\Http\Controllers;


use App\Services\UserWeatherService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    private $userWeatherService;

    public function __construct(UserWeatherService $userWeatherService)
    {
        $this->userWeatherService = $userWeatherService;
    }
    public function index()
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Users Loaded Successfully!',
            'data' => $this->userWeatherService->getUsersWithWeatherData(),
        ], Response::HTTP_OK);
    }
    public function show($id)
    {

        try {
            $data = $this->userWeatherService->getUserWithWeather($id);
        } catch (Exception $exception) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User Not Found',
                'data' => $exception,
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'User Loaded Successfully!',
            'data' => $data,
        ], Response::HTTP_OK);
    }
    
    public function forceUpdate($id)
    {
        try {
            $data = $this->userWeatherService->forceUpdateWeatherData($id);
        } catch (Exception $exception) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User Not Found',
                'data' => $exception,
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Weather Updated Successfully!',
            'data' => $data,
        ], Response::HTTP_OK);
    }
}
