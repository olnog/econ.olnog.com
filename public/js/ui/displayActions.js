function displayActions(){ //39
  let canTheyBuild = false
  let html = ""
  if (lastAction != null || lastContractAction != null){
    $("#lastAction").prop('disabled', false)
    $("#startAutomation").prop('disabled', false)
  }

  if (!actions.possible.includes(lastAction) && lastContractAction == null){
    lastAction = null
    $("#startAutomation").prop('disabled', true)
    if (automation != null){
      stopAutomation()
    }

  }

  for (i in actions.unlocked){
    let thisIsDisabled = 'btn-primary'
    let impossibleActionClass = ''
    let whyNotStart = ''
    let whyNotEnd = ''
    if (actions.unlocked[i].name == 'build'){
      canTheyBuild = true
      continue
    } else if (actions.unlocked[i].name == 'repair'){
      continue
    }
    let actionButton = "<button  id='action-" + actions.unlocked[i].name
      + "' class='m-2 action btn " + thisIsDisabled + " " + " btn-warning'>"
      + dedashify(actions.unlocked[i].name) + "</button>"
    if (actions.possible.includes(actions.unlocked[i].name)){
      actionButton = "<button  id='action-" + actions.unlocked[i].name
        + "' class='m-2 action btn " + thisIsDisabled + " " + " btn-primary'>"
        + dedashify(actions.unlocked[i].name) + "</button>"
    }
    html += actionButton
  }
  $("#actionListing").html(html)

  if (lastAction == null && lastContractAction == null){
    $("#lastAction").prop('disabled', true)
  }
  if (automation != null){

    $(".action").prop('disabled', true)

    $("#lastAction").prop('disabled', true)
  }
  $("#build").addClass('d-none')
  $('#buildingsThatCanBeBuilt').addClass('d-none')
  if (canTheyBuild){
    displayAvailableBuildings()
  }

  formatActions()
}

function displayFreelanceActions(){
  let html = ""
  let n = 0

  for (let i in contracts){
    if (contracts[i].category == 'freelance' && clacks >= contracts[i].price && contracts[i].userID != userID){
      html += "<button id='freelanceAction-" + contracts[i].id
        + "' class='freelance btn btn-danger'>" + contracts[i].action + " (-"
        + contracts[i].price + ") </button>"
        n++
    }
  }
  $("#freelanceActions").html(html)
  if (n < 1){
    $("#freelanced").addClass('d-none')
  }
}

function displayHireableActions(){
  let alwaysHireableActions = ['harvest-wheat', 'harvest-herbal-greens',
    'harvest-plant-x', 'harvest-rubber', 'plant-herbal-greens-field',
    'plant-plant-x-field', 'plant-rubber-plantation', 'plant-wheat-field']
  let html = ""
  let n = 0
  for (let i in contracts){
    if (contracts[i].category=='hire'
      && (alwaysHireableActions.includes(contracts[i].action)
      || actions.unlocked.includes(contracts[i].action))){
      html += "<button id='hireAction-" + contracts[i].id
      + "' class='hire btn btn-success'>" + contracts[i].action + " (+"
      + contracts[i].price + ")</button>"
      n++
    }
  }
  $("#hiredActions").html(html)
  if (n < 1){
    $("#hired").addClass('d-none')
  }
}

function formatActions(){
  $(".impossible").removeClass('d-none')
  if ($("#hideImpossible").is(':checked')){
    $(".impossible").addClass('d-none')
  }
}
