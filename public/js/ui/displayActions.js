function displayActions(){ //39
  html = ""
  if (skills.construction.rank > 0 ){
    html = "<button id='build' class='btn btn-primary whyNot'>build</button></span>"
      + "<select id='buildingsThatCanBeBuilt' class='d-none me-3'><option selected></option></select>"
  }
  if (lastAction != null || lastContractAction != null){
    $("#lastAction").prop('disabled', false)
    $("#startAutomation").prop('disabled', false)
  }
  if (!actions.available.includes(lastAction) && lastContractAction == null){
    lastAction = null
    $("#startAutomation").prop('disabled', true)
    if (automation != null){
      stopAutomation()
    }

  }
  for (i in actions.possible){
    let thisIsDisabled = 'btn-primary'
    let impossibleActionClass = ''
    let whyNotStart = ''
    let whyNotEnd = ''
    if (!actions.available.includes(actions.possible[i])){
      thisIsDisabled = ' btn-warning whyNot'
      impossibleActionClass = ' impossible '
      whyNotStart = "<span id='whyNot-" + actions.possible[i] + "' class='whyNot'>"
      whyNotEnd = '</span>'
    }
    html += "<button  id='action-" + actions.possible[i]
      + "' class='m-2 action btn " + thisIsDisabled + " " + impossibleActionClass + "'>"
      + dedashify(actions.possible[i]) + "</button>"
  }
  $("#actionListing").html(html)
  if (lastAction == null && lastContractAction == null){
    $("#lastAction").prop('disabled', true)
  }
  if (automation != null){

    $(".action").prop('disabled', true)

    $("#lastAction").prop('disabled', true)
  }
  if (skills.construction.rank > 0){
    displayAvailableBuildings()
  }
  formatActions()
}

function displayFreelanceActions(){
  let html = ""
  let n = 0

  for (let i in contracts){
    if (contracts[i].category == 'freelance' && clacks >= contracts[i].price){
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
      || actions.available.includes(contracts[i].action))){
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
