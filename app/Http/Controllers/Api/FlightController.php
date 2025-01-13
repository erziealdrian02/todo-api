<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Flight;
use Exception;
use GuzzleHttp\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

/**
 * @OA\Schema(
 *     schema="Flight",
 *     type="object",
 *     title="Flight",
 *     description="Schema for Flight model",
 *     required={"flight_number", "airline", "origin", "destination", "departure_time", "arrival_time", "price", "seats_available"},
 *     @OA\Property(property="id", type="integer", description="ID of the flight"),
 *     @OA\Property(property="flight_number", type="string", description="Flight number"),
 *     @OA\Property(property="airline", type="string", description="Airline name"),
 *     @OA\Property(property="origin", type="string", description="Origin city"),
 *     @OA\Property(property="destination", type="string", description="Destination city"),
 *     @OA\Property(property="departure_time", type="string", format="date-time", description="Departure time"),
 *     @OA\Property(property="arrival_time", type="string", format="date-time", description="Arrival time"),
 *     @OA\Property(property="price", type="number", format="float", description="Ticket price"),
 *     @OA\Property(property="seats_available", type="integer", description="Available seats"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 */

class FlightController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/flight",
     *     tags={"Flight"},
     *     summary="Get all flights",
     *     @OA\Response(
     *         response=200,
     *         description="List of flights",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Flight")
     *         )
     *     )
     * )
     */
    public function index()
    {
        //get all posts
        $posts = Flight::latest()->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts);
    }
    /**
     * @OA\Get(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Get a flight by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the flight",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Flight details",
     *         @OA\JsonContent(ref="#/components/schemas/Flight")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flight not found"
     *     )
     * )
     */
    public function show($id)
    {
        //find post by ID
        $post = Flight::find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Post!', $post);
    }
    /**
     * @OA\Get(
     *     path="/api/flight/{startDate}/{endDate}",
     *     tags={"Flight"},
     *     summary="Get a flight by Start and End Date",
     *     @OA\Parameter(
     *         name="startDate",
     *         in="path",
     *         description="Start Date of the flight (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="path",
     *         description="End Date of the flight (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Flight details",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Flight")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flight not found"
     *     )
     * )
     */
    public function showByDate($startDate, $endDate)  
    {  
        $flights = Flight::whereBetween('departure_time', [$startDate, $endDate])->get();  

        if ($flights->isEmpty()) {  
            return new PostResource(false, 'No flights found for the specified date range.', null);  
        }  

        return new PostResource(true, 'Detail Data Penerbangan!', $flights);  
    }
    /**
     * @OA\Get(
     *     path="/api/flight/{startDate}/{endDate}/{destination}",
     *     tags={"Flight"},
     *     summary="Get a flight by Start and End Date",
     *     @OA\Parameter(
     *         name="startDate",
     *         in="path",
     *         description="Start Date of the flight (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="path",
     *         description="End Date of the flight (format: YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="destination",
     *         in="path",
     *         description="Destination",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Flight details",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Flight")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flight not found"
     *     )
     * )
     */
    public function showByFilter($startDate, $endDate, $destination)  
    {  
        $flights = Flight::whereBetween('departure_time', [$startDate, $endDate])->where('destination', $destination)->get();  

        if ($flights->isEmpty()) {  
            return new PostResource(false, 'No flights found for the specified date range.', null);  
        }  

        return new PostResource(true, 'Detail Data Penerbangan!', $flights);  
    }
    /**
     * @OA\Post(
     *     path="/api/flight",
     *     tags={"Flight"},
     *     summary="Create a new flight",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"flight_number", "airline", "origin", "destination", "departure_time", "arrival_time", "price", "seats_available"},
     *             @OA\Property(property="flight_number", type="string", example="GA123", description="Flight number"),
     *             @OA\Property(property="airline", type="string", example="Garuda Indonesia", description="Airline name"),
     *             @OA\Property(property="origin", type="string", example="Jakarta", description="Origin city"),
     *             @OA\Property(property="destination", type="string", example="Bali", description="Destination city"),
     *             @OA\Property(property="departure_time", type="string", format="date-time", example="2025-01-10 10:00:00", description="Departure time in ISO 8601 format"),
     *             @OA\Property(property="arrival_time", type="string", format="date-time", example="2025-01-10 12:00:00", description="Arrival time in ISO 8601 format"),
     *             @OA\Property(property="price", type="number", format="float", example=150.50, description="Price of the flight"),
     *             @OA\Property(property="seats_available", type="integer", example=100, description="Number of available seats"),
     *             @OA\Property(property="airline_id", type="integer", example=0, description="Airline ID (optional, defaults to 0)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Flight created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Post Berhasil Ditambahkan!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="flight_number", type="string", example="GA123"),
     *                 @OA\Property(property="airline", type="string", example="Garuda Indonesia"),
     *                 @OA\Property(property="origin", type="string", example="Jakarta"),
     *                 @OA\Property(property="destination", type="string", example="Bali"),
     *                 @OA\Property(property="departure_time", type="string", format="date-time", example="2025-01-10T10:00:00Z"),
     *                 @OA\Property(property="arrival_time", type="string", format="date-time", example="2025-01-10T12:00:00Z"),
     *                 @OA\Property(property="price", type="number", format="float", example=150.50),
     *                 @OA\Property(property="seats_available", type="integer", example=100),
     *                 @OA\Property(property="airline_id", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T08:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T08:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "string"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menyimpan data."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|string|max:255',
            'airline' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'price' => 'required|numeric',
            'seats_available' => 'required|integer',
        ]);

        try {
            //create post
            $post = $request->user()->flights()->create([
                'flight_number'     => $request->flight_number,
                'airline'   => $request->airline,
                'origin'   => $request->origin,
                'destination'   => $request->destination,
                'departure_time'   => $request->departure_time,
                'arrival_time'   => $request->arrival_time,
                'price'   => $request->price,
                'seats_available'   => $request->seats_available,
                'airline_id' => 0, // Pastikan ini sesuai dengan database Anda
            ]);

            //return response
            return response()->json([
                'success' => true,
                'message' => 'Data Post Berhasil Ditambahkan!',
                'data' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Update a flight by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the flight to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="flight_number", type="string", example="GA123"),
     *             @OA\Property(property="airline", type="string", example="Garuda Indonesia"),
     *             @OA\Property(property="origin", type="string", example="Jakarta"),
     *             @OA\Property(property="destination", type="string", example="Bali"),
     *             @OA\Property(property="departure_time", type="string", format="date-time", example="2025-01-10T10:00:00Z"),
     *             @OA\Property(property="arrival_time", type="string", format="date-time", example="2025-01-10T12:00:00Z"),
     *             @OA\Property(property="price", type="number", format="float", example=150.50),
     *             @OA\Property(property="seats_available", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Flight updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Flight updated successfully!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="flight_number", type="string", example="GA123"),
     *                 @OA\Property(property="airline", type="string", example="Garuda Indonesia"),
     *                 @OA\Property(property="origin", type="string", example="Jakarta"),
     *                 @OA\Property(property="destination", type="string", example="Bali"),
     *                 @OA\Property(property="departure_time", type="string", format="date-time", example="2025-01-10 10:00:00"),
     *                 @OA\Property(property="arrival_time", type="string", format="date-time", example="2025-01-10 12:00:00"),
     *                 @OA\Property(property="price", type="number", format="float", example=150.50),
     *                 @OA\Property(property="seats_available", type="integer", example=100),
     *                 @OA\Property(property="airline_id", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T08:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T08:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flight not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Flight not found!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the flight."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Find flight by ID
        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found!'
            ], 404);
        }

        // Validate the incoming request
        $request->validate([
            'flight_number' => 'required|string|max:255',
            'airline' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'price' => 'required|numeric',
            'seats_available' => 'required|integer',
        ]);

        // Update the flight
        try {
            $flight->update([
                'flight_number' => $request->flight_number,
                'airline' => $request->airline,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'price' => $request->price,
                'seats_available' => $request->seats_available,
                'airline_id' => 0, // Pastikan ini sesuai dengan database Anda
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight updated successfully!',
                'data' => $flight
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the flight.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Delete a flight by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the flight to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Flight deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Flight Berhasil Di Delete!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="flight_number", type="string", example="GA123"),
     *                 @OA\Property(property="airline", type="string", example="Garuda Indonesia"),
     *                 @OA\Property(property="origin", type="string", example="Jakarta"),
     *                 @OA\Property(property="destination", type="string", example="Bali"),
     *                 @OA\Property(property="departure_time", type="string", format="date-time", example="2025-01-10T10:00:00Z"),
     *                 @OA\Property(property="arrival_time", type="string", format="date-time", example="2025-01-10T12:00:00Z"),
     *                 @OA\Property(property="price", type="number", format="float", example=150.50),
     *                 @OA\Property(property="seats_available", type="integer", example=100),
     *                 @OA\Property(property="airline_id", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T08:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T08:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Flight not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Flight not found!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat mendelete data."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {

        $post = Flight::find($id);
        try {
            $post->delete();
            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Data Flight Berhasil Di Delete!',
                'data' => $post
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendelete data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
