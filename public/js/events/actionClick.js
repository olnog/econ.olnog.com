$(document).on('click', '.action', function(e) {
  let actionName = e.target.id.substring('action-'.length)
  $("#lastAction").html($("#" + e.target.id).html())
  action(actionName)
  lastAction = actionName
  //refreshUI()
})

$(document).on('click', '#build:not(.btn-warning)', function(e) {
  let typeOfBuilding = $("#buildingsThatCanBeBuilt").val()
  buildBuilding(typeOfBuilding)
  refreshUI()
})

$(document).on('click', '#lastAction', function(e) {
  if (lastAction != null){
    action(lastAction)
  } else if(lastContractAction != null){
    if (lastContractAction.hire != undefined){
      hire(lastContractAction.hire)
    } else if (lastContractAction.freelance != undefined){
      freelance(lastContractAction.freelance)
    }
  }
})

$(document).on('click', '#startAutomation', function(e) {
  $("#stopAutomation").removeClass('d-none')
  $("#stopAutomation").prop('disabled', false)
  $("#lastAction").prop('disabled', true)
  $("#startAutomation").addClass('d-none')
  $("#workHoursCent").prop('disabled', true)
  disableForAutomation()
  automation = setInterval(function(){
    $(".disabledActions").prop('disabled', true)
    if (lastAction != null){
      action(lastAction)
    } else if(lastContractAction != null){
      if (lastContractAction.hire != undefined){
        hire(lastContractAction.hire)
      } else if (lastContractAction.freelance != undefined){
        freelance(lastContractAction.freelance)
      }
    }

    if (autoActions != null){
      --autoActions
      $("#workHoursCent").val(autoActions)
    }
    if (autoActions != null && autoActions < 1){
      updateWorkHoursStop()
      stopAutomation()
    }
  }, 3000)
})

$(document).on('click', '#stopAutomation', function(e) {
  stopButtonPressed = true
  stopAutomation()
})

function disableForAutomation(){
  $(".freelanceBuild").prop('disabled', true)
  $(".freelanceRepair").prop('disabled', true)
  $(".freelanceBuildSelect").prop('disabled', true)
  $(".freelanceRepairSelect").prop('disabled', true)
  $("#buildingsThatCanBeBuilt").prop('disabled', true)
  $(".action").prop('disabled', true)
  $("#build").prop('disabled', true)

}

function stopAutomation(wasThereAnError){
  if (!stopButtonPressed && settings.sound){
    let audio = new Audio('audio/stop.wav')
    audio.play()
  }
  clearInterval(automation)
  automation = null
  $("#buildingsThatCanBeBuilt").prop('disabled', true)
  $("build").prop('disabled', false)
  $("#stopAutomation").addClass('d-none')
  $("#lastAction").prop('disabled', false)
  $(".action:not('.impossible')").prop('disabled', false)
  $("#startAutomation").removeClass('d-none')
  $("#workHoursCent").prop('disabled', false)
  $(".freelanceBuild").prop('disabled', false)
  $(".freelanceRepair").prop('disabled', false)
  $(".freelanceBuildSelect").prop('disabled', false)
  $(".freelanceRepairSelect").prop('disabled', false)
  stopButtonPressed = false
  if (wasThereAnError == true){
    $("#startAutomation").prop('disabled', true)
    $("#lastAction").prop('disabled', true)
  }
  if (autoActions != null){
    workHoursStop = autoActions
    if (autoActions == 0){
      autoActions = null
    }
  }
  $.get("/stop", function(data){
  })

}
