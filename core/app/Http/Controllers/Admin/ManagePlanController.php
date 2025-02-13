<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogicBox;
use App\Models\PhaseLogic;
use App\Models\Plan;
use App\Models\PlanHistory;
use App\Models\PlanPhase;
use App\Models\SignalPurchase;
use App\Models\Signals;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ManagePlanController extends Controller
{
    public function list()
    {
        $pageTitle = "All Signals Plans";
        $plans     = Signals::searchable(['name'])->paginate(getPaginate());
        return view('admin.plan.list', compact('pageTitle', 'plans'));
    }

    public function add()
    {
        $pageTitle  = 'Add New Plan';
        $logicBoxes = LogicBox::active()->get();
        return view('admin.plan.add', compact('pageTitle', 'logicBoxes'));
    }

    public function edit($id)
    {
        $pageTitle  = 'Update Plan';
        $plan       = Plan::with('planPhases.phaseLogics')->findOrFail($id);
        $logicBoxes = LogicBox::active()->get();
        return view('admin.plan.add', compact('pageTitle', 'logicBoxes', 'plan'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name'             => 'required',
            'price'            => 'required|numeric|gt:0',
            'fund'             => 'required|numeric|gt:0',
            'conversion'       => 'required|integer|gt:0',
            'max_daily_loss'   => 'required|numeric|gt:0',
            'max_overall_loss' => 'required|numeric|gt:0',

            'phase_name'       => 'required|array',
            'phase_name.*'     => 'required',

            'phase_profit'     => 'required|array',
            'phase_profit.*'   => 'required|numeric|gt:0',

            'logic'            => 'required|array',
            'logic.*'          => 'required',
        ]);

        $phaseNameKeys = array_unique(array_keys($request->phase_name));
        $loginKeys     = array_unique(array_keys($request->logic));

        if ($phaseNameKeys != $loginKeys) {
            $notify[] = ['error', 'Each phase must have a logic'];
            return back()->withNotify($notify);
        }

        if ($id) {
            $plan     = Plan::with('planPhases.phaseLogics')->findOrFail($id);
            $notify[] = ['success', 'Plan updated successfully'];
            $this->deleteOldPhaseLogic($plan, $request);
        } else {
            $plan     = new Plan();
            $notify[] = ['success', 'Plan added successfully'];
        }

        $plan->name              = $request->name;
        $plan->price             = $request->price;
        $plan->fund              = $request->fund;
        $plan->conversion        = $request->conversion;
        $plan->duration          = 30;
        $plan->max_daily_loss    = $request->max_daily_loss;
        $plan->max_overall_loss  = $request->max_overall_loss;
        $plan->save();

        foreach ($request->logic as $key => $logics) {
            if (isset($request->old_phase[$key])) {
                $planPhase = PlanPhase::findOrFail($request->old_phase[$key]);
            } else {
                $planPhase          = new PlanPhase();
                $planPhase->plan_id = $plan->id;
            }

            $planPhase->name   = $request->phase_name[$key];
            $planPhase->profit = $request->phase_profit[$key];
            $planPhase->save();

            foreach ($logics as $k => $logic) {
                if (isset($request->old_logic[$key][$k])) {
                    $phaseLogic = PhaseLogic::findOrFail($request->old_logic[$key][$k]);
                } else {
                    $phaseLogic                = new PhaseLogic();
                    $phaseLogic->plan_phase_id = $planPhase->id;
                }

                $phaseLogic->logic_box_id = $logic;
                $phaseLogic->save();
            }
        }

        return back()->withNotify($notify);
    }

    private function deleteOldPhaseLogic($plan, $request)
    {
        $previousPhaseIds = $plan->planPhases->pluck('id')->toArray();
        PlanPhase::whereIn('id', array_diff($previousPhaseIds, $request->old_phase))->delete();

        $oldLogicIds = collect($request->old_logic)->flatten()->toArray();

        $previousLogicIds = [];
        foreach ($plan->planPhases as $phase) {
            $previousLogicIds = array_merge($previousLogicIds, $phase->phaseLogics->pluck('id')->toArray());
        }

        PhaseLogic::whereIn('id', array_diff($previousLogicIds, $oldLogicIds))->delete();
    }

    public function history()
    {
        $pageTitle = "Users Signal Plan History";
        // get all signal purchase
        $histories = Db::table('signal_purchases')
            ->join('users', 'signal_purchases.user_id', '=', 'users.id')
            ->select('signal_purchases.*', 'users.username')
            ->latest()
            ->paginate(getPaginate());
        return view('admin.plan.histories', compact('pageTitle', 'histories'));
    }
    public function status($id)
    {
        return Plan::changeStatus($id);
    }
}
