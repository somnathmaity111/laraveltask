<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /* api for get users details */
    public function index(Request $request)
    {
        $query = User::query()->with(['details', 'location']);

        // Filtering by gender
        if ($request->has('gender')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        // Filtering by city
        if ($request->has('city')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // Filtering by country
        if ($request->has('country')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('country', $request->country);
            });
        }

        // Determine if pagination is required
        $paginate = filter_var($request->input('paginate', 'true'), FILTER_VALIDATE_BOOLEAN);

        // Get total count before fetching data
        $totalCount = $query->count();

        // Get requested fields
        if ($request->has('fields')) {
            $requestedFields = explode(',', $request->fields);
        } else {
            // Default fields when no 'fields' parameter is provided
            $requestedFields = ['id', 'name', 'email', 'gender', 'city', 'country'];
        }

        // Define valid fields for each table
        $userFields = ['id', 'name', 'email'];
        $detailsFields = ['gender'];
        $locationFields = ['city', 'country'];

        // Select only user table fields
        $selectedUserFields = array_intersect($requestedFields, $userFields);
        if (! in_array('id', $selectedUserFields)) {
            $selectedUserFields[] = 'id'; // Always include 'id' for relationships
        }

        if ($paginate) {
            // If paginate=true (default), allow per_page parameter
            $perPage = $request->input('per_page', 10); // Default is 10 per page
            $users = $query->select($selectedUserFields)->paginate($perPage);

            // Process gender and location dynamically
            $users->getCollection()->transform(function ($user) use ($requestedFields, $detailsFields, $locationFields) {
                return $this->formatUserData($user, $requestedFields, $detailsFields, $locationFields);
            });

            return ApiResponseHelper::sendResponse($users, 'Users fetched successfully.');
        } else {
            // If paginate=false, fetch all data and ignore per_page
            $users = $query->select($selectedUserFields)->get()->map(function ($user) use ($requestedFields, $detailsFields, $locationFields) {
                return $this->formatUserData($user, $requestedFields, $detailsFields, $locationFields);
            });

            return ApiResponseHelper::sendResponse([
                'data' => $users,
                'total' => $totalCount,
            ], 'Users fetched successfully without pagination.');
        }
    }

    // Helper function to format user data based on requested fields
    private function formatUserData($user, $requestedFields, $detailsFields, $locationFields)
    {
        $filteredUser = collect($user)->only($requestedFields)->toArray();

        // Include gender if requested
        if (in_array('gender', $requestedFields) && $user->details) {
            $filteredUser['gender'] = $user->details->gender;
        }

        // Include city and country if requested
        if ($user->location) {
            if (in_array('city', $requestedFields)) {
                $filteredUser['city'] = $user->location->city;
            }
            if (in_array('country', $requestedFields)) {
                $filteredUser['country'] = $user->location->country;
            }
        }

        return $filteredUser;
    }
}
