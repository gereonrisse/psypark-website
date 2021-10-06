<?php

namespace App\Http\Controllers;

use App\Models\PostingItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PostingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $r)
    {
        $available_years = PostingItem::selectRaw('YEAR(datetime) as year')->groupBy('year')->orderBy('year', 'DESC')->get()->pluck('year');
        $selected_year = $r->query('year') ?? $available_years[0] ?? 2021;
        $search = $r->query('search');

        $posting_items = PostingItem::when($selected_year !== 'all', function ($query) use ($selected_year)
        {
            return $query->whereYear('datetime', $selected_year);
        })
            ->when($search, function ($query) use ($search)
            {
                return $query->where(function ($query) use ($search)
                {
                    $query->where('description', 'like', "%$search%")
                        ->orWhere('notes', 'like', "%$search%")
                        ->orWhere('datetime', 'like', "%$search%")
                        ->orWhere('person', 'like', "%$search%");
                });
            })
            ->orderBy('datetime', 'desc')
            ->get();

        $balance = PostingItem::sum('amount');

        return Inertia::render('PostingItems/Index', [
            'posting_items'   => $posting_items,
            'balance'         => $balance,
            'available_years' => $available_years
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('PostingItems/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $r): \Illuminate\Http\RedirectResponse
    {
        logger()->debug($r->get('time'));

        $r->validate([
            'description' => ['required', 'max:255'],
            'person'      => ['required', 'max:255'],
            'amount'      => ['required', 'numeric', 'not_in:0'],
            'date'        => ['required', 'date'],
            'time'        => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes'       => ['nullable', 'max:65536'],
            'file'        => ['nullable', 'file', 'mimes:jpg,bmp,png,pdf', 'max:50000']
        ]);

        $postingItem = PostingItem::create([
            'user_id'     => auth()->user()->id,
            'description' => $r->get('description'),
            'person'      => $r->get('person'),
            'notes'       => $r->get('notes'),
            'amount'      => $r->get('amount'),
            'datetime'    => $r->get('date') . ' ' . $r->get('time') . ':00',
            'file_hash'   => null
        ]);

        session()->flash('message', 'Buchung wurde angelegt');
        session()->flash('type', 'success');

        return Redirect::route('posting-items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PostingItem $postingItem
     * @return \Inertia\Response
     */
    public function show(PostingItem $postingItem): \Inertia\Response
    {
        $user_name = User::find($postingItem->user_id)->value('name');

        return Inertia::render('PostingItems/Show', [
            'posting_item' => $postingItem,
            'user_name'    => $user_name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PostingItem $postingItem
     * @return \Inertia\Response
     */
    public function edit(PostingItem $postingItem)
    {
        return Inertia::render('PostingItems/Edit', [
            'posting_item' => $postingItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'description' => ['required', 'max:255'],
            'person'      => ['required', 'max:255'],
            'amount'      => ['required', 'numeric', 'not_in:0'],
            'date'        => ['required', 'date'],
            'time'        => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes'       => ['nullable', 'max:65536'],
            'file'        => ['nullable', 'file', 'mimes:jpg,bmp,png,pdf', 'max:50000']
        ]);

        $posting_item = PostingItem::find($request->get('id'))
            ->update([
                'description' => $request->get('description'),
                'person'      => $request->get('person'),
                'amount'      => $request->get('amount'),
                'date'        => $request->get('date'),
                'time'        => $request->get('time'),
                'notes'       => $request->get('notes'),
                'file'        => $request->get('file'),
            ]);

        session()->flash('message', 'Buchung wurde aktualisiert');
        session()->flash('type', 'success');

        return Redirect::route('posting-items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PostingItem $postingItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PostingItem $postingItem)
    {
        $postingItem->delete();

        session()->flash('message', 'Buchung wurde gelöscht');

        return Redirect::route('posting-items.index');
    }
}
