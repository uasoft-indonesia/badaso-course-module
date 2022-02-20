<?php

namespace Uasoft\Badaso\Module\Lms\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Uasoft\Badaso\Controllers\Controller;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Module\Lms\Models\Category;
use Uasoft\Badaso\Module\Lms\Models\Course;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'page' => 'sometimes|required|integer',
                'limit' => 'sometimes|required|integer',
                'relation' => 'nullable'
            ]);

            if ($request->has('page') || $request->has('limit')) {
                $course = Course::when($request->relation, function ($query) use ($request) {
                    return $query->with(explode(',', $request->relation));
                })->paginate($request->limit);
            } else {
                $course = Course::when($request->relation, function ($query) use ($request) {
                    return $query->with(explode(',', $request->relation));
                })->get();
            }

            $data['course'] = $course->toArray();
            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function read(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Module\Lms\Models\Course,id',
                'relation' => 'nullable'
            ]);

            $course = Course::when($request->relation, function ($query) use ($request) {
                return $query->with(explode(',', $request->relation));
            })->where('id', $request->id)->first();
            $data['course'] = $course->toArray();

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|integer|max:10|exists:Uasoft\Badaso\Module\Lms\Models\Category,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:Uasoft\Badaso\Module\Lms\Models\Course',
                'description' => 'nullable',
                'price' => 'nullable|numeric',
                'strike' => 'nullable|numeric',
                'course_image' => 'nullable|string',
                'start_date' => 'nullable|date',
                'featured' => 'nullable|integer',
                'trending' => 'nullable|integer',
                'popular' => 'nullable|integer',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'published' => 'nullable|integer',
                'free' => 'nullable|integer',
                'expire_at' => 'nullable|date',
            ]);

            Course::create([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'strike' => $request->strike,
                'course_image' => $request->course_image,
                'start_date' => $request->start_date,
                'featured' => $request->featured,
                'trending' => $request->trending,
                'popular' => $request->popular,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'published' => $request->published,
                'free' => $request->free,
                'expire_at' => $request->expire_at,
            ]);
            DB::commit();
            $course = Course::latest()->first();
            return ApiResponse::success($course);
        } catch (Exception $e) {
            DB::rollback();
            return ApiResponse::failed($e);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Module\Lms\Models\Course,id',
            ]);

            $course = Course::find($request->id);
            $course->delete();

            DB::commit();

            return ApiResponse::success();
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id' => 'required|exists:Uasoft\Badaso\Module\Lms\Models\Course,id',
                'category_id' => 'required|integer|max:10|exists:Uasoft\Badaso\Module\Lms\Models\Category,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable',
                'price' => 'nullable|numeric',
                'strike' => 'nullable|numeric',
                'course_image' => 'nullable|string',
                'start_date' => 'nullable|date',
                'featured' => 'nullable|integer',
                'trending' => 'nullable|integer',
                'popular' => 'nullable|integer',
                'meta_title' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'published' => 'nullable|integer',
                'free' => 'nullable|integer',
                'expire_at' => 'nullable|date',
            ]);

            $course = Course::where('id', $request->id)->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'strike' => $request->strike,
                'course_image' => $request->course_image,
                'start_date' => $request->start_date,
                'featured' => $request->featured,
                'trending' => $request->trending,
                'popular' => $request->popular,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'published' => $request->published,
                'free' => $request->free,
                'expire_at' => $request->expire_at,
            ]);
            DB::commit();
            $course = Course::where("id", $request->id)->first();
            return ApiResponse::success($course);
        } catch (Exception $e) {
            DB::rollback();

            return ApiResponse::failed($e);
        }
    }
}
