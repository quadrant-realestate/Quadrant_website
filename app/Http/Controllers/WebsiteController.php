<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    //
    public function submitexpressioninterest(Request $request){
        $messages = [
            'email.required' => 'This field is required.',
            'email.email' => 'Incorrect email address.',
            'partner_email.email' => 'Incorrect email format.',
            'partner_email.required_if' => 'This field is required.',
        ];
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "preferred_name" => "required",
            "contact_number" => "required",
            "email" => "required|email|regex:/(.+)@(.+)\.(.+)/i",
            "interested_categories" => "required",
            "skill_level" => "required",
            "playing_partner" => "required",
            "partner_name" => "required_if:playing_partner,yes",
            "contact_number2" => "required_if:playing_partner,yes",
            "partner_email" => "required_if:playing_partner,yes",
        ],$messages);

        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        else{
            DB::table('expressioninterests')->insert([
                "name" => $request->name,
                "preferred_name" => $request->preferred_name,
                "contact_number" => $request->contact_number,
                "dial_code" => $request->dial_code,
                "email" => $request->email,
                "location" => $request->location,
                "country" => $request->country,
                "interested_categories" => json_encode($request->interested_categories),
                "skill_level" => $request->skill_level,
                "playing_partner" =>$request->playing_partner,
                "partner_name" => $request->partner_name,
                "contact_number2" => $request->contact_number2,
                "dial_code2" => $request->dial_code2,
                "partner_email" => $request->partner_email,
                "information_textarea" =>$request->information_textarea,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),    
            ]);
            $body= array(
                "name" =>$request->name,
                "preferred_name" =>$request->preferred_name,
                "contact_number" =>$request->contact_number,
                "dial_code" => $request->dial_code,
                "email" =>$request->email,
                "location" =>$request->location,
                "country" =>$request->country,
                "interested_categories" => $request->interested_categories,
                "skill_level" =>$request->skill_level,
                "playing_partner" =>$request->playing_partner,
                "partner_name" =>$request->partner_name,
                "contact_number2" =>$request->contact_number2,
                "dial_code2" => $request->dial_code2,
                "partner_email" =>$request->partner_email,
                "information_textarea" =>$request->information_textarea,
            );
            $to_name = $request->name;
            $to_email = $request->email;
            Mail::send('website.Email.expression-interest', $body, function($message) use ($to_name, $to_email) {
                $message->to([
                    'forms@strivex.shop' => 'New submission',                
                ])->subject('Expression of Interest – StriveX ChampionShip');              
                $message->from('noreply@strivex.shop', 'New submission from Expression of Interest – StriveX ChampionShip');
            });
                return redirect()->route('expressionofinterestthankyou');
            }
        }

        public function expressionofinterestthankyou(){
            return view('website.expression-of-interest-thankyou');
        }

        public function emailexpression(){
            return view('website.Email.expression-interest');
        }

        public function submitplayersponsorship(Request $request){
            $messages = [
                    'email.required' => 'This field is required.',
                    'email.email' => 'Incorrect email address.',
                ];
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "number" => "required",
                "city" => "required",
                "email" => "required|email|regex:/(.+)@(.+)\.(.+)/i",
                "locality" => "required",
                "primary_discipline" => "required",
                "social_media" => "required",
                "motivation_contribution" => "required",
                "notable_achievements" => "required",
                "sponsorship" => "required",
                "specify" => "required_if:sponsorship,yes",
            ],$messages);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            else{
                DB::table('playersponserships')->insert([
                    "name" => $request->name,
                    "number" => $request->name,
                    "email" => $request->email,
                    "city" => $request->city,
                    "locality" => $request->locality,
                    "be_profile" => $request->be_profile,
                    "current_ranking" => $request->current_ranking,
                    "primary_discipline" => json_encode($request->primary_discipline),
                    "social_media" => json_encode($request->social_media),
                    "motivation_contribution" => $request->motivation_contribution,
                    "notable_achievements" => $request->notable_achievements,
                    "sponsorship" => $request->sponsorship,
                    "specify" => $request->specify,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
    
                $body = array(
                        "name" => $request->name,
                        "number" => $request->name,
                        "email" => $request->email,
                        "city" => $request->city,
                        "locality" => $request->locality,
                        "be_profile" => $request->be_profile,
                        "current_ranking" => $request->current_ranking,
                        "primary_discipline" => $request->primary_discipline,
                        "social_media" => $request->social_media,
                        "motivation_contribution" => $request->motivation_contribution,
                        "notable_achievements" => $request->notable_achievements,
                        "sponsorship" => $request->sponsorship,
                        "specify" => $request->specify,
                    );
                $to_name = $request->name;
                $to_email = $request->email;
                Mail::send('website.Email.player-sponsorship', $body, function($message) use ($to_name, $to_email) {
                    $message->to([
                        'community@strivex.shop' => 'Player Sponsorship',                    
                    ])->subject('StriveX Player Sponsorship Form - StriveX Shop');                
                    $message->from('noreply@strivex.shop', 'New submission from StriveX Player Sponsorship Form');
                });
                return redirect()->route('form-submited');
            }    
        }

        public function volunteerapllicationform(Request $request){
            // dd($request->all());
            $messages = [
                'volunteer_role.required' => 'This field is required.',
                'volunteer_role.min' => 'You may tick more than one.',
                'email.required' => 'This field is required.',
                'email.email' => 'Incorrect email address.',
            ];
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "number" => "required",
                "email" => "required|email|regex:/(.+)@(.+)\.(.+)/i",
                "city" => "required",
                "locality" => "required",
                "availability" => "required",
                "volunteer_role" => "required|min:2",
                "specify" => "required_if:sponsorship,yes",
            ],$messages);        

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }        
            else{
                DB::table('volunteerforms')->insert([
                    "name" => $request->name,
                    "number" => $request->number,
                    "email" => $request->email,
                    "city" => $request->city,
                    "locality" => $request->locality,
                    "availability" => $request->availability,
                    "volunteer_role" => json_encode($request->volunteer_role),
                    "relevant_experience" => $request->relevant_experience,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
                $body = array(
                    "name" => $request->name,
                    "number" => $request->number,
                    "email" => $request->email,
                    "city" => $request->city,
                    "locality" => $request->locality,
                    "availability" => $request->availability,
                    "volunteer_role" => $request->volunteer_role,
                    "relevant_experience" => $request->relevant_experience,
                );
                $to_name = $request->name;
                $to_email = $request->email;
                Mail::send('website.Email.volunteerform', $body, function($message) use ($to_name, $to_email) {
                    $message->to([
                        'community@strivex.shop' => 'Volunteer Form',                    
                    ])->subject('Voluteer Application Form - StriveX Championship');            
                    $message->from('noreply@strivex.shop', 'New submission from Voluteer Application Form - StriveX Championship');
                });            
    
                return redirect()->route('form-submited');
            }    
        }

        

        public function submitcosponsorship(Request $request){
            // dd($request->all());
            $messages = [
                    'email.required' => 'This field is required.',
                    'email.email'    => 'Incorrect email address.',
                ];
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "organisation_name" => "required",
                "website" => "required",
                "email" => "required|email|regex:/(.+)@(.+)\.(.+)/i",
                "number" => "required",
                "interested_sponsorship" => "required",
                "sponsor_event" => "required",
                "achieve_sponsoring" => "required",
            ],$messages);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
        
            else{
                DB::table('formcosponsorships')->insert([
                    "name" => $request->name,
                    "organisation_name" => $request->organisation_name,
                    "website" => $request->website,
                    "email" => $request->email,
                    "interested_sponsorship" => json_encode($request->interested_sponsorship),
                    "other_specify" => $request->other_specify,
                    "number" => $request->number,
                    "sponsor_event" => $request->sponsor_event,
                    "other_sponsored" => $request->other_sponsored,
                    "achieve_sponsoring" => $request->achieve_sponsoring,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);
                $body = array(
                    "name" => $request->name,
                    "organisation_name" => $request->organisation_name,
                    "website" => $request->website,
                    "email" => $request->email,
                    "number" => $request->number,
                    "interested_sponsorship" => $request->interested_sponsorship,
                    "other_specify" => $request->other_specify,
                    "sponsor_event" => $request->sponsor_event,
                    "other_sponsored" => $request->other_sponsored,
                    "achieve_sponsoring" => $request->achieve_sponsoring,
                );
                $to_name = $request->name;
                $to_email = $request->email;
                Mail::send('website.Email.sponsership-interestform', $body, function($message) use ($to_name, $to_email) {
                    $message->to([
                        'community@strivex.shop' => 'Co-Sponsorshop Application Form',            
                    ])->subject('Co-Sponsorshop Application Form – StriveX ChampionShip');        
                    $message->from('noreply@strivex.shop', 'New submission from Co-Sponsorshop Application Form – StriveX ChampionShip');
                });

                return redirect()->route('form-submited');
            }
        }


        public function submitcontact(Request $request){
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "last_name" => "required",
                "email" => "required|email|regex:/(.+)@(.+)\.(.+)/i",
                'confirm_email' => 'required|email|same:email|regex:/(.+)@(.+)\.(.+)/i',
                "contact" => "required",
                "comment" => "required",
                ], [
                'email.required'         => 'This field is required.',
                'email.email'            => 'Oops. Please apply a valid email address.',
                'confirm_email.required' => 'This field is required.',
                'confirm_email.email'    => 'Oops. Please apply a valid email address.',
                'confirm_email.same'     => 'Your emails do not match.',                                            
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }        
            else{
                DB::table('contacts')->insert([
                    "name" => $request->name,
                    "last_name" => $request->last_name,
                    "email" => $request->email,
                    "confirm_email" => $request->confirm_email,
                    "contact" => $request->contact,
                    "comment" => $request->comment,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ]);

                $body = array(
                    "name" => $request->name,
                    "last_name" => $request->last_name,
                    "email" => $request->email,
                    "confirm_email" => $request->confirm_email,
                    "contact" => $request->contact,
                    "comment" => $request->comment,
                );
                $to_name = $request->name;
                $to_email = $request->email;
                Mail::send('website.Email.contact', $body, function($message) use ($to_name, $to_email) {
                    $message->to([
                        'support@strivex.shop' => 'Contact Us',                    
                    ])->subject('Contact Us – StriveX Shop');                
                    $message->from('noreply@strivex.shop', 'New submission from Contact Us – StriveX Shop');
                });
                return redirect()->route('contact-thankyou');
            }
        }

        public function categorydetail($slug)
        {            
            $categorytypedata = DB::table('categories')->where('slug', $slug)->first();

            if($categorytypedata){
                $products = DB::table('products')->where('category_id', $categorytypedata->id)->orderBy('sort_order', 'ASC')->get();
            }else{                
                $categorytypedata = DB::table('types')->where('slug', $slug)->first();
                if (!$categorytypedata) {
                    abort(404);
                }
                $products = DB::table('products')->where('type_id', $categorytypedata->id)->orderBy('sort_order', 'ASC')->get();
            }
            // dd($products);
            return view('website.category-product', compact('categorytypedata', 'products'));
        }

        public function productdetail($slug)
        {
            $product = DB::table('products')->where('slug', $slug)->first();
            return view('website.product-detail' , compact('product'));
        }

        public function shopnow()
        {
            $products = DB::table('products')->orderBy('sort_order', 'ASC')->get();
            $categories = DB::table('categories')->get();
            return view('website.shop-now', compact('products','categories'));
        }

        public function searchproduct(Request $request)
        {
           
            $query = DB::table('products');

            if ($request->searchproduct) {
                $query->where('name', 'LIKE', '%' . $request->searchproduct . '%');
            }

            if ($request->min_price && $request->max_price) {
                $query->whereBetween('total_price', [
                    min($request->min_price, $request->max_price),
                    max($request->min_price, $request->max_price)
                ]);
            }

            if ($request->selectedcategory) {
                $query->whereIn(
                    'category_id',
                    explode(',', $request->selectedcategory)
                );
            }

            $products = $query->get();

            
             return response()->json(
            [
                'success' => true,
                'products' => $products,
            ], 200);
        }


        public function submitcheckout(Request $request)
        {
            

            $order_number = DB::table('order_number')->where('id', 1)->lockForUpdate()->first();
            $orderItems = json_decode($request->orderhistory, true);
            
            $coupondata = json_decode($request->coupondata, true);
            // dd($request->createaccount);
            // if($request->createaccount){
            //     $customer = DB::table('customers')->where('email', $request->email)->first();
            //     if($customer)
            //     {
            //         if($customer->account_exist)
            //         {
            //              // Send mail of Already have an account
            //         }
                   
            //     }else{
            //         // New Account Created
            //     }

            // }else{
            //     dd("Customer donot want to create account");
            // }
           
        $orderid = DB::transaction(function () use ($request, $orderItems, $order_number, $coupondata) {

            $subtotal = 0;
            $discount = 0;
            $shipping = $request->shippingcharges;

            /* ======================
            CUSTOMER
            ====================== */
            DB::table('customers')->insertOrIgnore([
                'first_name' => $request->fname,
                'last_name'  => $request->lname,
                'email'      => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $customer = DB::table('customers')->where('email', $request->email)->first();

            /* ======================
            ADDRESS
            ====================== */
            DB::table('addresses')->insert([
                'customer_id'      => $customer->id,
                'country'          => $request->country,
                'billing_address'  => $request->billing_address,
                'billing_address2' => $request->billing_address2,
                'city'             => $request->city,
                'state'            => $request->state,
                'zipcode'          => $request->zipcode,
                'phone'            => $request->phone,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            /* ======================
            ORDER
            ====================== */
            $order_id = DB::table('orders')->insertGetId([
                'customer_id'    => $customer->id,
                'order_number'   => 'StriveX-' . $order_number->order_number,
                'subtotal'       => 0,
                'discount'       => 0,
                'shipping'       => $shipping,
                'grand_total'    => 0,
                'payment_method' => $request->payment_option,
                'payment_status' => 'pending',
                'order_status'   => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            /* ======================
            ORDER ITEMS
            ====================== */
            foreach ($orderItems as $item) {

                $itemTotal = $item['bundle']['each_price'] * $item['quantity'];

                if (
                    isset($item['attributes']['strung']) &&
                    $item['attributes']['strung'] === 'yes' &&
                    !empty($item['strunging']['price'])
                ) {
                    if($item['strunging']['same'] == 'yes')
                    {
                        $itemTotal += array_sum(explode(',', $item['strunging']['price']))*$item['quantity'];
                    }else{                    
                        $itemTotal += array_sum(explode(',', $item['strunging']['price']));
                    }
                }

                $subtotal += $itemTotal;

                $order_item_id = DB::table('order_items')->insertGetId([
                    'order_id'      => $order_id,
                                'product_id'    => $item['productid'],
                                'product_name'  => $item['productname'],
                                'product_slug'  => $item['productslug'],
                                'category_name' => $item['categoryname'],
                                'type_name'     => $item['typename'],
                                'quantity'      => $item['quantity'],
                                'unit_price'    => $item['bundle']['each_price'],
                                'base_price'    => $item['bundle']['base_price'],
                                'bundle_min'    => $item['bundle']['min'],
                                'bundle_max'    => $item['bundle']['max'],
                                'image'         => $item['image'],
                                'created_at'    => now(),
                                'updated_at'    => now(),
                ]);
                /* ======================
                    SAVE ATTRIBUTES
                    ====================== */
                if (!empty($item['attributes']) && is_array($item['attributes'])) {

                    foreach ($item['attributes'] as $key => $value) {

                        // Skip empty / false values
                        if ($value === null || $value === '' || $value === false) {
                            continue;
                        }

                        // Skip strung (handled separately)
                        if ($key === 'strung') {
                            continue;
                        }

                        DB::table('order_item_attributes')->insert([
                            'order_item_id'   => $order_item_id,
                            'attribute_name'  => $key,
                            'attribute_value' => $value,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                    }
                }

                /* ======================
                    SAVE strungING
                ====================== */
                if (
                    isset($item['attributes']['strung']) &&
                    $item['attributes']['strung'] === 'yes' &&
                    !empty($item['strunging'])
                ) {
                    DB::table('order_item_strunging')->insert([
                        'order_item_id' => $order_item_id,
                        'same_for_all'  => $item['strunging']['same'] ?? 0,
                        'price'         => $item['strunging']['price'] ?? null,
                        'tension_type'  => $item['strunging']['tension_type'] ?? null,
                        'tension_name'  => $item['strunging']['tension_name'] ?? null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }

            }

            if($coupondata){
                if($coupondata['cupon_discount_type'] == 'percentage'){
                    $discount = ($subtotal/100)*$coupondata['coupon_value'];
                }else{
                    $discount = $subtotal - $coupondata['coupon_value'];
                }
            }
            $grandTotal = $subtotal - $discount + $shipping;

            DB::table('orders')
                ->where('id', $order_id)
                ->update([
                    'subtotal'    => $subtotal,
                    'discount'    => $discount,
                    'grand_total' => $grandTotal,
                ]);

            DB::table('order_number')->where('id', 1)->update([
                'order_number' => $order_number->order_number + 1,
            ]);

            return $order_id; // ✅ IMPORTANT
        });

        /* ==========================
            ACCOUNT CREATION LOGIC
        ========================== */

        if ($request->createaccount) {
            $customer = DB::table('customers')->where('email', $request->email)->first();
            if ($customer) {
                // CASE 1: Already has account
                if ($customer->account_exist == 1) 
                {
                    // Send "already have an account" email
                    Mail::send('website.Email.account-exist', compact(
                        'customer'                        
                    ), function ($message) use ($customer) {
                        $message->to($customer->email)
                            ->subject('You already have an account | Strivex Shop')
                            ->from('noreply@strivex.shop', 'Strivex Shop');
                    });
                }
                // CASE 2: Guest → Create account
                else 
                {
                    $password = Str::random(10);

                    DB::table('customers')->where('id', $customer->id)->update([
                            'password'      => Hash::make($password),
                            'account_exist' => 1,
                            'updated_at'    => now(),
                        ]);

                    // Send account activation email
                    Mail::send('website.Email.account-created', compact(
                        'customer' ,'password'                      
                    ), function ($message) use ($customer) {
                        $message->to($customer->email)
                            ->subject('Welcome to Strivex – Your account has been created')
                            ->from('noreply@strivex.shop', 'Strivex Shop');
                    });
                }
            }
        }

        $order = DB::table('orders')->where('id', $orderid)->first();
        $orderItems = DB::table('order_items')->where('order_id', $orderid)->get();
        $address = DB::table('addresses')->where('customer_id', $order->customer_id)->latest()->first();
        $customer = DB::table('customers')->where('id', $order->customer_id)->first();

        $orderItems = DB::table('order_items')
            ->where('order_id', $orderid)
            ->get();

        foreach ($orderItems as $item) {

            // Attributes
            $item->attributes = DB::table('order_item_attributes')
                ->where('order_item_id', $item->id)
                ->get();

            // strunging
            $item->strunging = DB::table('order_item_strunging')
                ->where('order_item_id', $item->id)
                ->first();
        }

        Mail::send('website.Email.checkout-mail', compact(
            'order',
            'orderItems',
            'address',
            'customer'
        ), function ($message) use ($order) {
            $message->to('accounts@strivexventures.co.uk')
                ->subject('You have got a new order: #' . $order->order_number)
                ->from('noreply@strivex.shop', 'Strivex Shop');
        });

        Mail::send('website.Email.thankyou-customer', compact(
            'order',
            'orderItems',
            'address',
            'customer'
        ), function ($message) use ($order,$customer) {
            $message->to($customer->email)
                ->subject('Your Strivex order has been received!')
                ->from('noreply@strivex.shop', 'Strivex Shop');
        });
          

        return response()->json([
                        'success' => true,
                        'order_id' =>   $orderid,
                        'message' => 'Order placed successfully!'
                    ]);
            
           


           


           
            // DB::table('customers')->insert([
            //     "first_name" => $request->fname,
            //     "last_name" => $request->lname,
            //     "email" => $request->email,
            //     "created_at" => now(),
            //     "updated_at" => now(),
            // ]);

            // DB::table('addresses')->insert([
            //     "customer_id" => $customer_id,
            //     "country" => $request->country,
            //     "billing_address" => $request->billing_address,
            //     "billing_address2" => $request->billing_address2,
            //     "city" => $request->city,
            //     "state" => $request->state,
            //     "zipcode" => $request->zipcode,
            //     "phone" => $request->phone,
            //     "created_at" => now(),
            //     "updated_at" => now(),
            // ]);

            // DB::table("orders")->insert([
            //     "customer_id" => $customer_id,
            //     "order_number" => "ORDER-123",
            //     "subtotal" => "",
            //     "discount" => "",
            //     "shipping" => "",
            //     "grand_total" => "",

            //     "coupon_code" => "",
            //     "coupon_type" => "",
            //     "coupon_value" => "",
            //     "coupon_discount" => "",

            //     "payment_method" => "",
            //     "payment_status" => "",

            //     "order_status" => "",
            //     "created_at" => now(),
            //     "updated_at" => now(),                 
            // ]);

            // DB::table("order_items")->insert([
            //     "order_id" => $order_id,
            //     "product_name" => "",
            //     "product_slug" => "",
            //     "category_name" => "",
            //     "type_name" => "",
            //     "quantity" => "",
            //     "unit_price" => "",
            //     "base_price" => "",
            //     "bundle_min" => "",
            //     "bundle_max" => "",
            //     "image" => "",
            //     "created_at" => now(),
            //     "updated_at" => now(),     
            // ]);

            // DB::table("order_item_strunging")->insert([
            //     "order_item_id" => $order_item_id,
            //     "same_for_all" => "",
            //     "price" => "",
            //     "tension_type" => "",
            //     "created_at" => now(),
            //     "updated_at" => now(),     
            // ]);
        }


        public function orderreceived($encodedOrderId)
        {
            $orderId = base64_decode($encodedOrderId);
            // dd(base64_decode($encodedOrderId));

            
            $order = DB::table('orders')->where('id', $orderId)->first();
            $orderItems = DB::table('order_items')->where('order_id', $orderId)->get();
            $address = DB::table('addresses')->where('customer_id', $order->customer_id)->latest()->first();
            $customer = DB::table('customers')->where('id', $order->customer_id)->first();

            $orderItems = DB::table('order_items')
                ->where('order_id', $orderId)
                ->get();

            foreach ($orderItems as $item) {

                // Attributes
                $item->attributes = DB::table('order_item_attributes')
                    ->where('order_item_id', $item->id)
                    ->get();

                // strunging
                $item->strunging = DB::table('order_item_strunging')
                    ->where('order_item_id', $item->id)
                    ->first();
            }

           
            // dd($order);
            return view('website.order-received', compact('order','orderItems','address','customer'));
        }
        public function createPaymentIntent(Request $request)
        {
            Stripe\Stripe::setApiKey(config('stripe.secret'));
            // $request->total_amount
            $amountInCents = (int)((float)$request->total_amount * 100);

            $customer = Stripe\Customer::create(array(
                "name" => $request->first_name,
                'email' => $request->email,
            ));            
                
            // $customer = Customer::create([
            //     'name' => $request->first_name . ' ' . $request->last_name,
            //     'email' => $request->email,
            // ]);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'gbp',
                'customer' => $customer->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        }

        public function applycouponcodee(Request $request)
        {            

            $coupon = DB::table('coupons')->where('coupon_code', $request->couponcode)->where('coupon_status', 'active')->first();

            
            return response()->json(['success' => true, 'coupon'=> $coupon]);
        }

        public function checklogin(Request $request)
        {            

           
            $customer = DB::table('customers')->where('email', $request->email)->where('account_exist', 1)->first();

            /* =========================
            EMAIL NOT FOUND
            ========================= */
            if (!$customer) {
                return response()->json(['success' => false, 'message'=> "No account found with this email."]);
            }

            /* =========================
            PASSWORD NOT SET (Guest Account)
            ========================= */
            if (empty($customer->password)) {
                return response()->json(['success' => false, 'message'=> "This email was used as guest checkout but account not created."]);
            }

            /* =========================
            PASSWORD CHECK
            ========================= */
            if (!Hash::check($request->password, $customer->password)) {
                return response()->json(['success' => false, 'message'=> "The password you entered is incoorect."]);
            }

            /* =========================
            LOGIN SUCCESS
            ========================= */
            session([
                'strivex_customer_logged_in' => true,
                'strivex_customer_id'        => $customer->id,
                'strivex_customer_email'     => $customer->email,
                'strivex_customer_name'      => $customer->first_name
            ]);

            return response()->json(['success' => true, 'customer'=> $customer]);

        }

        public function logincheckauth(Request $request)
        {
            $request->validate([
                    'email'    => 'required|email',
                    'password' => 'required'
                ]);

                $customer = DB::table('customers')->where('email', $request->email)->where('account_exist', 1)->first();

                /* =========================
                EMAIL NOT FOUND
                ========================= */
                if (!$customer) {
                    return back()->withErrors([
                        'errors' => 'No account found with this email.'
                    ]);
                }

                /* =========================
                PASSWORD NOT SET (Guest Account)
                ========================= */
                if (empty($customer->password)) {
                    return back()->withErrors([
                        'errors' => 'This email was used as guest checkout. Please create a password.'
                    ]);
                }

                /* =========================
                PASSWORD CHECK
                ========================= */
                if (!Hash::check($request->password, $customer->password)) {
                    return back()->withErrors([
                        'errors' => 'Invalid password.'
                    ]);
                }

                /* =========================
                LOGIN SUCCESS
                ========================= */
                session([
                    'strivex_customer_logged_in' => true,
                    'strivex_customer_id'        => $customer->id,
                    'strivex_customer_email'     => $customer->email,
                    'strivex_customer_name'      => $customer->first_name
                ]);

                return redirect()->route('my-account')->with('success', 'Logged in successfully');
        }

        public function myaccount()
        {
            if (!Session::has('strivex_customer_logged_in')) {
                // Not logged in
                Session::flush();
                return redirect()->route('account');
            }

            $customerId = session('strivex_customer_id');
            // dd($customerId);
                $orders = DB::table('orders')
                    ->select(
                        'orders.id',
                        'orders.order_number',
                        'orders.order_status',
                        'orders.grand_total',
                        'orders.created_at',
                        DB::raw('SUM(order_items.quantity) as total_items')
                    )
                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.customer_id', $customerId)
                    ->groupBy(
                        'orders.id',
                        'orders.order_number',
                        'orders.order_status',
                        'orders.grand_total',
                        'orders.created_at'
                    )
                    ->orderBy('orders.created_at', 'DESC')
                    ->get();

                $billingaddress = DB::table('addresses')->where('customer_id', $customerId)->latest()->first();
                $customer = DB::table('customers')->where('id', $customerId)->where('account_exist', 1)->first();
                $wishlists = DB::table('wishlists')
                    ->join('products', 'products.id', '=', 'wishlists.product_id')
                    ->where('wishlists.customer_id', session('strivex_customer_id'))
                    ->select('products.*')
                    ->get();
                    // dd($wishlists);

                $strivexchampionship2026 = DB::table('strivexchampionship2026')->where('customer_id', $customerId)->where('payment_status', 1)->orderBy('id', "desc")->get();
                // dd($strivexchampionship2026);
            return view('website.my-account', compact('orders','billingaddress','customer','wishlists','strivexchampionship2026'));
        }

        public function logoutuseraccount()
        {

            session()->forget([
                'strivex_customer_logged_in',
                'strivex_customer_id',
                'strivex_customer_email',
                'strivex_customer_name'
            ]);

             return redirect()->route('account');
        }

        public function customerresetpass(Request $request)
        {           
            $email = $request->email;
            $customer = DB::table('customers')->where('email', $request->email)->where('account_exist', 1)->first();

            /* =========================
            EMAIL NOT FOUND
            ========================= */
            if (!$customer) {
                return back()->withErrors([
                    'errors' => 'No account found with this email.'
                ]);
            }

            $email_en = Crypt::encrypt($email);
        

            Mail::send('website.Email.reset-password', compact(
                        'customer','email_en'                        
                    ), function ($message) use ($customer, $email_en) {
                        $message->to($customer->email)
                            ->subject('Reset your password for Strivex')
                            ->from('noreply@strivex.shop', 'Strivex Shop');
                    });

            return redirect()->back()->with('message', 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.');
        }

        public function forgetpassword($email_en)
        {
            
            $email_de = Crypt::decrypt($email_en);
           
            $customer = DB::table('customers')->where('email', $email_de)->where('account_exist', 1)->first();

            /* =========================
            EMAIL NOT FOUND
            ========================= */
            if (!$customer) {
                return back()->withErrors([
                    'errors' => 'No account found with this email.'
                ]);
            }

           return view('website.reset-password', compact('email_en'));
        }

        public function updatepassword(Request $request)
        {
            $request->validate([
                'email_en' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            $email_de = Crypt::decrypt($request->email_en);
            $customer = DB::table('customers')->where('email', $email_de)->where('account_exist', 1)->first();

            if (!$customer) {
                return back()->withErrors([
                    'errors' => 'No account found with this email.'
                ]);
            }

            DB::table('customers')->where('email', $email_de)->where('account_exist', 1)->update([
                'password' => Hash::make($request->password),
            ]);

            session()->forget([
                'strivex_customer_logged_in',
                'strivex_customer_id',
                'strivex_customer_email',
                'strivex_customer_name'
            ]);
            return redirect()->route('account')->with('message', 'Your password has been changes successfully. Login to review your order history.');

        }

      public function newcustomersignup(Request $request)
        {
            $request->validate([
                'email' => 'required|email:rfc',
            ], [
                'email.required' => 'Email address is required.',
                'email.email'    => 'Please enter a valid email address.',
            ]);

            $password = Str::random(10);

            $customer = DB::table('customers')
                ->where('email', $request->email)
                ->first();

            if ($customer) {
                if ($customer->account_exist) {
                    return back()->withErrors([
                        'email' => 'This email is already registered.'
                    ])->withInput();
                }

                // Update existing row
                DB::table('customers')
                    ->where('email', $request->email)
                    ->update([
                        'password'      => Hash::make($password),
                        'account_exist' => 1,
                        'updated_at'    => now(),
                    ]);
            } else {
                // Insert new row
                DB::table('customers')->insert([
                    'email'         => $request->email,
                    'password'      => Hash::make($password),
                    'account_exist' => 1,
                    'updated_at'    => now(),
                ]);
            }

            $customer = DB::table('customers')->where('email', $request->email)->first();

            // Send account activation email
            Mail::send('website.Email.account-created', compact('customer', 'password'), function ($message) use ($customer) {
                $message->to($customer->email)
                    ->subject('Welcome to Strivex – Your account has been created')
                    ->from('noreply@strivex.shop', 'Strivex Shop');
            });

            return redirect()->back()->with('register', 'Account credentials have been sent to your provided email address. Please check your mail box.');
        }

        public function updateaccountdetails(Request $request)
        {
            // dd($request->all());
            DB::table('customers')->where('email', $request->email)->update([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
            ]);

            return back()->with('message','Account details updated successfully.');
        }

        public function orderdetails($id)
        {
            $order = DB::table('orders')->where('id', $id)->first();

            $items = DB::table('order_items')
                ->where('order_id', $id)
                ->get();

            foreach ($items as $item) {

                $item->attributes = DB::table('order_item_attributes')
                    ->where('order_item_id', $item->id)
                    ->get();

                $item->strunging = DB::table('order_item_strunging')
                    ->where('order_item_id', $item->id)
                    ->first();
            }

            return view('website.include.order-details', compact('order', 'items'));
        }

        public function strivexchampionship2026details($id)
        {
            // dd($id);
            $data = DB::table('strivexchampionship2026')->where('id', $id)->first();
            $customer_email = DB::table('customers')->where('id', $data->customer_id)->first();
            return response()->json(
            [
                'success' => true,
                'data' => $data,
                'customer_email' => $customer_email,
            ], 200);
        }

        public function myaccountviewchampionship2026($id)
        {
            $data = DB::table('strivexchampionship2026')->where('id', $id)->first();
            $customer_email = DB::table('customers')->where('id', $data->customer_id)->first();
            return view('website.myaccount-view-championship-2026', compact('data','customer_email'));
        }

        public function changepartnerrequest(Request $request)
        {
            $cat = $request->query('category'); 
            // dd(session('strivex_customer_id'));
            $form_id = $request->query('id');

            $data = DB::table('strivexchampionship2026')->where('id', $form_id)->where('customer_id', session('strivex_customer_id'))->first();
            if (!$data) {
                return redirect()->route('my-account');
            }
            $customer_email = DB::table('customers')->where('id', session('strivex_customer_id'))->first();
           
            return view('website.change-request',compact('data','cat','customer_email'));


        }

        public function strivexchampionship2026partnerchangepaymentdone(Request $request)
        {
                // dd($request->all());

                $form = DB::table('strivexchampionship2026')->where('id', $request->form_id)->first();
                // dd($form->customer_id);
                $getemail = DB::table('customers')->where('id', $form->customer_id)->first();
                // dd($getemail);
                $category  = $request->category_info;

                $category_data = [
                    'category_info' => $request->category_info ?? null,
                    'name'  => $request->partner_name ?? null,
                    'gender'  => $request->partner_gender ?? null,
                    'email'  => $request->partner_email ?? null,
                    'dial_code'  => $request->dial_code ?? null,
                    'mobile_code'  => $request->mobile_code ?? null,
                    'badminton_england_id'  => $request->partner_badminton_england_id ?? null,
                    'badminton_england_rating'  => $request->partner_badminton_england_rating ?? null,
                    'playing_level'  => $request->partner_playing_level ?? null,
                ];

                // dd($category_data);
                if($category == "Men's Doubles – Intermediate")
                {
                    DB::table('strivexchampionship2026')->where('id', $request->form_id)->update([
                        'men_s_doubles_intermediate' => json_encode($category_data), 
                        'is_men_s_doubles_intermediate_changed' => true,
                        'updated_at' => now(),
                    ]);
                }else if($category == "Men's Doubles – Advanced"){
                    DB::table('strivexchampionship2026')->where('id', $request->form_id)->update([
                        'men_s_doubles_advanced' => json_encode($category_data),
                        'is_men_s_doubles_advanced_changed' => true,                     
                        'updated_at' => now(),
                    ]);
                }else if($category == "Mixed Doubles (Open)"){
                    DB::table('strivexchampionship2026')->where('id', $request->form_id)->update([
                        'mixed_doubles_open' => json_encode($category_data),
                        'is_mixed_doubles_open_changed' => true,
                        'updated_at' => now(),
                    ]);
                }else{
                    DB::table('strivexchampionship2026')->where('id', $request->form_id)->update([
                        'partner_information' => json_encode($category_data),
                        'is_partner_information_changed' => true,
                        'updated_at' => now(),
                    ]);
                }


                // MAIL send here                 
                // Form Submitter 
                // Admin (forms@strivex.shop) payment confirmation mail 
            
                // New Partner
                $data = [
                    'partner_name'  => $request->partner_name ?? '',
                    'partner_email' => $request->partner_email,
                    'category'      => $request->category_info, // array
                    'email'         => $getemail->email,
                    'name'          => $form->name,

                    'en_partner_name'  => Crypt::encrypt($request->partner_name) ?? '',
                    'en_email'      => Crypt::encrypt($request->partner_email),
                    'form_id'       => Crypt::encrypt($request->form_id),
                ];

                Mail::send('website.Email.partner_email', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['partner_email'])
                        ->subject('Partner Email (T&Cs confirmation)')
                        ->from('noreply@strivex.shop', 'StriveX Championship');
                });
            

                

            $data = [
                'name' => $form->name,
                'email' => $getemail->email,
            ];

            Mail::send('website.Email.strivex-championship-2026-partner-change', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])
                    ->subject('StriveX Championship 2026 – Partner Change Request')
                    ->from('noreply@strivex.shop', 'StriveX Championship');
            });
           
        return response()->json(
            [
                'success' => true,
            ], 200);        
    }

    public function changeRequest(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'categories' => 'required|array|min:1|max:3',
        ]);

        // $selectedCategories = $request->categories;
        // $id = $request->id;

        $cat = $request->categories;
        $form_id = $request->id;

        $data = DB::table('strivexchampionship2026')->where('id', $form_id)->where('customer_id', session('strivex_customer_id'))->first();
        if (!$data) {
            return redirect()->route('my-account');
        }
        $customer_email = DB::table('customers')->where('id', session('strivex_customer_id'))->first();
        return view('website.strivex-championship-2026-add-category', compact(
            'data','cat','customer_email'
        ));
         return view('website.strivex-championship-2026-add-category',compact('data','cat','customer_email'));
    }

    public function myaccountstrivexchampionship2026addcategory(Request $request)
{
    // dd($request->all());
    $categoryInfo = $request->category_info ?? [];
    $partnerNames = $request->partner_name ?? [];
    $partnerGenders = $request->partner_gender ?? [];
    $partnerEmails = $request->partner_email ?? [];
    $mobileCodes = $request->mobile_code ?? [];
    $dialCodes = $request->dial_code ?? [];
    $badmintonIds = $request->partner_badminton_england_id ?? [];
    $badmintonRatings = $request->partner_badminton_england_rating ?? [];
    $playingLevels = $request->partner_playing_level ?? [];

    $form_id = $request->form_id;

    $data = DB::table('strivexchampionship2026')
        ->where('id', $form_id)
        ->where('customer_id', session('strivex_customer_id'))
        ->first();

    if (!$data) {
        return redirect()->route('my-account');
    }
    $customer = DB::table('customers')->where('id', $data->customer_id)->first();
    // dd($customer);
    $oldCategories = json_decode($data->categories, true) ?? [];
    $mergedCategories = array_values(array_unique(array_merge($oldCategories, $categoryInfo)));

    $categoryOrder = [
        "Men's Singles (Open)",
        "Men's Doubles – Intermediate",
        "Men's Doubles – Advanced",
        "Mixed Doubles (Open)",
    ];

    $finalCategories = [];
    foreach ($categoryOrder as $category) {
        if (in_array($category, $mergedCategories, true)) {
            $finalCategories[] = $category;
        }
    }

    // Prepare partner data for each doubles category
    $men_s_doubles_intermediate = null;
    $men_s_doubles_advanced = null;
    $mixed_doubles_open = null;
    // dd($categoryInfo);
    
     $mailCategories = $categoryInfo;
    $single_category = false;
if (!empty($mailCategories) && ($key = array_search("Men's Singles (Open)", $mailCategories)) !== false) {
    unset($mailCategories[$key]);
    $mailCategories = array_values($mailCategories); // reindex
    $single_category = true;
}

    foreach ($mailCategories as $index => $category) {
        $partnerData = [
            'category_info' => $category,
            'name' => $partnerNames[$index] ?? null,
            'gender' => $partnerGenders[$index] ?? null,
            'email' => $partnerEmails[$index] ?? null,
            'mobile_code' => $mobileCodes[$index] ?? null,
            'dial_code' => $dialCodes[$index] ?? null,
            'badminton_england_id' => $badmintonIds[$index] ?? null,
            'badminton_england_rating' => $badmintonRatings[$index] ?? null,
            'playing_level' => $playingLevels[$index] ?? null,
        ];

        switch ($category) {
            case "Men's Doubles – Intermediate":
                $men_s_doubles_intermediate = $partnerData;
                break;
            case "Men's Doubles – Advanced":
                $men_s_doubles_advanced = $partnerData;
                break;
            case "Mixed Doubles (Open)":
                $mixed_doubles_open = $partnerData;
                break;
        }
    }
    //   dd($partnerData);
    // Prepare DB update array
    $updateData = [
        'categories' => json_encode($finalCategories),
        'updated_at' => now(),
    ];

    if ($men_s_doubles_intermediate) {
        $updateData['men_s_doubles_intermediate'] = json_encode($men_s_doubles_intermediate);
    }
    if ($men_s_doubles_advanced) {
        $updateData['men_s_doubles_advanced'] = json_encode($men_s_doubles_advanced);
    }
    if ($mixed_doubles_open) {
        $updateData['mixed_doubles_open'] = json_encode($mixed_doubles_open);
    }
                // dd($men_s_doubles_advanced);
   
    $adminMailData = [
        'name'                        => $data->name,
        'gender'                      => $data->gender,
        'badminton_england_id'        => $data->badminton_england_id,
        'badminton_england_rating'    => $data->badminton_england_rating,
        'playing_level'               => $data->playing_level,
        'mobile_number'               => $data->mobile_number,
        'dial_code'                   => $data->dial_code,        
        'categories'                  => $mailCategories ?? [],
        'single_category'             => $single_category,
        'men_s_doubles_intermediate'  => $men_s_doubles_intermediate ?? [],
        'men_s_doubles_advanced'      => $men_s_doubles_advanced ?? [],
        'mixed_doubles_open'          => $mixed_doubles_open ?? [],
        'agree_box1'                  => 1,
        'agree_box2'                  => 1,
        'payment_price'               => $request->total_price,
    ];
                // dd($adminMailData);
    Mail::send('website.Email.strivex-championship-2026-payment-form-add-category', ['data' => $adminMailData], function ($message) {
        $message->to('forms@strivex.shop')
                ->subject('Strivex Championship 2026 | Strivex Shop')
                ->from('noreply@strivex.shop', 'Strivex Shop');
    });


    DB::table('strivexchampionship2026')
        ->where('id', $form_id)
        ->update($updateData);

    // Send partner emails
    foreach ($categoryInfo as $index => $category) {
        $partnerName = $partnerNames[$index] ?? '';
        $partnerEmail = $partnerEmails[$index] ?? '';

        if (!$partnerEmail) continue; // skip if no email

        $mailData = [
            'partner_name'  => $partnerName,
            'partner_email' => $partnerEmail,
            'category'      => $category,
            'email'         => $customer->email ?? '', // assuming customer's email
            'name'          => $data->name ?? '',  // assuming customer's name
            'en_partner_name' => Crypt::encrypt($partnerName),
            'en_email'       => Crypt::encrypt($partnerEmail),
            'form_id'        => Crypt::encrypt($form_id),
        ];

        Mail::send('website.Email.partner_email', ['data' => $mailData], function ($message) use ($mailData) {
            $message->to($mailData['partner_email'])
                    ->subject('Partner Email (T&Cs confirmation)')
                    ->from('noreply@strivex.shop', 'StriveX Championship');
        });
    }

    $data = [
            'name' => $data->name,
            'email' => $customer->email,
        ];

    Mail::send('website.Email.strivex-championship-2026-payment', ['data' => $data], function ($message) use ($data) {
        $message->to($data['email'])
            ->subject('StriveX Championship 2026 – Entry Received')
            ->from('noreply@strivex.shop', 'StriveX Championship');
    });
    
    // dd("Code working");

     


    
    return response()->json(
        [
            'success' => true,
        ], 200);

                
    return redirect()->route('my-account')->with('success', 'Categories and partner info updated successfully.');
}


        // public function addstrivexchampionship2026category(Request $request)
        // {
        //     $cat = "Men's Doubles – Intermediate";

        //     $form_id = $request->query('id');

        //     $data = DB::table('strivexchampionship2026')->where('id', $form_id)->where('customer_id', session('strivex_customer_id'))->first();
        //     if (!$data) {
        //         return redirect()->route('my-account');
        //     }
        //     $customer_email = DB::table('customers')->where('id', session('strivex_customer_id'))->first();
            
        //     return view('website.strivex-championship-2026-add-category',compact('data','cat','customer_email'));
        // }

        public function shareCart(Request $request)
        {
            $request->validate([
                'cart' => 'required'
            ]);

            $token = Str::random(12);

            DB::table('shared_carts')->insert([
                'token'      => $token,
                'cart_json'  => $request->cart,
                'expires_at' => now()->addDays(7)
            ]);

            return response()->json([
                'url' => url('/cart/share/' . $token)
            ]);
        }

        /* ===============================
            LOAD SHARED CART
        =============================== */
        public function loadSharedCart($token)
        {
            $cart = DB::table('shared_carts')
                ->where('token', $token)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
                })
                ->first();

            if (!$cart) {
                abort(404, 'Cart expired or invalid');
            }

            return view('website.shared-cart', [
                'cartJson' => $cart->cart_json
            ]);
        }
/* =========================
       ADD TO WISHLIST
    ========================= */
    public function addwishlist(Request $request)
    {
        if (!session('strivex_customer_logged_in')) {
            return response()->json([
                'status' => 'login_required'
            ]);
        }

        $request->validate([
            'product_id' => 'required'
        ]);

        $exists = DB::table('wishlists')
            ->where('customer_id', session('strivex_customer_id'))
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'exists',
                'message' => 'Already in wishlist'
            ]);
        }

        DB::table('wishlists')->insert([
            'customer_id' => session('strivex_customer_id'),
            'product_id'  => $request->product_id,
            'created_at'  => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Added to wishlist'
        ]);
    }

    /* =========================
       REMOVE WISHLIST
    ========================= */
    public function removewishlist(Request $request)
    {
        DB::table('wishlists')
            ->where('customer_id', session('strivex_customer_id'))
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json([
            'status' => 'removed'
        ]);
    }

    public function compareproduct($slug)
    {
        
        $product = DB::table('products')->where('slug', $slug)->first();
        $allproducts =  DB::table('products')->where('category_id', $product->category_id)->get();

        return view('website.compare-product',compact('product','allproducts'));
    }

    public function compareproductother($id)
    {
        $product =DB::table('products')->where('id', $id)->first();
        
        $type = DB::table('types')->where('id', $product->type_id)->first();
        $category = DB::table('categories')->where('id', $product->category_id)->first();
        
        $tabs = json_decode($product->tab, true) ?? [];
        $color_sizes = json_decode($product->color_sizes, true) ?? [];
        $allSizes = ['xs','s','m','l','xl'];
        
        return response()->json([
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => asset(json_decode($product->images, true)[0] ?? ''),
            'type' => $type->name ?? '',
            'type_slug' => $type->slug ?? '',
            'category' => $category->name ?? '',
            'category_slug' => $category->slug ?? '',
            'price' => '£'.$product->total_price,
            'description' => $product->meta_description,
            'techspecs' => collect($tabs)->firstWhere('tabname', 'Quick Specs')['tabdetail'] ?? '',
            'colors' => $color_sizes,
            'sizes' => $allSizes,
            'rating' => '85%', // optionally dynamic
            'stock' => 'In Stock', // optionally dynamic
            'cart_url' => url('product/'.$product->slug)
        ]);
    }


   public function strivexchampionship2026(Request $request)
    {
        // dd($request->all());
       
        // 1️⃣ Count doubles categories
        $categories = collect($request->input('categories', []));

        $doublesCount = $categories->filter(function ($category) {
            return stripos($category, 'doubles') !== false;
        })->count();

        $hasMensDoublesIntermediate = $categories->contains(function ($cat) {
            return stripos($cat, "Men's Doubles – Intermediate") !== false;
        });

        $showMensDoublesAdvanced = $categories->contains(function ($cat) {
            return stripos($cat, "Men's Doubles – Advanced") !== false;
        });

        $showMixedDoubles = $categories->contains(function ($cat) {
            return stripos($cat, "Mixed Doubles (Open)") !== false;
        });

        $showPartnerInformation = $categories->contains(function ($cat) {
            return stripos($cat, "Partner Information") !== false;
        });
        // dd($hasMensDoublesIntermediate);
        // dd($doublesCount);
        // 2️⃣ Base rules
        $rules = [
            'name' => 'required|string',
            'gender' => 'required',
            'email' => 'required|email',
            // 'badminton_england_rating' => 'required',
            'mobile_number' => 'required',
            'playing_level' => 'required',
            'full_name' => 'required',
            'relationship' => 'required',
            'mobile_number_emer' => 'required',
            'categories' => 'required|array|min:1|max:3',
            'agree_box1' => 'accepted',
            // 'agree_box2' => 'accepted',
        ];

        // 3️⃣ CONDITIONAL RULE (🔥 this is what you asked for)
        if ($doublesCount >= 2) {
            $rules['partnersame'] = 'required|in:yes,no';
        }

        $requireIndividualPartners = $doublesCount < 2 || ($doublesCount >= 2 && $request->partnersame === 'no');

        // 🔴 Apply partner validation ONLY when conditions match
        if ($hasMensDoublesIntermediate && $requireIndividualPartners) {

            $rules['partner_name1'] = 'required|string|max:255';
            $rules['partner_gender1'] = 'required|string';
            $rules['partner_email1'] = 'required|email';
            $rules['mobile_code1'] = 'required|string';
            // $rules['partner_badminton_england_rating1'] = 'required|string';
            $rules['partner_playing_level1'] = 'required|string';
        }

        if ($showMensDoublesAdvanced && $requireIndividualPartners) {

            $rules['partner_name2'] = 'required|string|max:255';
            $rules['partner_gender2'] = 'required|string';
            $rules['partner_email2'] = 'required|email';
            $rules['mobile_code2'] = 'required|string';
            // $rules['partner_badminton_england_rating2'] = 'required|string';
            $rules['partner_playing_level2'] = 'required|string';
        }

        if ($showMixedDoubles && $requireIndividualPartners) {

            $rules['partner_name3'] = 'required|string|max:355';
            $rules['partner_gender3'] = 'required|string';
            $rules['partner_email3'] = 'required|email';
            $rules['mobile_code3'] = 'required|string';
            // $rules['partner_badminton_england_rating3'] = 'required|string';
            $rules['partner_playing_level3'] = 'required|string';
        }

        if ($doublesCount >= 2 && $request->partnersame === 'yes') {

            $rules['partner_name4'] = 'required|string|max:355';
            $rules['partner_gender4'] = 'required|string';
            $rules['partner_email4'] = 'required|email';
            $rules['mobile_code4'] = 'required|string';
            // $rules['partner_badminton_england_rating4'] = 'required|string';
            $rules['partner_playing_level4'] = 'required|string';
        }

        // 4️⃣ Validator
        $validator = Validator::make($request->all(), $rules);

        // 5️⃣ Handle validation
        if ($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Validation passed
        // dd('Validator Pass');

        $categoryPrices = [
            "Men's Singles (Open)" => 50,
            "Men's Doubles – Intermediate" => 60,
            "Men's Doubles – Advanced" => 60,
            "Mixed Doubles (Open)" => 60,
        ];

        $totalPrice = 0;
        foreach ($request->categories as $cat) {
            if (isset($categoryPrices[$cat])) {
                $totalPrice += $categoryPrices[$cat];
            }
        }
        // dd($request->partnersame);

        $hasIntermediate = ((in_array("Men's Doubles – Intermediate", $request->categories)) && ($request->partnersame === 'no' || $request->partnersame === null));
        $men_s_doubles_intermediate = [
            'category_info' => $hasIntermediate ? $request->category_info1 : null,
            'name'  => $hasIntermediate ? $request->partner_name1 : null,
            'gender'  => $hasIntermediate ? $request->partner_gender1 : null,
            'email'  => $hasIntermediate ? $request->partner_email1 : null,
            'dial_code'  => $hasIntermediate ? $request->dial_code3 : null,
            'mobile_code'  => $hasIntermediate ? $request->mobile_code1 : null,
            'badminton_england_id'  => $hasIntermediate ? $request->partner_badminton_england_id1 : null,
            'badminton_england_rating'  => $hasIntermediate ? $request->partner_badminton_england_rating1 : null,
            'playing_level'  => $hasIntermediate ? $request->partner_playing_level1 : null,
        ];

        $hasAdvanced = ((in_array("Men's Doubles – Advanced", $request->categories)) && ($request->partnersame === 'no' || $request->partnersame === null));
        $men_s_doubles_advanced = [
            'category_info' => $hasAdvanced ? $request->category_info2 : null,
            'name'  => $hasAdvanced ? $request->partner_name2 : null,
            'gender'  => $hasAdvanced ? $request->partner_gender2 : null,
            'email'  => $hasAdvanced ? $request->partner_email2 : null,
            'dial_code'  => $hasAdvanced ? $request->dial_code4 : null,
            'mobile_code'  => $hasAdvanced ? $request->mobile_code2 : null,
            'badminton_england_id'  => $hasAdvanced ? $request->partner_badminton_england_id2 : null,
            'badminton_england_rating'  => $hasAdvanced ? $request->partner_badminton_england_rating2 : null,
            'playing_level'  => $hasAdvanced ? $request->partner_playing_level2 : null,
        ];

        $hasMixedDoubles = ((in_array("Mixed Doubles (Open)", $request->categories)) && ($request->partnersame === 'no' || $request->partnersame === null));
        $mixed_doubles_open = [
            'category_info' => $hasMixedDoubles ? $request->category_info3 : null,
            'name'  => $hasMixedDoubles ? $request->partner_name3 : null,
            'gender'  => $hasMixedDoubles ? $request->partner_gender3 : null,
            'email'  => $hasMixedDoubles ? $request->partner_email3 : null,
            'dial_code'  => $hasMixedDoubles ? $request->dial_code5 : null,
            'mobile_code'  => $hasMixedDoubles ? $request->mobile_code3 : null,
            'badminton_england_id'  => $hasMixedDoubles ? $request->partner_badminton_england_id3 : null,
            'badminton_england_rating'  => $hasMixedDoubles ? $request->partner_badminton_england_rating3 : null,
            'playing_level'  => $hasMixedDoubles ? $request->partner_playing_level3 : null,
        ];

        $allPartners = ($request->partnersame === "yes");
        $partner_information = [
            'category_info' => $allPartners ? $request->category_info4 : null,
            'name'  => $allPartners ? $request->partner_name4 : null,
            'gender'  => $allPartners ? $request->partner_gender4 : null,
            'email'  => $allPartners ? $request->partner_email4 : null,
            'dial_code'  => $allPartners ? $request->dial_code6 : null,
            'mobile_code'  => $allPartners ? $request->mobile_code4 : null,
            'badminton_england_id'  => $allPartners ? $request->partner_badminton_england_id4 : null,
            'badminton_england_rating'  => $allPartners ? $request->partner_badminton_england_rating4 : null,
            'playing_level'  => $allPartners ? $request->partner_playing_level4 : null,
        ];

        // dd($totalPrice);
        DB::table('customers')->insertOrIgnore([
            'first_name' => $request->name,
            // 'last_name'  => $request->lname,
            'email'      => $request->email,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customer = DB::table('customers')->where('email', $request->email)->first();

        // Insert into DB
        $strivexchampionship2026_id = DB::table('strivexchampionship2026')->insertGetId([
            'name' => $request->name,
            'gender' => $request->gender,
            'badminton_england_id' => $request->badminton_england_id ?? null,
            'badminton_england_rating' => $request->badminton_england_rating,
            'playing_level' => $request->playing_level,
            'customer_id' => $customer->id,
            'mobile_number' => $request->mobile_number,
            'dial_code' => $request->dial_code,
            'emergency_name' => $request->full_name,
            'emergency_relationship' => $request->relationship,
            'emergency_mobile' => $request->mobile_number_emer,
            'emergency_dial_code' => $request->dial_code2,
            'categories' => json_encode($request->categories),

            'partnersame' => $request->partnersame,
            'men_s_doubles_intermediate' => json_encode($men_s_doubles_intermediate),
            'men_s_doubles_advanced' => json_encode($men_s_doubles_advanced),
            'mixed_doubles_open' => json_encode($mixed_doubles_open),
            'partner_information' => json_encode($partner_information),

            'is_men_s_doubles_intermediate_changed' => $hasIntermediate ? false : null,
            'is_men_s_doubles_advanced_changed' => $hasAdvanced ? false : null,
            'is_mixed_doubles_open_changed' => $hasMixedDoubles ? false : null,
            'is_partner_information_changed' => $allPartners ? false : null,

            'agree_box1' => $request->has('agree_box1') ? 1 : 0,
            'agree_box2' => $request->has('agree_box2') ? 1 : 0,
            'payment_status' => 0,
            'payment_mode' => '',
            'payment_price' => $totalPrice,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $mailData = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'badminton_england_id' => $request->badminton_england_id,
            'badminton_england_rating' => $request->badminton_england_rating,
            'playing_level' => $request->playing_level,
            'customer_id' => $customer->id,
            'mobile_number' => $request->mobile_number,
            'dial_code' => $request->dial_code,

            'emergency_name' => $request->full_name,
            'emergency_relationship' => $request->relationship,
            'emergency_mobile' => $request->mobile_number_emer,
            'emergency_dial_code' => $request->dial_code2,

            'categories' => $request->categories, // keep as array

            'partnersame' => $request->partnersame,
            'men_s_doubles_intermediate' => $men_s_doubles_intermediate,
            'men_s_doubles_advanced' => $men_s_doubles_advanced,
            'mixed_doubles_open' => $mixed_doubles_open,
            'partner_information' => $partner_information,    // keep as array

            'agree_box1' => $request->has('agree_box1') ? 1 : 0,
            'agree_box2' => $request->has('agree_box2') ? 1 : 0,

            'payment_price' => $totalPrice,
        ];

        Mail::send('website.Email.strivex-championship-2026-payment-form',['data' => $mailData],
            function ($message) {
                $message->to('forms@strivex.shop')
                    ->subject('Strivex Championship 2026 | Strivex Shop')
                    ->from('noreply@strivex.shop', 'Strivex Shop');
            }
        );

        $email_en = Crypt::encrypt($request->email);
        $id =  Crypt::encrypt($strivexchampionship2026_id);
        return redirect()->route('strivexchampionship2026payment', ['email' => $email_en, 'id'    => $id]);
    }

    public function strivexchampionship2026payment($email, $id)
    {
        $email_de = Crypt::decrypt($email);
        $id = Crypt::decrypt($id);
        $customer = DB::table('customers')->where('email',  $email_de)->first();
        $getformdata = DB::table('strivexchampionship2026')->where('id', $id)->where('customer_id',  $customer->id)->first();      
    
        return view('website.strivex-championship-2026-payment', compact('getformdata','customer','id'));

        // $getdata = DB::table('strivexchampionship2026')->where('id', $id)->first();       
        // $getemail = DB::table('customers')->where('id', $getdata->customer_id)->first();
        // $partners = json_decode($getdata->partners);       
        // $partneremails = [];
        // if ($partners) {
        //     foreach ($partners as $category => $partner) {
        //         if (!empty($partner->partner_email)) {
        //             $data = [
        //                 'partner_name'  => $partner->partner_name,
        //                 'partner_email' => $partner->partner_email,
        //                 'categories'    => json_decode($getdata->categories, true), // array is better
        //                 'email'         => $getemail->email,
        //                 'name'          => $getdata->name,
        //             ];

        //             Mail::send('website.Email.partner_email', ['data' => $data], function ($message) use ($data) {
        //                 $message->to($data['partner_email'])   // ✅ FIX HERE
        //                     ->subject('Partner Email (T&Cs confirmation)')
        //                     ->from('noreply@strivex.shop', 'StriveX Championship');
        //             });
        //         }
        //     }
        // }

        // $data = [
        //     'name' => $getdata->name,
        //     'email' => $getemail->email,
        // ];

        // Mail::send('website.Email.strivex-championship-2026-payment', ['data' => $data], function ($message) use ($data) {
        //     $message->to($data['email'])
        //         ->subject('StriveX Championship 2026 – Entry Received')
        //         ->from('noreply@strivex.shop', 'StriveX Championship');
        // });

        // dd("Email Sent");
    }

    public function strivexchampionship2026paymentdone(Request $request)
    {
        $create_account = $request->create_account;
        if ($create_account) {
            $getdata = DB::table('strivexchampionship2026')->where('id', $request->form_id)->first();       
            $customer = DB::table('customers')->where('id', $getdata->customer_id)->first();               
            // CASE 1: Already has account
            if ($customer->account_exist == 1) 
            {
                // Send "already have an account" email
                Mail::send('website.Email.account-exist', compact(
                    'customer'                        
                ), function ($message) use ($customer) {
                    $message->to($customer->email)
                        ->subject('You already have an account | Strivex Shop')
                        ->from('noreply@strivex.shop', 'Strivex Shop');
                });
            }
            // CASE 2: Guest → Create account
            else 
            {
                $password = Str::random(10);

                DB::table('customers')->where('id', $customer->id)->update([
                    'password'      => Hash::make($password),
                    'account_exist' => 1,
                    'updated_at'    => now(),
                ]);

                // Send account activation email
                Mail::send('website.Email.account-created', compact(
                    'customer' ,'password'                      
                ), function ($message) use ($customer) {
                    $message->to($customer->email)
                        ->subject('Welcome to Strivex – Your account has been created')
                        ->from('noreply@strivex.shop', 'Strivex Shop');
                });
            }
        }

        DB::table('strivexchampionship2026')->where('id', $request->form_id)->update([
            'payment_status' => 1,
            'payment_mode' => 'Debit/Credit Card',
            'updated_at' => now(),
        ]);

        $getdata = DB::table('strivexchampionship2026')->where('id', $request->form_id)->first();       
        $getemail = DB::table('customers')->where('id', $getdata->customer_id)->first();

        $men_s_doubles_intermediate = json_decode($getdata->men_s_doubles_intermediate, true);
        $men_s_doubles_advanced     = json_decode($getdata->men_s_doubles_advanced, true);
        $mixed_doubles_open         = json_decode($getdata->mixed_doubles_open, true);
        $partner_information        = json_decode($getdata->partner_information, true);

        $partners = [
            $men_s_doubles_intermediate,
            $men_s_doubles_advanced,
            $mixed_doubles_open,
            $partner_information,
        ];

        foreach ($partners as $partner) {
            if (!empty($partner) && !empty($partner['email'])) {

                $data = [
                    'partner_name'  => $partner['name'] ?? '',
                    'partner_email' => $partner['email'],
                    'category'      => $partner['category_info'], // array
                    'email'         => $getemail->email,
                    'name'          => $getdata->name,

                    'en_partner_name'  => Crypt::encrypt($partner['name']) ?? '',
                    'en_email'      => Crypt::encrypt($partner['email']),
                    'form_id'       => Crypt::encrypt($request->form_id),
                ];

                Mail::send('website.Email.partner_email', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['partner_email'])
                        ->subject('Partner Email (T&Cs confirmation)')
                        ->from('noreply@strivex.shop', 'StriveX Championship');
                });
            }
        }

        $data = [
            'name' => $getdata->name,
            'email' => $getemail->email,
            'amount' => $getdata->payment_price,
            'categories' => $getdata->categories
        ];

        Mail::send('website.Email.strivex-championship-2026-payment', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('StriveX Championship 2026 – Entry Received')
                ->from('noreply@strivex.shop', 'StriveX Championship');
        });

        Mail::send('website.Email.strivex-championship-2026-payment-admin', ['data' => $data], function ($message) use ($data) {
            $message->to('accounts@strivexventures.co.uk')
                ->subject('StriveX Championship 2026 – Payment Received')
                ->from('noreply@strivex.shop', 'StriveX Championship');
        });
       
        return response()->json(
            [
                'success' => true,
            ], 200);
        
    }

    public function accepttermsandconditions($email, $id, $name)
    {
        $de_email = Crypt::decrypt($email);       
        $form_id = Crypt::decrypt($id);
        $de_name = Crypt::decrypt($name);
        
        return view('website.accept-terms-and-conditions', compact('de_email','form_id','de_name'));
    }

    public function accepttermsconditions(Request $request)
    {
        // $request->form_id
        $request->validate([
            'agree_box2' => 'required|accepted',
        ], [
            'agree_box2.accepted' => 'You must accept the Terms & Conditions to proceed.',
        ]);
        $data = DB::table('strivexchampionship2026')->where('id', $request->form_id)->first();
        
        $customers = DB::table('customers')->where('id', $data->customer_id)->first();
       
        $customeremail = $customers->email;

        $data = [
            'name' => $data->name,
            'email' => $customeremail,
            'accepted_person_name' => $request->de_name,
        ];
        
        Mail::send('website.Email.strivex-championship-2026-partner-accept', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('Partner Accepted (T&Cs confirmation)')
                ->from('noreply@strivex.shop', 'StriveX Championship');
        });

        $data = [           
            'form_submitter_email' => $customeremail,
            'partner_email' => $request->email,
        ];
        
        Mail::send('website.Email.strivex-championship-2026-admin', ['data' => $data], function ($message) use ($data) {
            $message->to("forms@strivex.shop")
                ->subject('Partner Accepted (T&Cs confirmation)')
                ->from('noreply@strivex.shop', 'StriveX Championship');
        });

        return redirect()->route('thankyoustrivexchampionship2026partner');
    }
        
    }
