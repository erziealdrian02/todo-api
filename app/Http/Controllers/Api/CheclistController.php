<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Checklist",
 *     type="object",
 *     title="Checklist",
 *     description="Schema for Checklist model",
 *     required={"user_id", "name"},
 *     @OA\Property(property="id", type="integer", description="ID of the checklist"),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who owns the checklist"),
 *     @OA\Property(property="name", type="string", description="Name of the checklist"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 */

class CheclistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/checklist",
     *     summary="Get all checklists",
     *     tags={"Checklist"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of checklists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Checklist")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $checklists = Checklists::where('user_id', $user->id)->get();

            return response()->json([
                'success' => true,
                'data' => $checklists
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch checklists.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/checklist",
     *     summary="Create a new checklist",
     *     tags={"Checklist"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Checklist name")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Checklist created", @OA\JsonContent(ref="#/components/schemas/Checklist")),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $checklist = Checklists::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checklist created successfully.',
                'data' => $checklist
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create checklist.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/checklist/{id}",
     *     summary="Get a checklist by ID",
     *     tags={"Checklist"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Checklist found", @OA\JsonContent(ref="#/components/schemas/Checklist")),
     *     @OA\Response(response=404, description="Checklist not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $checklist = Checklists::where('user_id', $user->id)->where('id', $id)->first();

            if (!$checklist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checklist not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $checklist
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch checklist.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/checklist/{id}",
     *     summary="Update a checklist",
     *     tags={"Checklist"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Updated checklist name")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Checklist updated", @OA\JsonContent(ref="#/components/schemas/Checklist")),
     *     @OA\Response(response=404, description="Checklist not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $checklist = Checklists::where('user_id', $user->id)->where('id', $id)->first();

            if (!$checklist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checklist not found.',
                ], 404);
            }

            $checklist->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checklist updated successfully.',
                'data' => $checklist
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update checklist.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/checklist/{id}",
     *     summary="Delete a checklist",
     *     tags={"Checklist"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Checklist deleted"),
     *     @OA\Response(response=404, description="Checklist not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $checklist = Checklists::where('user_id', $user->id)->where('id', $id)->first();

            if (!$checklist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checklist not found.',
                ], 404);
            }

            $checklist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Checklist deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete checklist.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
