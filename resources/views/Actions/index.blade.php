<div class='text-center'>Food: <span id='laborFood'>{{number_format($food)}}</span></div>
<div class='text-center mt-3'>
    <button id='show-laborSupplement' class='show btn btn-link'> [ SHOW EQUIPMENT ]</button>
    <button id='hide-laborSupplement' class='hide btn btn-link d-none'> [ LESS ]</button>
</div><div id='laborSupplement' class='d-none'>
  <div class='fw-bold'>
      Equipment
      <button id='show-equipmentListings' class='show btn btn-link'>+</button>
      <button id='hide-equipmentListings' class='hide btn btn-link d-none'>-</button>
    </div><div class='ms-3'>
      Equipped:
      <span id='equipped'>
        @if ($equipped['main'] != null)
          <span class='fw-bold'>{{$equipped['main']->name}}</span>:
          {{round($equipped['main']->uses / $equipped['main']->totalUses * 100, 1)}}%
        @else
          nothing
        @endif
      </span>
    </div><div class='ms-3'>
      Special Equipment:
      <span id='specialEquipped'>
        @if ($equipped['also'] != null)
          <span class='fw-bold'>{{$equipped['also']->name}}</span>:
          {{round($equipped['also']->uses / $equipped['also']->totalUses * 100, 1)}}%
        @else
          nothing
        @endif
      </span>
    </div><div id='equipmentListings' class='ms-3 pb-3 d-none'>
      @foreach ($allEquipment as $equipment)
        <div class='row mt-3'>
          <div class='col'>
            <span class='@if (($equipped['main'] != null
              && $equipment->id == $equipped['main']['id'])
              || ($equipped['also'] != null
              && $equipment->id == $equipped['also']['id'])) fw-bold @endif'>
            {{$equipment->name}}:
            </span>
            {{round($equipment->uses / $equipment->totalUses * 100, 1)}} %
            @foreach ($relevantFuel as $equipmentFuel => $fuelName)
              @continue (!str_contains($equipment->name, $equipmentFuel))
              <div class='ms-5'>
              [{{$fuelName}}:
              {{number_format(\App\Items::fetchByName($fuelName, \Auth::id())->quantity)}}]
              </div>
            @endforeach
          </div>

          <div class='col'>
            @if (($equipped['main'] == null
              || ($equipped['main'] != null
              && $equipment->id != $equipped['main']->id))
              && ($equipped['also'] == null
                || ($equipped['also'] != null
                && $equipment->id != $equipped['also']->id)))
              <button id='equipEquipment-{{$equipment->id}}'
                class='equipEquipment btn btn-link'>equip</button>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div><div class='row'>
  <div class='col-lg-3 '>
    <span class='fw-bold'>Unlocked</span>
    <a href='/actionTypes/' class='me-3'>[ unlock more ] </a>
    <a href='https://econ.olnog.com/help#actions'>[ help ]</a>
    <a href='/contracts/create?category=freelance' class='btn ms-3 createContract'>
      <img src='/img/icons8-sell-24.png'>
    </a>
  </div><div class='col-lg-9 '>
    <input type='checkbox' id='hideImpossible' class='formatActions'> Hide unavailable actions?
  </div><div>
  <span id='skillUnlocked' class=' fw-bold d-none'> You can unlock a new action!</span>
</div>
</div><div class=''>
  <div class="progress">
    <div id='skillPointProgress' class="progress-bar" role="progressbar"
      style="width:{{$skillPointCent}}%"
      aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
  </div>

</div><div id='actionsSection' class='ms-3'>
  <div id='newBuildingAvailable' class='fw-bold mt-2 d-none'>You've got a new building available to build!</div>


  @if (in_array('build', $actions) && !empty($buildableBuildings))
    <select id='buildingsThatCanBeBuilt' class=''>
      <option></option>
      @foreach($buildableBuildings as $building)
        <option value='{{$building}}'>{{$building}}</option>
      @endforeach
    </select>
    <button id='build' class='btn btn-primary' disabled>build</button>
  @endif
  <div id='buildingCosts'></div>
  <div id='actionListing' class='ps-3'>
    @if (empty($actions))
      <div class='text-center mt-3'>
        <span class='fw-bold'>You don't have any actions unlocked yet.</span>
      </div><div class='text-center mt-3'>
        <a href='/actionTypes'>[ Unlock here ]</a>
      </div>
    @endif
    @foreach($actions as $action)
      @continue (in_array($action, $banned))
      <button id='action-{{$action}}' class='m-2 action btn
      @if (in_array($action, $actionable))
        btn-primary
      @else
        btn-warning
      @endif
      ' @if ($actionBeingDoneNow) disabled @endif>
        {{implode(' ', explode('-', $action))}}
      </button>
    @endforeach
  </div>
</div><div>
  @if (count($freelanceContracts) > 0)
  <div id='freelanced' class='mt-1'>
    <div class=''>
      <span class='fw-bold'>freelancing</span> (pay clacks)
    </div><div id='freelanceActions' class='ps-5'>
      <?php $lastFreelanceAction = null; ?>
      @foreach($freelanceContracts as $contract)
        @if ($contract->action == $lastFreelanceAction)
          @continue
        @endif
        <?php
          $lastFreelanceAction = $contract->action;
        ?>
        @if($contract->action == 'build' )
          @if(count($buildableBuildings) > 0 && $canTheyBuild)
            <div>
              <select id='contractBuildableBuildings-{{$contract->id}}-actions'
                class='freelanceBuildSelect'
                @if (count($buildableBuildings) < 1) disabled @endif>
                <option></option>
                @foreach($buildableBuildings as $building)
                  <option value='{{$building}}'>{{$building}}</option>
                @endforeach
              </select>
              <button id='freelanceBuild-{{$contract->id}}-actions'
                class='freelanceBuild btn btn-danger m-3'
                @if (count($buildableBuildings) < 1) disabled @endif>
                {{$contract->action}} (-{{$contract->price}})
              </button>
            </div>
          @endif
        @elseif($contract->action == 'repair')
          @if (count($repairableBuildings) > 0)
            <div>
              <select id='contractRepairableBuildings-{{$contract->id}}-actions' class='freelanceRepairSelect' @if (count($repairableBuildings) < 1) disabled @endif>
                <option></option>
                @foreach($repairableBuildings as $building)
                  <option value='{{$building->id}}'>{{$building->name}} ({{$building->uses / $building->totalUses * 100}}%)</option>
                @endforeach
              </select>
              <button id='freelanceRepair-{{$contract->id}}-actions'
                class='freelanceRepair btn btn-danger m-3' @if (count($repairableBuildings) < 1) disabled @endif>
                {{$contract->action}} (-{{$contract->price}})
              </button>
            </div>
          @endif
        @else
          <button id='freelanceAction-{{$contract->id}}-actions'
            class='freelance btn btn-danger m-2' @if ($actionBeingDoneNow) disabled @endif>
            {{$contract->action}} (-{{$contract->price}})
          </button>
        @endif
      @endforeach
    </div>
  </div>
  @endif
  @if (count($hireableContracts) > 0)
  <div id='hired' class='mt-1'>
    <div class=''>
      <span class='fw-bold'>hiring</span> (receive clacks)
    </div><div id='hiredActions' class='ps-5'>
      @foreach($hireableContracts as $contract)
        @if(in_array($contract->action, $actions))
          <button id='hireAction-{{$contract->id}}-actions'
            class='hire btn btn-success ' @if ($actionBeingDoneNow) disabled @endif>
            {{$contract->action}} (+{{$contract->price}})
          </button>
        @endif
      @endforeach
    </div>
  </div>
  @endif
  @if (count($robots) > 0)
  <div id='robots' class=''>
    <div class=' mt-5'>
      <span class='fw-bold'>Robots </span>
      <button id='robotStart' class='btn btn-success'>&#9658;</button>
      <button id='robotStop'class='btn btn-danger d-none' >&#128721;</button>
      <span id='robotAnimation' class='d-none'></span>
    </div><div>
      Electricity: <span id='robotsElectricity'>{{number_format($electricity)}}</span>
    </div><div id='robotListing'>
    @foreach($robots as $robot)
        <div>Robot #{{$robot->num}} ({{round($robot->uses / 100 * 10, 1)}}%) -
          <span class='fw-bold'>{{$robot->name}}</span>
          <select id='reprogramList-{{$robot->id}}' class='reprogramList'>
            <option value='null'></option>
            @foreach($actions as $action)
              <option value='{{$action}}'>{{implode(' ', explode('-', $action))}}</option>
            @endforeach
          </select>
          <button id='reprogram-{{$robot->id}}' class='reprogram btn btn-link'>
            reprogram
          </button>
        </div><div id='robotStatus{{$robot->id}}' class='bg-secondary'></div>
        <div id='robotError{{$robot->id}}' class='text-danger'></div>
    @endforeach

    </div>
  </div>
  @endif
</div><div id='' class='fw-bold mt-3'>
  Rebirth
  <button id='show-rebirthSection' class='show btn btn-link'>+</button>
  <button id='hide-rebirthSection' class='hide btn btn-link d-none'>-</button>
</div><div id='rebirthSection' class='d-none'>
  <div class='text-center p-3'>
    We understand that you might get yourself into bad spots so you can
    Rebirth at any time. You will still get the 50% estate tax clack penalty.
  </div><div class='text-center'>
    <button id='rebirth' class='btn btn-danger form-control'>Rebirth</button>
  </div>
</div>
