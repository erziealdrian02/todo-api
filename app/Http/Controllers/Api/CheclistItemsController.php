<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItems;
use App\Models\Checklists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="ChecklistItem",
 *     type="object",
 *     title="Checklist Item",
 *     description="Schema for Checklist Item model",
 *     required={"checklist_id", "item_name"},
 *     @OA\Property(property="id", type="integer", description="ID of the checklist item"),
 *     @OA\Property(property="checklist_id", type="integer", description="ID of the associated checklist"),
 *     @OA\Property(property="item_name", type="string", description="Name of the checklist item"),
 *     @OA\Property(property="is_completed", type="boolean", description="Completion status of the checklist item"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 */


class CheclistItemsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/checklist/{checklist_id}/item",
     *     summary="Get all items in a checklist",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of checklist items",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ChecklistItem")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Checklist not found")
     * )
     */
    public function index($checklist_id)
    {
        try {
            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $items = ChecklistItems::where('checklist_id', $checklist_id)->get();

            return response()->json(['success' => true, 'data' => $items], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch checklist items', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/checklist/{checklist_id}/item",
     *     summary="Create a new checklist item",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_name"},
     *             @OA\Property(property="item_name", type="string", description="Checklist item name")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Checklist item created", @OA\JsonContent(ref="#/components/schemas/ChecklistItem")),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Checklist not found")
     * )
     */
    public function store(Request $request, $checklist_id)
    {
        try {
            $request->validate([
                'item_name' => 'required|string|max:255',
            ]);

            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $item = ChecklistItems::create([
                'checklist_id' => $checklist_id,
                'item_name' => $request->item_name,
            ]);

            return response()->json(['success' => true, 'message' => 'Checklist item created successfully.', 'data' => $item], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create checklist item', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/checklist/{checklist_id}/item/{item_id}",
     *     summary="Get a specific checklist item",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="path",
     *         required=true,
     *         description="Checklist Item ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Checklist item found", @OA\JsonContent(ref="#/components/schemas/ChecklistItem")),
     *     @OA\Response(response=404, description="Checklist item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($checklist_id, $item_id)
    {
        try {
            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $item = ChecklistItems::where('checklist_id', $checklist_id)->find($item_id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Checklist item not found'], 404);
            }

            return response()->json(['success' => true, 'data' => $item], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch checklist item', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/checklist/{checklist_id}/item/{item_id}",
     *     summary="Update checklist item status",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="path",
     *         required=true,
     *         description="Checklist Item ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="is_completed", type="boolean", description="Completion status of the checklist item")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Checklist item updated", @OA\JsonContent(ref="#/components/schemas/ChecklistItem")),
     *     @OA\Response(response=404, description="Checklist item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, $checklist_id, $item_id)
    {
        try {
            $request->validate([
                'is_completed' => 'required|boolean',
            ]);

            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $item = ChecklistItems::where('checklist_id', $checklist_id)->find($item_id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Checklist item not found'], 404);
            }

            $item->update(['is_completed' => $request->is_completed]);

            return response()->json(['success' => true, 'message' => 'Checklist item status updated successfully.', 'data' => $item], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update checklist item', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/checklist/{checklist_id}/item/rename/{item_id}",
     *     summary="Rename checklist item",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="path",
     *         required=true,
     *         description="Checklist Item ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="item_name", type="string", description="New name for the checklist item")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Checklist item renamed", @OA\JsonContent(ref="#/components/schemas/ChecklistItem")),
     *     @OA\Response(response=404, description="Checklist item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function rename(Request $request, $checklist_id, $item_id)
    {
        try {
            $request->validate([
                'item_name' => 'required|string|max:255',
            ]);

            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $item = ChecklistItems::where('checklist_id', $checklist_id)->find($item_id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Checklist item not found'], 404);
            }

            $item->update(['item_name' => $request->item_name]);

            return response()->json(['success' => true, 'message' => 'Checklist item renamed successfully.', 'data' => $item], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to rename checklist item', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/checklist/{checklist_id}/item/{item_id}",
     *     summary="Delete a checklist item",
     *     tags={"Checklist Item"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="checklist_id",
     *         in="path",
     *         required=true,
     *         description="Checklist ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="item_id",
     *         in="path",
     *         required=true,
     *         description="Checklist Item ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Checklist item deleted"),
     *     @OA\Response(response=404, description="Checklist item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($checklist_id, $item_id)
    {
        try {
            $checklist = Checklists::where('user_id', Auth::id())->find($checklist_id);
            
            if (!$checklist) {
                return response()->json(['success' => false, 'message' => 'Checklist not found'], 404);
            }

            $item = ChecklistItems::where('checklist_id', $checklist_id)->find($item_id);

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Checklist item not found'], 404);
            }

            $item->delete();

            return response()->json(['success' => true, 'message' => 'Checklist item deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete checklist item', 'error' => $e->getMessage()], 500);
        }
    }

}
