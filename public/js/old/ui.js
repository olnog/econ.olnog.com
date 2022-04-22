function displayActions(){
  html = ""
  if (skills.construction.rank > 0 ){
    html = " <div><button disabled id='build' class='btn btn-primary'>build</button>"
      + "<select id='possibleBuildings' class='d-none'><option selected></option></select>"
  }
  //display actions that cannot be done and why
  for (i in actions){
    if (!isThisActionPossible(actions[i])){
      continue
    }
    let thisIsDisabled = ''
    if (!isThisActionAvailable(actions[i])){
      thisIsDisabled = ' disabled '
    }
    html += "<div><button " + thisIsDisabled + " id='action-" + actions[i] + "' class='action btn btn-primary'>"
      + dedashify(actions[i]) + "</button></div>"
  }
  $("#actionListing").html(html)
  if (skills.construction.rank > 0){
    displayAvailableBuildings()
  }
}

function displayAvailableBuildings(){
  html = "<option></option>"
  for (i in buildingCosts){
    let canTheyBuildThis = true
    for (material in buildingCosts[i]){
      if (items[material] < buildingCosts[i][material]){
        canTheyBuildThis = false
        break
      }
    }
    if (canTheyBuildThis){
      $("#possibleBuildings").removeClass('d-none')
      html += "<option value='" + i + "'>" + i +  "</option>"
    }
  }
  $("#possibleBuildings").html(html)
}

function displayBuiltBuildings(){
  let html = ""
  for (i in buildings){
    html += "<div> " + buildings[i].durabilityCaption + " " + i + " (" + (buildings[i].uses / buildings[i].totalUses) * 100 + "%) "
  }
  $("#buildingListings").html(html)
}

function displayBuyOrders(){
  html = ""
  for (i in buyOrders){
    sellAs = "sell " + buyOrders[i].quantity + " " + i + " to The State (+" + buyOrders[i].cost + " clacks)"
    sellCaption = "<button disabled id='sell-" + i
      + "' class='sell btn btn-link me-3'>[ sell ]</button>"
    if (items[i] >= buyOrders[i].quantity){
      sellCaption = "<button id='sell-" + i
        + "' class='sell btn btn-link me-3'>[ sell ]</button>"
    }
    html += "<div>" + sellCaption +  "The State wants to buy "
      + buyOrders[i].quantity  + " <span class='fw-bold'>" + i + "</span> for " + buyOrders[i].cost
      + " clacks.</div>"
  }
  $("#state").html(html)
}

function displayItems(){
  let html = ""
  for (i in items){
    let buttonCaption = " "
    if ((i.split(' ')[2] == 'pickaxe' || i.split(' ')[2] == 'axe'
    || i.split(' ')[2] == 'saw') && items[i] > 0){
      buttonCaption = "<button id='equip-" + i.replaceAll(" ", "-") + "' class='equip'> equip </button> "
    }
    let sellCaption = ""
    if (items[i] >= buyOrders[i].quantity){
      sellCaption = "<button id='sell-" + i + "' class='sell btn btn-link'>[ sell ]</button>"
    }
    html += "<div>" + i + ": " + items[i] + buttonCaption + sellCaption + "</div>"
  }
  $("#itemListings").html(html)
}

function displayLand(){
  html = ""
  for (i in land){
    html += "<div>" + i + ": " + land[i] + "</div>"
  }
  $("#land").html(html)
}

function displaySkills(){
  let html = ""
  for (i in skills){
    buttonCaption = ""
    if (availableSkillPoints > skills[i].rank && skills[i].rank != 5){
      buttonCaption = "<button id='incrementSkill-" + i + "' class='incrementSkill btn btn-link'>+</button>"
    }
    html += "<div>" + skills[i].caption + ": " + skills[i].rank + buttonCaption
      + "</div>"
  }
  $("#skillListing").html(html)
}

function refreshUI(){
  $("#clacks").html(clacks)
  $("#availableSkillPoints").html(availableSkillPoints)
  $("#workHours").html(workHours.toLocaleString())
  let percentCaption = ""
  if (equipped.caption != 'nothing'){
    (percentCaption = equipped.uses / equipped.totalUses * 100).toFixed(2)
  }
  $("#equipped").html(equipped.caption + " (" + percentCaption + "%)")
  displayBuiltBuildings()
  displayLand()
  displaySkills()
  displayItems()
  displayActions()
  displayBuyOrders()
  $("#skillPointProgress").attr('aria-valuenow', workHoursLearning)
  let progressCent = ($("#skillPointProgress").attr('aria-valuenow') / $("#skillPointProgress").attr('aria-valuemax')).toFixed(2) * 100
  $("#skillPointProgress").css('width', progressCent + "%")

  items.food > 0
    ? $("#eatFood").removeAttr('disabled')
    : $("#eatFood").attr('disabled', true)
  
}
