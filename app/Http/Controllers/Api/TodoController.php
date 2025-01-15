<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Todo;
use Illuminate\Http\Request;

/**  
 * @OA\Schema(  
 *     schema="Todo",  
 *     type="object",  
 *     title="Todo",  
 *     description="Schema for Todo model",  
 *     required={"user_id", "title", "status", "due_date"},  
 *     @OA\Property(property="id", type="integer", description="ID of the todo item"),  
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who created the todo"),  
 *     @OA\Property(property="title", type="string", description="Title of the todo"),  
 *     @OA\Property(property="description", type="string", description="Description of the todo"),  
 *     @OA\Property(property="status", type="string", description="Status of the todo (e.g., pending, completed, in-progress)"),  
 *     @OA\Property(property="due_date", type="string", format="date-time", description="Due date of the todo"),  
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),  
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")  
 * )  
 */  

class TodoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/todo",
     *     tags={"Todo"},
     *     summary="Get all Todo",
     *     @OA\Response(
     *         response=200,
     *         description="List of Todo",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Todo")
     *         )
     *     )
     * )
     */
    public function index()
    {
        //get all posts
        $posts = Todo::latest()->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Todo', $posts);
    }
    /**
     * @OA\Get(
     *     path="/api/todo/{id}",
     *     tags={"Todo"},
     *     summary="Get a todo by ID",
     *     description="Retrieve a specific todo item by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo item",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true, description="Indicates the success of the operation"),
     *             @OA\Property(property="message", type="string", example="Detail Data Todo!", description="Response message"),
     *             @OA\Property(property="data", ref="#/components/schemas/Todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false, description="Indicates the success of the operation"),
     *             @OA\Property(property="message", type="string", example="Todo not found", description="Error message")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        //find post by ID
        $post = Todo::find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Todo!', $post);
    }
    /**
     * @OA\Post(
     *     path="/api/todo",
     *     tags={"Todo"},
     *     summary="Create a new todo",
     *     description="Create a new todo item for the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status", "due_date"},
     *             @OA\Property(property="title", type="string", example="Buy groceries", description="Title of the todo"),
     *             @OA\Property(property="description", type="string", example="Buy milk, eggs, and bread", description="Description of the todo"),
     *             @OA\Property(property="status", type="string", example="pending", description="Status of the todo"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-15 10:00:00", description="Due date of the todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Todo created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Post Berhasil Ditambahkan!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Todo")
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'due_date' => 'required|string|max:255',
        ]);

        try {
            //create post
            $post = $request->user()->todos()->create([
                'title'     => $request->title,
                'description'   => $request->description,
                'status'   => $request->status,
                'due_date'   => $request->due_date,
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
     *     path="/api/todo/{id}",
     *     tags={"Todo"},
     *     summary="Update an existing todo",
     *     description="Update the details of an existing todo item by its ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status", "due_date"},
     *             @OA\Property(property="title", type="string", example="Buy groceries", description="Title of the todo"),
     *             @OA\Property(property="description", type="string", example="Buy milk, eggs, and bread", description="Description of the todo"),
     *             @OA\Property(property="status", type="string", example="completed", description="Status of the todo"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-15 10:00:00", description="Due date of the todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Todo updated successfully!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Todo not found!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while updating the todo."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Find todo by ID
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found!'
            ], 404);
        }

        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'due_date' => 'required|string|max:255',
        ]);

        // Update the todo
        try {
            $todo->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'due_date' => $request->due_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully!',
                'data' => $todo
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/todo/{id}",
     *     tags={"Todo"},
     *     summary="Delete a todo",
     *     description="Delete a todo item by its ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the todo to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Todo deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data Todo Berhasil Di Delete!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Todo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Todo not found!")
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

        $post = Todo::find($id);
        try {
            $post->delete();
            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Data Todo Berhasil Di Delete!',
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
