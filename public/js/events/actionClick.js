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
  $(".action").prop('disabled', true)
  $("#startAutomation").addClass('d-none')
  $("#workHoursCent").prop('disabled', true)
  $("build").prop('disabled', true)
  $("#buildingsThatCanBeBuilt").prop('disabled', true)
  automation = setInterval(function(){
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
    if (autoActions == 0){
      updateWorkHoursStop()
      stopAutomation()
    }
  }, 2000)
})

$(document).on('click', '#stopAutomation', function(e) {
  stopButtonPressed = true
  stopAutomation()
})

function stopAutomation(){
  if (!stopButtonPressed && settings.sound){
    let audio = new Audio('js/stop.wav')
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
  stopButtonPressed = false
  if (autoActions != null){
    workHoursStop = autoActions
    if (autoActions == 0){
      autoActions = null
    }
  }
}
