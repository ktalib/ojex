<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogicBox;
use Illuminate\Http\Request;

class LogicBoxController extends Controller
{
    public function list()
    {
        $pageTitle  = "Logic Box List";
        $logicBoxes = LogicBox::searchable(['name'])->paginate(getPaginate());
        return view('admin.plan.logic_box', compact('pageTitle', 'logicBoxes'));
    }
    public function store(Request $request, $id = 0)
    {
        $request->validate([
            "name"       => "required|string",
            "type"       => "required|in:1,2",
            "logic"      => "required|in:1,2,3,4,5,6",
            "value_from" => "required|gt:0",
            "value_to"   => "required_if:logic,4|nullable|gt:value_from",
            "duration"   => "required|gt:0",
        ]);

        if ($id) {
            $logicBox     = LogicBox::findOrFail($id);
            $notification = "Logic box updated successfully";
        } else {
            $logicBox     = new LogicBox();
            $notification = "Logic box added successfully";
        }

        $logicBox->name       = $request->name;
        $logicBox->type       = $request->type;
        $logicBox->logic      = $request->logic;
        $logicBox->value_from = $request->value_from;
        $logicBox->value_to   = $request->value_to ?? 0;
        $logicBox->duration   = $request->duration;
        $logicBox->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    public function status($id)
    {
        return LogicBox::changeStatus($id);
    }
}
