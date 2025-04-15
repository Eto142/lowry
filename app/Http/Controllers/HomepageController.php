<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Artwork;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class HomepageController extends Controller
{


    // Show all exhibitions (with different sections for past/future)
    public function index()
    {
        $now = Carbon::now();

        // Past exhibitions (ended before today)
        $pastExhibitions = Exhibition::where('exhibition_type', 'past')
            ->orderBy('created_at', 'desc')
            ->get();
        // Upcoming exhibitions (only shown to logged in users)
        $upcomingExhibitions = [];
        if (auth()->check()) {
            $upcomingExhibitions = Exhibition::where('exhibition_type', 'future')
                ->get();
        }

        // Available artworks for purchase
        $availableArtworks = Artwork::where('is_sold', false)
            ->with('artist')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.homepage', [
            'pastExhibitions' => $pastExhibitions,
            'upcomingExhibitions' => $upcomingExhibitions,
            'availableArtworks' => $availableArtworks
        ]);
    }


    public function futureExhibitions()
    {
        $now = Carbon::now();

        // future exhibitions 
        $futureExhibitions = Exhibition::where('exhibition_type', 'future')
            ->orderBy('created_at', 'desc')
            ->get();

        // Available artworks for purchase
        return view('home.future_exhibition', [
            'futureExhibitions' => $futureExhibitions,

        ]);
    }

    public function currentExhibitions()
    {
        $now = Carbon::now();

        // current exhibitions 
        $currentExhibitions = Exhibition::where('exhibition_type', 'current')
            ->orderBy('created_at', 'desc')
            ->get();

        // Available artworks for purchase
        return view('home.current_exhibition', [
            'currentExhibitions' => $currentExhibitions,

        ]);
    }


    public function pastExhibitions()
    {
        $now = Carbon::now();

        // current exhibitions 
        $pastExhibitions = Exhibition::where('exhibition_type', 'past')
            ->orderBy('created_at', 'desc')
            ->get();

        // Available artworks for purchase
        return view('home.past_exhibition', [
            'pastExhibitions' => $pastExhibitions,

        ]);
    }

    // Show exhibiton home page
    public function Exhibition()
    {
        $now = Carbon::now();

        // Past exhibitions (ended before today)
        $pastExhibitions = Exhibition::where('exhibition_type', 'past')
            ->orderBy('created_at', 'desc')
            ->get();
        // Upcoming exhibitions (only shown to logged in users)
        $upcomingExhibitions = [];
        if (auth()->check()) {
            $upcomingExhibitions = Exhibition::where('exhibition_type', 'future')
                ->get();
        }

        // Available artworks for purchase
        $availableArtworks = Artwork::where('is_sold', false)
            ->with('artist')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.exhibition', [
            'pastExhibitions' => $pastExhibitions,
            'upcomingExhibitions' => $upcomingExhibitions,
            'availableArtworks' => $availableArtworks
        ]);
    }


    public function about()
    {

        return view('home.about',);
    }

    public function permanent()
    {

        return view('home.permanent-collection',);
    }



    // Show Collections page
    public function Collections()
    {
        return view('home.collections');
    }

    // Show Plan page
    public function Plan()
    {
        return view('home.plan');
    }

    // Show Access page
    public function Access()
    {
        return view('home.access');
    }

    // Show Membership page
    public function Membership()
    {
        return view('home.membership');
    }

    // Show Group page
    public function Group()
    {
        return view('home.group');
    }

    // Show ticket page
    public function Ticket()
    {
        return view('home.ticket');
    }

    // Show socially page
    public function Socially()
    {
        return view('home.socially');
    }

    // Show Families page
    public function Families()
    {
        return view('home.families');
    }

    // Show young page
    public function Young()
    {
        return view('home.young');
    }

    // Show Artist page
    public function Artist()
    {
        return view('home.artist');
    }

    // Show Impact page
    public function Impact()
    {
        return view('home.impact');
    }


    // Show Support page
    public function Support()
    {
        return view('home.support');
    }



    // Show single exhibition
    public function show(Exhibition $exhibition)
    {
        $now = Carbon::now();
        $isPast = $exhibition->created_at < $now;

        return view('exhibitions.show', [
            'exhibition' => $exhibition,
            'isPast' => $isPast
        ]);
    }

    // Show artwork details
    public function showArtwork(Artwork $artwork)
    {
        return view('exhibitions.artwork', [
            'artwork' => $artwork
        ]);
    }

    // Handle auction bidding
    public function placeBid(Request $request, Artwork $artwork)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:' . ($artwork->current_bid + 1)
        ]);

        // Create new bid
        $bid = $artwork->bids()->create([
            'user_id' => auth()->id(),
            'amount' => $validated['amount'],
            'is_anonymous' => $request->has('anonymous')
        ]);

        // Update artwork current bid
        $artwork->update([
            'current_bid' => $validated['amount']
        ]);

        // Notify previous highest bidder
        if ($artwork->current_bidder) {
            Mail::to($artwork->current_bidder->email)->send(
                new OutbidNotification($artwork, $validated['amount'])
            );
        }

        return back()->with('success', 'Your bid has been placed!');
    }

    // Process artwork purchase
    public function purchaseArtwork(Request $request, Artwork $artwork)
    {
        if ($artwork->is_sold) {
            return back()->with('error', 'This artwork has already been sold.');
        }

        // Process payment (would integrate with Stripe/PayPal in real app)
        try {
            // In a real app, you would process payment here
            // $payment = PaymentGateway::charge($request->payment_token, $artwork->price);

            // Mark as sold
            $artwork->update([
                'is_sold' => true,
                'buyer_id' => auth()->id(),
                'sold_at' => now(),
                'sale_amount' => $artwork->price
            ]);

            // Notify artist
            Mail::to($artwork->artist->email)->send(
                new ArtworkSoldNotification($artwork)
            );

            // Notify buyer
            Mail::to(auth()->user()->email)->send(
                new PurchaseConfirmation($artwork)
            );

            return redirect()->route('purchase.confirmation', $artwork)
                ->with('success', 'Purchase completed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // Auction live view
    public function liveAuction(Artwork $artwork)
    {
        if (!$artwork->is_auction || $artwork->auction_end < now()) {
            return redirect()->route('artwork.show', $artwork);
        }

        $bids = $artwork->bids()
            ->with('bidder')
            ->orderBy('amount', 'desc')
            ->get();

        return view('exhibitions.auction', [
            'artwork' => $artwork,
            'bids' => $bids
        ]);
    }
}
