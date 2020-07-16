<?php

namespace App\Http\Controllers\Api\Gig;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gig\GigPreviewResource;
use Illuminate\Http\Request;

use App\User;

use App\Http\Resources\Gig\UserGigResource;
use App\Model\App\AppSetting;
use App\Model\Gig\GigAnalytics;
use App\Model\Gig\GigGallery;
use App\Model\Gig\GigPackage;
use App\Model\Gig\GigRequirement;
use App\Model\Gig\UserGig;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ApiUserGigController extends Controller
{
    public function index(Request $request, $days_for_stats = null)
    {
        if (!!$days_for_stats) {
            $date = Carbon::today()->subDays($days_for_stats);
            $gigs = UserGig::where('user_id', $request->user()->id)->where('created_at', '>=', $date)->get();
            // return response()->json(['data' => $gigs], 500);
            return response()->json(['data' => UserGigResource::collection($gigs)], 200);
        } else {
            return response()->json(['data' => UserGigResource::collection($request->user()->gigs)], 200);
        }
    }

    public function getSpecificStatusGigs(Request $request, $status = 'publish')
    {
        $gigs = UserGig::where('user_id', $request->user()->id)->where('status', $status)->get();
        return response()->json(['data' => UserGigResource::collection($gigs)], 200);
    }

    public function getOtherUserSpecificStatusGigs(Request $request, $userID, $status = 'publish')
    {
        if (!!$userID) {
            $gigs = UserGig::where('user_id', $userID)->where('status', $status)->get();
            return response()->json(['data' => UserGigResource::collection($gigs)], 200);
        } else {
            return response()->json(['message' => "Not Found!"], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'category_id' => 'required',
            // 'subcategory_id' => 'numeric',
            // 'service_type_id' => 'required',
            'title' => 'required',
            // 'description' => 'required',
            'status' => 'required'
            // 'gig_type' => 'required',
            // 'hourly_rate' => 'numeric'
        ]);

        // if ( (!($request->gig_type == 'hourly') && !($request->gig_type == 'package')) ) {
        //     return response()->json(['message' => "No Other Gig Type Allowed, Gig Type Must Be 'hourly' or 'package'"], 500);
        // }

        $new_gig = UserGig::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->has('category_id') ? $request->category_id : null,
            'subcategory_id' => $request->has('subcategory_id') ? $request->subcategory_id : null,
            'service_type_id' => $request->has('service_type_id') ? $request->service_type_id : null,
            'title' => $request->has('title') ? $request->title : null,
            'description' => $request->has('description') ? $request->description : null,
            'status' => $request->has('status') ? $request->status : null,
            'gig_type' => $request->has('gig_type') ? $request->gig_type : null,
            'tags' => json_encode($request->tags),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => $request->has('is_three_packages_mode_on') ? $request->is_three_packages_mode_on : null,
            'is_extra_fast_delivery_on' => $request->has('is_extra_fast_delivery_on') ? $request->is_extra_fast_delivery_on : null
        ]);

        // Saving Packages
        // if ($request->gig_type == 'package') {
        //     if (!is_null($request->packages)) {
        //         for ($i=0; $i < count($request->packages); $i++) { 
        //             GigPackage::create([
        //                 'gig_id' => $new_gig->id,
        //                 'title' => $request->packages[$i]['title'],
        //                 'description' => $request->packages[$i]['description'],
        //                 'price' => $request->packages[$i]['price'],
        //                 'time' => $request->packages[$i]['time']
        //             ]);
        //         }
        //     }
        // }

        // Saving Files
        // $this->uploadFilesLoop($new_gig->id, 'image', 'image');
        // $this->uploadFilesLoop($new_gig->id, 'video', 'video');

        // Saving Requirements
        // if (!is_null($request->requirements)) {
        //     for ($i=0; $i < count($request->requirements); $i++) { 
        //         GigRequirement::create([
        //             'gig_id' => $new_gig->id,
        //             'title' => $request->requirements[$i]['title'],
        //             'description' => $request->requirements[$i]['description'],
        //             'is_required' => $request->requirements[$i]['is_required']
        //         ]);
        //     }
        // }

        if ($new_gig) {
            return response()->json(['data' => $new_gig], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function show($id)
    {
        $gig = UserGig::where('id', $id)->with('category', 'subcategory', 'servicetype', 'gallery', 'packages', 'requirements')->first();
        if ($gig) {
            return response()->json(['data' => new UserGigResource($gig)], 200);
        } else {
            return response()->json(['message' => 'No Gig Found!'], 404);
        }
    }

    public function getPreviewGigData($id)
    {
        $gig = UserGig::where('id', $id)->with('user', 'category', 'subcategory', 'servicetype', 'gallery', 'packages', 'requirements')->first();
        if (!$gig) {
            return response()->json(['message' => "No Gig Found!"], 404);
        } else if ($gig->status != 'publish') {
            return response()->json(['message' => "Gig Not Published!"], 404);
        }
        return response()->json(['data' => new GigPreviewResource($gig)], 200);
    }

    public function getGigDetails($id)
    {
        $gig = UserGig::where('id', $id)->with('category', 'subcategory', 'servicetype', 'gallery', 'packages', 'requirements')->first();
        if ($gig) {
            GigAnalytics::create([
                'gig_id' => $id,
                'type' => 'click'
            ]);
            GigAnalytics::create([
                'gig_id' => $id,
                'type' => 'view'
            ]);
            return response()->json(['data' => new UserGigResource($gig)], 200);
        } else {
            return response()->json(['message' => 'No Gig Found!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // return response()->json(['request' => $request->file('image1'), 'id' => $id], 500);

        $gigIdFromURL = $id;
        $gig = UserGig::where('id', $id)->first();
        if (!$gig) {
            return response()->json(['message' => "No Gig Found!"], 500);
        }

        $gig->update([
            'category_id' => $request->has('category_id') ? $request->category_id : $gig->category_id,
            'subcategory_id' => $request->has('subcategory_id') ? $request->subcategory_id : $gig->subcategory_id,
            'service_type_id' => $request->has('service_type_id') ? $request->service_type_id : $gig->service_type_id,
            'title' => $request->has('title') ? $request->title : $gig->title,
            'description' => $request->has('description') ? $request->description : $gig->description,
            'status' => $request->has('status') ? $request->status : $gig->status,
            'gig_type' => $request->has('gig_type') ? $request->gig_type : $gig->gig_type,
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : $gig->hourly_rate,
            'is_three_packages_mode_on' => $request->has('is_three_packages_mode_on') ? $request->is_three_packages_mode_on : $gig->is_three_packages_mode_on,
            'is_extra_fast_delivery_on' => $request->has('is_extra_fast_delivery_on') ? $request->is_extra_fast_delivery_on : $gig->is_extra_fast_delivery_on
        ]);

        // Updating Packages
        if ($request->has('packages')) {
            $loopCounter = 3;
            if ($request->is_three_packages_mode_on) {
                $loopCounter = 3;
            } else {
                $loopCounter = 1;
            }
            if ($request->has('create_new_packages')) {
                for ($i = 0; $i < $loopCounter; $i++) {
                    if (!!$request->packages[$i]['title']) {
                        GigPackage::create([
                            'gig_id' => $gigIdFromURL,
                            'title' => $request->packages[$i]['title'] ? $request->packages[$i]['title'] : null,
                            'description' => $request->packages[$i]['description'] ? $request->packages[$i]['description'] : null,
                            'time' => $request->packages[$i]['time'] ? $request->packages[$i]['time'] : null,
                            'revisions' => $request->packages[$i]['revisions'] ? $request->packages[$i]['revisions'] : null,
                            'price' => $request->packages[$i]['price'] ? $request->packages[$i]['price'] : null,
                            'is_hourly' => $request->packages[$i]['is_hourly'] ? $request->packages[$i]['is_hourly'] : null,
                            'extra_fast_delivery_enabled' => $request->packages[$i]['extra_fast_delivery_enabled'],
                            'extra_fast_delivery_time' => $request->packages[$i]['extra_fast_delivery_time'] ? $request->packages[$i]['extra_fast_delivery_time'] : null,
                            'extra_fast_delivery_price' => $request->packages[$i]['extra_fast_delivery_price'] ? $request->packages[$i]['extra_fast_delivery_price'] : null
                        ]);
                    }
                }
            } else if ($request->has('update_packages')) {
                for ($i = 0; $i < $loopCounter; $i++) {
                    $package = GigPackage::where('id', $request->packages[$i]['id'])->first();
                    if ($package) {
                        $package->update([
                            'title' => $request->packages[$i]['title'] ? $request->packages[$i]['title'] : $package->title,
                            'description' => $request->packages[$i]['description'] ? $request->packages[$i]['description'] : $package->description,
                            'time' => $request->packages[$i]['time'] ? $request->packages[$i]['time'] : $package->time,
                            'revisions' => $request->packages[$i]['revisions'] ? $request->packages[$i]['revisions'] : $package->revisions,
                            'price' => $request->packages[$i]['price'] ? $request->packages[$i]['price'] : $package->price,
                            'is_hourly' => $request->packages[$i]['is_hourly'] ? $request->packages[$i]['is_hourly'] : $package->is_hourly,
                            'extra_fast_delivery_enabled' => $request->packages[$i]['extra_fast_delivery_enabled'],
                            'extra_fast_delivery_time' => $request->packages[$i]['extra_fast_delivery_time'] ? $request->packages[$i]['extra_fast_delivery_time'] : $package->extra_fast_delivery_time,
                            'extra_fast_delivery_price' => $request->packages[$i]['extra_fast_delivery_price'] ? $request->packages[$i]['extra_fast_delivery_price'] : $package->extra_fast_delivery_price
                        ]);
                    }
                }
            }
        }

        // Saving Files
        if ($request->has('create_update_gallery_files')) {
            if ($request->has('create_gallery_files')) {
                $this->uploadFilesLoop($gigIdFromURL, 'image', 'image');
                $this->uploadFilesLoop($gigIdFromURL, 'video', 'video');
            } else if ($request->has('update_gallery_files')) {
                $this->updateUploadedFilesLoop($gigIdFromURL, 'image', 'image');
                $this->updateUploadedFilesLoop($gigIdFromURL, 'video', 'video');
            }
        }

        // Updating Requirements
        if ($request->has('requirements')) {
            if ($request->has('create_new_requiremnets')) {
                for ($i = 0; $i < count($request->requirements); $i++) {
                    GigRequirement::create([
                        'gig_id' => $gigIdFromURL,
                        'title' => $request->requirements[$i]['title'] ? $request->requirements[$i]['title'] : null,
                        'description' => $request->requirements[$i]['description'] ? $request->requirements[$i]['description'] : null,
                        'is_required' => $request->requirements[$i]['is_required']
                    ]);
                }
            } else if ($request->has('update_requiremnets')) {
                for ($i = 0; $i < count($request->requirements); $i++) {
                    $requirement = GigRequirement::where('id', $request->requirements[$i]['id'])->first();
                    $requirement->update([
                        'title' => $request->requirements[$i]['title'] ? $request->requirements[$i]['title'] : $requirement->title,
                        'description' => $request->requirements[$i]['description'] ? $request->requirements[$i]['description'] : $requirement->description,
                        'is_required' => $request->requirements[$i]['is_required']
                    ]);
                }
            }
        }

        if ($gig) {
            $gigData = UserGig::where('id', $gigIdFromURL)->with('packages', 'requirements', 'gallery')->first();
            return response()->json(['data' => new UserGigResource($gigData)], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function destroy($id)
    {
        $gig = UserGig::where('id', $id)->delete();
        if ($gig) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function deleteGigsWithStatus(Request $request, $status)
    {
        $gig = UserGig::where('user_id', $request->user()->id)->where('status', $status)->delete();
        if ($gig) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function changeGigStatus(Request $request, $id)
    {

        $gig = UserGig::where('id', $id)->first();
        if (!$gig) {
            return response()->json(['message' => "No Gig Found!"], 500);
        }
        $appSetting = AppSetting::first();

        $status = '';
        if ($request->status == 'publish') {
            if (!!$appSetting->auto_review_gigs) {
                $status = 'publish';
            } else {
                $status = 'pendgin_approval';
            }
        } else {
            $status = $request->status;
        }

        $gig->update([
            'status' => $status
        ]);

        if ($gig) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    private function uploadFilesLoop($gig_id, $file_name, $file_type)
    {
        $request = app('Illuminate\Http\Request');
        $i  = 1;
        while ($request->hasfile($file_name . $i)) {
            // this loop is automate, but images must be uploaded in sequence mean image1, then image2, and so on
            $fileName = null;
            if (request($file_name . $i)) {
                $fileName = request($file_name . $i)->store('gigfiles');
                // Storage::delete($oldImageURL);
            }
            GigGallery::create([
                'gig_id' => $gig_id,
                'file_type' => $file_type,
                'file_number' => $i,
                'file_name' => $fileName
            ]);
            $i++;
        }
    }

    private function updateUploadedFilesLoop($gig_id, $file_name, $file_type)
    {
        $request = app('Illuminate\Http\Request');
        for ($i = 1; $i < 4; $i++) {
            if ($request->hasfile($file_name . $i)) {
                $gallery_file = GigGallery::where('gig_id', $gig_id)->where('file_number', $i)->where('file_type', $file_type)->first();
                if ($gallery_file) {
                    $file = request($file_name . $i)->store('gigfiles');
                    Storage::delete($gallery_file->file_name);

                    $gallery_file->update([
                        'file_name' => $file
                    ]);
                } else {
                    $fileName = null;
                    if (request($file_name . $i)) {
                        $fileName = request($file_name . $i)->store('gigfiles');
                    }
                    GigGallery::create([
                        'gig_id' => $gig_id,
                        'file_type' => $file_type,
                        'file_number' => $i,
                        'file_name' => $fileName
                    ]);
                }
            }
        }
    }
}
