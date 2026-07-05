<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\User;
use App\Models\ShortUrl;
use App\Models\Invitation;


class DashboardController extends Controller
{
 public function dashboard(){
 	 $user = Auth::user();
        $data['user'] = $user;

        if ($user->isSuperAdmin()) {
				$data['companiesCount']     = Company::count();
				$data['usersCount']         = User::count();
				$data['shortUrls']          = collect();
				$data['totalShortUrls']     = 0;
				$data['teamMembers']        = User::latest()->get();
				$data['totalTeamMembers']   = User::count();

        } elseif ($user->isAdmin()) {
                $data['shortUrls'] = ShortUrl::where('company_id', $user->company_id)
                                ->where('user_id', '!=', $user->id)
                                ->latest()->get();
                $data['totalShortUrls']   = ShortUrl::where('company_id', $user->company_id)
                                ->where('user_id', '!=', $user->id)
                                ->count();
                $data['teamMembers']      = User::where('company_id', $user->company_id)->get();
                $data['totalTeamMembers'] = User::where('company_id', $user->company_id)->count();
        } elseif ($user->isManager()) {
                 $data['shortUrls']        = ShortUrl::where('company_id', $user->company_id)->latest()->get();
                 $data['totalShortUrls']   = ShortUrl::where('company_id', $user->company_id)->count();
                 $data['teamMembers']      = User::where('company_id', $user->company_id)->get();
                 $data['totalTeamMembers'] = User::where('company_id', $user->company_id)->count();
        } else {
                 $data['shortUrls'] = ShortUrl::where('company_id', $user->company_id)
                                ->where('user_id', '!=', $user->id)
                                ->latest()->get();
                 $data['totalShortUrls'] = ShortUrl::where('company_id', $user->company_id)
                                ->where('user_id', '!=', $user->id)
                                ->count();
                 $data['teamMembers']      = collect();
                 $data['totalTeamMembers'] = 0;
        }

 	  return view('frontend.dashboard', $data);
 }

public function createUrl()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isSuperAdmin()) {
             return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create short URLs.');
  
        }

        return view('frontend.createshorturls');
    }

public function storeUrl(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to create short URLs.');
 
        }

        $request->validate(['original_url' => 'required|url']);

        ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code'   => Str::random(6),
            'user_id'      => $user->id,
            'company_id'   => $user->company_id,
            'hits'         => 0,
        ]);

        return redirect()->route('dashboard')->with('success', 'Short URL ban gaya.');
    }



    public function inviteteammembers()
    {
    $user = Auth::user();

    if (! ($user->isSuperAdmin() || $user->isAdmin())) {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to invite team members.');
    }

    $companies = $user->isSuperAdmin() ? Company::all() : collect();

    return view('frontend.inviteteams', compact('companies'));
   }


    public function storeInvitemembers(Request $request)
{
    $user = Auth::user();

    if (! ($user->isSuperAdmin() || $user->isAdmin())) {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to invite team members.');
    }

    if ($user->isSuperAdmin()) {
 
        $request->validate([
            'email'        => 'required|email',
            'role'         => 'required|string|in:Admin,Manager,Member,Sales',
            'company_id'   => 'nullable|integer|exists:companies,id',
            'company_name' => 'nullable|string|max:255|required_without:company_id',
        ]);
    } else {
        $request->validate([
            'email' => 'required|email',
            'role'  => 'required|string|in:Manager,Sales',
        ]);
    }

    $isNewCompany = $user->isSuperAdmin() && empty($request->company_id);

    if ($user->isSuperAdmin() && $isNewCompany && $request->role === User::ROLE_ADMIN) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'A SuperAdmin cannot invite an Admin for a new company.');
    }

    if ($user->isAdmin() && in_array($request->role, [User::ROLE_ADMIN, User::ROLE_MEMBER])) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'You do not have permission to invite an Admin or a Member.');
    }

    if ($user->isSuperAdmin()) {
        $companyId = $request->company_id;

        if ($isNewCompany) {
            $company = Company::create([
                'name'  => $request->company_name,
                'email' => $request->email,
            ]);
            $companyId = $company->id;
        }
    } else {

        $companyId = $user->company_id;
    }

    Invitation::create([
        'email'      => $request->email,
        'role'       => $request->role,
        'company_id' => $companyId,
        'invited_by' => $user->id,
        'token'      => Str::random(40),
    ]);

    return redirect()->route('team.invite')->with('success', 'Invitation sent successfully.');
}

   public function teamMembers()
    {
        $user = Auth::user();

        if (! ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager())) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view team members.');
        }

        $teamMembers = $user->isSuperAdmin()
            ? User::latest()->get()
            : User::where('company_id', $user->company_id)->get();

        return view('frontend.teams', compact('teamMembers'));
    }

    public function redirect(string $code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();
        $shortUrl->increment('hits');

        return redirect()->away($shortUrl->original_url);
    }



}

