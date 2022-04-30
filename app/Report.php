<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    public static function new(){
      $newLine = "<br>";
      $date = date('Y-m-d', strtotime('-1 days'));
      $reportMsg = "Daily Report " . $date . $newLine;
      $buttons = \App\Metric::whereDate('created_at', $date)
        ->whereNotNull('button')->get();
      $actions = \App\Metric::whereDate('created_at', $date)
        ->whereNotNull('action')->get();
      $data = [
        'action' => [
          'start'=>[],
          'stop'=>[],
          'users'=>[],
        ],
        'button' => [
          'start'=>[],
          'stop'=>[],
          'users'=>[],
        ],
      ];
      foreach ($buttons as $button){
        if (!in_array($button->userID, $data['button']['users'])){
          array_push($data['button']['users'], $button->userID);
          $data['button']['start'][$button->userID] = $button->created_at->format('Y-m-d H:i:s');
          $data['button']['stop'][$button->userID] = $button->created_at->format('Y-m-d H:i:s');
        } else if (in_array($button->userID, $data['button']['users'])){
          $data['button']['stop'][$button->userID] = $button->created_at->format('Y-m-d H:i:s');
        }
      }
      foreach ($actions as $action){
        if (!in_array($action->userID, $data['action']['users'])){
          array_push($data['action']['users'], $action->userID);
          $data['action']['start'][$action->userID] = $action->created_at->format('Y-m-d H:i:s');
          $data['action']['stop'][$action->userID] = $action->created_at->format('Y-m-d H:i:s');
        } else {
          $data['action']['stop'][$action->userID] = $action->created_at->format('Y-m-d H:i:s');
        }
      }
      $reportMsg .= "Active Users: " . count($data['button']['users']) . $newLine;
      $reportMsg .= "Total Actions: " . count($actions)
        . " Total Button Presses: " . count($buttons) . $newLine;
      $reportMsg .= "Avg Actions: "
        . round(count($actions) / count($data['button']['users']))
        . " Avg Button Presses: "
        . round(count($buttons) / count($data['button']['users']))
        . $newLine;
      foreach($data as $dataRef => $eachData){
        $totalMinutesPassed = [
          'action' => 0,
          'button' => 0,
        ];
        foreach ($eachData['users'] as $userID){
          $user = \App\User::find($userID);
          $startTime = strtotime($eachData['start'][$userID]);
          $stopTime = strtotime($eachData['stop'][$userID]);
          $minutesPassed = round(($stopTime-$startTime) / 60);
          $totalMinutesPassed[$dataRef] += $minutesPassed;
          if ($dataRef == 'button'){
            $reportMsg .= "\t" . $user->name . " : " . $minutesPassed . " minutes " . $newLine;
          }
        }
      }
      $reportMsg .= $newLine;
      $reportMsg .= "Total Minutes Played  : " . $minutesPassed . " minutes "
        . $newLine;
      $reportMsg .= "Avg Minutes Played  (" . count($data['button']['users'])
        . " user(s)) : " . $totalMinutesPassed['button'] . " minutes "
        . $newLine;
      $totalItems = \DB::table('items')->sum('quantity');
      $reportMsg .=  "Total Number Of Items In-Game: " . number_format($totalItems) . $newLine;
      $totalAllocations = \DB::table('labor')->sum('allocatedSkillPoints');
      $reportMsg .=  "Total Skill Points Allocated: " . number_format($totalAllocations) . $newLine;
      $totalAvailable = \DB::table('labor')->sum('availableSkillPoints');
      $reportMsg .=  "Total Skill Points Available: " . number_format($totalAvailable) . $newLine;
      $report = new \App\Report;
      $report->report = $reportMsg;
      $report->save();
    }
}
