<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::where('is_active', true)
            ->with('tags')
            ->latest()
            ->get();
        $tags = Tag::orderBy('name')
            ->get();
        if (request()->has('s')) {
            $query = strtolower(request()->get('s'));
            $listings = $listings->filter(function($listing) use($query) {
                if (Str::contains(strtolower($listing->title), $query)) {
                    return true;
                }

                if (Str::contains(strtolower($listing->company), $query)) {
                    return true;
                }

                if (Str::contains(strtolower($listing->location), $query)) {
                    return true;
                }
                return false;
            });

            if (request()->has('tag')) {
                $tag = strtolower(request()->get('tag'));
                $listings = $listings->filter(function($listing) use($tag) {
                    return $listing->tags->contains('slug', $tag);
                });
            }
        }
        return view('listings.index', compact('listings', 'tags'));
    }

    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    public function apply(Request $request, Listing $listing)
    {
        $listing->clicks()
            ->create([
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        return redirect()->to($listing->apply_link);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $validationArray = [
            'title' => 'required',
            'company' => 'required',
            'logo' => 'file|max:2048',
            'location' => 'required',
            'apply_link' => 'required|url',
            'content' => 'required',
            'payment_method_id' => 'required',
        ];

        if (auth()->check()) {
            $validationArray = array_merge($validationArray, [
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:5',
                'name' => 'required'
            ]);
        }

        $request->validate($validationArray);

        $user = Auth::user();

        if (! $user) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name
            ]);

            $user->createAsStripeCustomer();
            Auth::login($user);
        }

        try {
            $amount = 9900;
            if ($request->filled('is_highlighted')) {
                $amount += 1900;
            }

            $user->charge($amount, $request->payment_method_id);

            $md = new \ParsedownExtra();

            $listing = $user->listings()
                ->create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title) . '-' . random_int(1111, 9999),
                    'company' => $request->company,
                    'logo' => basename($request->file('logo')->store('public')),
                    'location' => $request->location,
                    'apply_link' => $request->apply_link,
                    'content' => $md->text($request->content),
                    'is_highlighted' => $request->filled('is_highlighted'),
                    'is_active' => true
                ]);
            foreach (explode(',', $request->tags) as $requestTag) {
                $tag = Tag::firstOrCreate([
                    'slug' => Str::slug(trim($requestTag))
                ], [
                    'name' => ucwords(trim($requestTag))
                ]);
                $tag->listings()->attach($listing->id);
            }

            return redirect()->route('dashboard');

        } catch (\Exception $exception) {
            return redirect()->back()
                ->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
