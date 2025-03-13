<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Schema for User model",
 *     required={"name", "email"},
 *     @OA\Property(property="id", type="integer", description="ID of the user"),
 *     @OA\Property(property="name", type="string", description="User's full name"),
 *     @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 * 
 * @OA\Schema(
 *     schema="RegisterResponse",
 *     type="object",
 *     title="Register Response",
 *     description="Response schema for user registration",
 *     @OA\Property(property="success", type="boolean", example=true, description="Indicates if the operation was successful"),
 *     @OA\Property(property="message", type="string", example="User berhasil didaftarkan!", description="Response message"),
 *     @OA\Property(property="data", type="object",
 *         @OA\Property(property="user", ref="#/components/schemas/User"),
 *         @OA\Property(property="token", type="string", description="Authentication token", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     title="Login Response",
 *     description="Response schema for user login",
 *     @OA\Property(property="success", type="boolean", example=true, description="Indicates if the operation was successful"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="token", type="string", description="Authentication token", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
 * )
 * 
 * @OA\Schema(
 *     schema="LogoutResponse",
 *     type="object",
 *     title="Logout Response",
 *     description="Response schema for user logout",
 *     @OA\Property(property="success", type="boolean", example=true, description="Indicates if the operation was successful"),
 *     @OA\Property(property="message", type="string", example="Logged out successfully.", description="Response message")
 * )
 */


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Create a new user account and return a token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="User's password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123", description="Confirmation of the password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User berhasil didaftarkan!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-09T08:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-09T08:00:00Z")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(property="errors", type="object", additionalProperties={"type": "string"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Terjadi kesalahan saat menyimpan data."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */
    public function register(Request $request) {
        // Validasi input
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
    
        try {
            // Hash password sebelum menyimpan
            $fields['password'] = bcrypt($fields['password']);
    
            // Buat user baru
            $user = User::create($fields);
    
            // Buat token untuk user
            $token = $user->createToken('authToken')->plainTextToken;
    
            // Kembalikan respons sukses
            return response()->json([
                'success' => true,
                'message' => 'User berhasil didaftarkan!',
                'data' => [
                    'user' => $user,
                    'token' => $token->plainTextToken,
                ],
            ], 201);
    
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login a user",
     *     description="Authenticate a user and return a token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com", description="User's email address"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="User's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred during login."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */
    public function login(Request $request) {
        try {
            // Validasi input
            $fields = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            // Cari user berdasarkan email
            $user = User::where('email', $request->email)->first();
    
            // Validasi kredensial
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }
    
            // Buat token
            $token = $user->createToken($user->username);
    
            // Kembalikan respons sukses
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token->plainTextToken,
            ], 200);
    
        } catch (\Exception $e) {
            // Tangani error dan kembalikan respons
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout a user",
     *     description="Revoke the user's current token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred during logout."),
     *             @OA\Property(property="error", type="string", example="Error details here")
     *         )
     *     )
     * )
     */
    public function logout(Request $request) {
        try {
            // Hapus token yang aktif
            $request->user()->currentAccessToken()->delete();
    
            // Kembalikan respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
            ], 200);
        } catch (\Exception $e) {
            // Tangani error dan kembalikan respons
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
}
