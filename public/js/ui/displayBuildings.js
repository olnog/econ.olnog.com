function checkNewBuildings(){
  $("#newBuildingAvailable").addClass('d-none')

  if (actions.buildings == null){
    return
  }
  if (oldBuildings == null){
    oldBuildings = actions.buildings
  }
  if (actions.buildings.length != oldBuildings.length){
    $("#newBuildingAvailable").removeClass('d-none')
    oldBuildings = actions.buildings
  }
}

function displayAvailableBuildings(){ //11
  if (actions.buildings.length < 1){
    $("#buildingCosts").addClass('d-none')
    return
  }

  $("#build").removeClass('d-none')
  $('#buildingsThatCanBeBuilt').removeClass('d-none')
  html = "<option></option>"
  for (i in actions.buildings){
      html += "<option value='" + actions.buildings[i] + "'>" + actions.buildings[i] +  "</option>"
  }
  $("#buildingsThatCanBeBuilt").html(html)
}

function displayBuiltBuildings(){ //30
  if (!doTheyOwnLand()){
    $("#buildingWarning").html("You need to have land in order to build. (Explore to find land - or buy / lease some. Or launch a hostile takeover of a parcel.)")
  }
  $(".builtBuildings").html(buildings.built.length)
  let html = ""
  for (let i in buildings.built){

    let repairButton = ""
    let rebuildButton = ''
    let fieldsClass = ''
    if (buildings.built[i].uses < buildings.built[i].totalUses
        && buildings.repairable.includes(buildings.built[i].id)
      && doTheyHaveThisActionUnlocked('repair')){
      repairButton = "<button id='repair-" + buildings.built[i].id
        + "' class='m-3 repair btn btn-info'>repair</button>"
    }
    if (buildings.built[i].uses < buildings.built[i].totalUses && doTheyHaveThisActionUnlocked('build')){
      rebuildButton = "<button id='rebuild-" + buildings.built[i].id + "' class='rebuild m-3 btn btn-primary'>rebuild</button>"
    }
    let leaseBuilding = "<a href='/contracts/create?category=leaseBuilding&buildingID="
    + buildings.built[i].id + "' class='btn ms-3 createContract'>"
    + "<img src='/img/icons8-rent-30.png'></a>"
    if (areTheyLeasingBuilding(buildings.built[i].id)){
      leaseBuilding = " - already leasing"
    }

    let buildingDiv = buildings.built[i].name
    + " (" + ((buildings.built[i].uses / buildings.built[i].totalUses) * 100).toFixed(2)
    + "%)" + leaseBuilding
    if (buildings.built[i].farming){
      fieldsClass = " fields "
      buildingDiv = buildings.built[i].name
      let t = buildings.built[i].harvestAfter.split(/[- :]/);
      let d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
      let rightNow = Date.now()
      let hoursUntilNow = Math.round((d-rightNow) / 1000/ 60 / 60 * 10) / 10;
      if (hoursUntilNow > 0){
        buildingDiv += " (" + hoursUntilNow + "h until harvest)"
      }
    }
    html += "<div class='mt-3" + fieldsClass + "'><div>"
    + buildingDiv +  "</div>"
    +"<div><button id='destroyBuilding-"
    + buildings.built[i].id
    + "' class='destroyBuilding m-3 btn btn-warning me-3'>destroy</button>"
    + repairButton + rebuildButton + "</div></div>"
  }
  $("#buildingListings").html(html)
  formatBuildings()
}


function displayPossibleBuildings(){ //15
  let html = ""
  for (let i in buildings.possible){
    let hideMe = ''
    if (doTheyHaveThisBuilding(buildings.possible[i].name)){
      hideMe ='d-none'
    }
    html += "<div class='" + hideMe + "'><div class='mt-3 fw-bold '>" + buildings.possible[i].name
      + "</div><div><span class=''>Building Cost:</span> "
      + buildings.possible[i].cost +"</div><div><span class=''>Associated Skill:</span> "
      + buildings.possible[i].skill + "</div><div><span class=''>Associated Action(s):</span> "
      + buildings.possible[i].actions + "</div><div>"
    + buildings.possible[i].description +"</div></div>"
  }
  $("#possibleBuildings").html(html)
}
