<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shared\MembershipPlanResource;
use App\Model\Shared\MembershipPlan;
use App\User;
use Illuminate\Http\Request;

class ApiMembershipPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = MembershipPlan::all();
        return response()->json(['data' => MembershipPlanResource::collection($items)], 200);
    }

    public function getSpecificUserPlans($usertype)
    {
        $items = MembershipPlan::where('plan_type', $usertype)->get();
        return response()->json(['data' => MembershipPlanResource::collection($items)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'plan_type' => 'required',
            'can_offer_requests' => 'required',
            'bids_allowed' => 'required',
            'commission_per_order' => 'required',
            'can_post_request' => 'required',
            'post_premium_requests' => 'required',
            'show_primium_request' => 'required',
            'can_add_gigs' => 'required'
        ]);

        $result = MembershipPlan::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'plan_type' => $request->plan_type,
            'can_offer_requests' => $request->can_offer_requests,
            'bids_allowed' => $request->bids_allowed,
            'commission_per_order' => $request->commission_per_order,
            'can_post_request' => $request->can_post_request,
            'post_premium_requests' => $request->post_premium_requests,
            'show_primium_request' => $request->show_primium_request,
            'can_add_gigs' => $request->can_add_gigs
        ]);

        if ($result) {
            return response()->json(['data' => 'Created!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = MembershipPlan::where('id', $id)->first();
        if ($item) {
            return response()->json(['data' => new MembershipPlanResource($item)], 200);
        } else {
            return response()->json(['message' => "Not Found!"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'plan_type' => 'required',
            'can_offer_requests' => 'required',
            'bids_allowed' => 'required',
            'commission_per_order' => 'required',
            'can_post_request' => 'required',
            'post_premium_requests' => 'required',
            'show_primium_request' => 'required',
            'can_add_gigs' => 'required'
        ]);

        $result = MembershipPlan::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'plan_type' => $request->plan_type,
            'can_offer_requests' => $request->can_offer_requests,
            'bids_allowed' => $request->bids_allowed,
            'commission_per_order' => $request->commission_per_order,
            'can_post_request' => $request->can_post_request,
            'post_premium_requests' => $request->post_premium_requests,
            'show_primium_request' => $request->show_primium_request,
            'can_add_gigs' => $request->can_add_gigs
        ]);

        if ($result) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = MembershipPlan::where('id', $id)->first();
        if ($item) {
            $result = $item->delete();
            if ($result) {
                return response()->json(['data' => 'Deleted!'], 200);
            } else {
                return response()->json(['message' => "Error Occured!"], 500);
            }
        } else {
            return response()->json(['message' => "Not Found!"], 500);
        }
    }

    public function updateMembershipPlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
            'plan_type' => 'required'
        ]);
        
        if ($request->plan_type != 'seller' && $request->plan_type != 'buyer') {
            return response()->json(['message' => "Plan Type shoult be 'seller' or 'buyer'."], 500);
        }

        $plan = MembershipPlan::where('id', $request->plan_id)->first();
        if (!$plan) {
            return response()->json(['message' => "Not Found!"], 500);
        } else {
            if ($request->plan_type == 'seller') {
                User::where('id', $request->user()->id)->update([
                    'seller_plan_id' => $request->plan_id
                ]);
            }
            else if ($request->plan_type == 'buyer') {
                User::where('id', $request->user()->id)->update([
                    'buyer_plan_id' => $request->plan_id
                ]);
            }
            else {
                return response()->json(['message' => "Error Occured!"], 500);
            }
            return response()->json(['data' => "Updated!"], 200);
        }
    }
}
