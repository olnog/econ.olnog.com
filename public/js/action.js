function action (actionName){
  let csrf = fetchCSRF()
  let consumption = {
    food: $("#eatFood").is(':checked'),
    herbMeds: $("#useHerbMeds").is(':checked'),
    bioMeds: $("#useBioMeds").is(':checked'),
    nanoMeds: $("#useNanoMeds").is(':checked')
  }
  console.log(buttonMetric)
  console.log('action')
  $.post( "/actions", {name: actionName, buttons: buttonMetric, automation: automation != null, consumption: JSON.stringify(consumption), _token: csrf }).done(function(data){
    console.log(data)
    buttonMetric = []
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      if (automation != null){
        stopAutomation()
      }
      return;
    }
    actions = JSON.parse(data).actions
    buildingSlots = JSON.parse(data).buildingSlots

    buildings = JSON.parse(data).buildings
    clacks        = JSON.parse(data).clacks
    equipment = JSON.parse(data).equipment
    statusHistory = JSON.parse(data).history
    status(JSON.parse(data).status)
    items = JSON.parse(data).items
    itemCapacity = JSON.parse(data).itemCapacity
    labor = JSON.parse(data).labor
    land = JSON.parse(data).land
    numOfItems = JSON.parse(data).numOfItems
    csrfToken = JSON.parse(data).csrf
    if (labor.rebirth){
      location.reload()
    }
    refreshUI()
  })
}
