function action (actionName){
  let csrf = fetchCSRF()
  let consumption = {
    food: $("#eatFood").is(':checked'),
    herbMeds: $("#useHerbMeds").is(':checked'),
    bioMeds: $("#useBioMeds").is(':checked'),
    nanoMeds: $("#useNanoMeds").is(':checked')
  }
  $.post( "/actions", {name: actionName, buttons: buttonMetric,
      automation: automation != null, consumption: JSON.stringify(consumption),
      _token: csrf }).done(function(data){
      $(".disabledActions").prop('disabled', true)
    if (automation == null){

      resetActionDisable()
    }
    loadPage('actions')
    buttonMetric = []
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      if (automation != null){
        stopAutomation(true)
      }
      return;
    }
    status(JSON.parse(data).status)
    csrfToken = JSON.parse(data).csrf
    displayHeaders(JSON.parse(data).info)
    displayAutomation()
  }).fail(function(){ // DID THIS WORK? 05/21/22
    location.reload()
  })
}

function doTheyHaveThisActionUnlocked(actionName){
  for (let i in actions.unlocked){
    if (actions.unlocked[i].name == actionName){
      return true
    }
  }
  return false
}
