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
    console.log(data)
    loadPage('actions')
    buttonMetric = []
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      if (automation != null){
        stopAutomation()
      }
      return;
    }
    status(JSON.parse(data).status)
    csrfToken = JSON.parse(data).csrf
    displayHeaders(JSON.parse(data).info)
    displayAutomation(JSON.parse(data).lastAction)
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
