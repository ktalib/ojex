<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        parent::__construct();
        $this->userType = 'admin';
        $this->column = 'admin_id';
        $this->user = auth()->guard('admin')->user();
    }

    public function tickets()
    {
        $pageTitle = 'Copy Expert';
        $items = db::table('copy_experts')->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function pendingTicket()
    {
        $pageTitle = 'Pending Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->pending()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function closedTicket()
    {
        $pageTitle = 'Closed Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->closed()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function answeredTicket()
    {
        $pageTitle = 'Answered Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->orderBy('id','desc')->with('user')->answered()->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket','admin','attachments')->where('support_ticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    public function ticketDelete(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        DB::table('copy_experts')->where('id', $request->id)->delete();
     
        $notify[] = ['success', 'Deleted successfully.'];
        return back()->withNotify($notify);
    }

 
public function store(Request $request)
{
    $request->validate([
        'win_rate' => 'required',
        'name' => 'required',
        'profit' => 'required',
        'image' => 'required|file',
        'wins' => 'required',
        'loss' => 'required',
 
    ]);

    $data = [
        'win_rate' => $request->win_rate,
        'name' => $request->name,
        'profit' => $request->profit,
        'image' => $request->file('image')->store('images'),
        'wins' => $request->wins,
        'loss' => $request->loss,
       
    ];

    DB::table('copy_experts')->insert($data);
    $notify[] = ['success', 'Created successfully.'];
    return back()->withNotify($notify);
}

public function updateCopy(Request $request)
{
    $request->validate([
        'id' => 'required',
        'win_rate' => 'required',
        'name' => 'required',
        'profit' => 'required',
        'image' => 'nullable|file',
        'wins' => 'required',
        'loss' => 'required',
    ]);

    $id = $request->id;
    $data = [
        'id' => $request->id,
        'win_rate' => $request->win_rate,
        'name' => $request->name,
        'profit' => $request->profit,
        'wins' => $request->wins,
        'loss' => $request->loss,
    ];

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('images');
    }

    DB::table('copy_experts')->where('id', $id)->update($data);

    $notify[] = ['success', 'Updated successfully.'];
    return back()->withNotify($notify);
}
}
