function areTheyLeasingBuilding(buildingID){
  for (let i in buildings.leases){
    if (buildings.leases[i].buildingID == buildingID){
      return true
    }
  }
  return false
}

function buildBuilding(buildingName){
  $.post( "/buildings", {buildingName: buildingName, _token: fetchCSRF() }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    status(JSON.parse(data).status)
    loadPage('actions')
  })
}

function destroyBuilding(buildingID){
  $.post( "/buildings/" + buildingID, {buildingID: buildingID, _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
/*
    actions = JSON.parse(data).actions
    buildings = JSON.parse(data).buildings
    itemCapacity = JSON.parse(data).itemCapacity
    numOfItems = JSON.parse(data).numOfItems
    */
    status(JSON.parse(data).status)
    loadPage('buildings')
    /*
    refreshUI()
*/
  })
}

function doTheyHaveThisBuilding(buildingName){
  for (let i in buildings.built){
    if (buildings.built[i].name == buildingName){
      return true
    }
  }
  return false
}

function fetchBuilding(buildingID){
  for (let i in buildings.built){
    if (buildings.built[i].id == buildingID){
      return buildings.built[i]
    }
  }
  return null
}

function rebuild(buildingID){
  $.post( "/buildings/" + buildingID, {buildingID: buildingID, action:'rebuild',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    status(JSON.parse(data).status)
    actions = JSON.parse(data).actions
    buildings = JSON.parse(data).buildings
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    numOfItems = JSON.parse(data).numOfItems
    refreshUI()
  })
}

function repair(buildingID){
  $.post( "/buildings/" + buildingID, {buildingID: buildingID, action:'repair', _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    status(JSON.parse(data).status)
    loadPage('buildings')
/*
    actions = JSON.parse(data).actions
    buildings = JSON.parse(data).buildings
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    numOfItems = JSON.parse(data).numOfItems
    refreshUI()
    */
  })
}

function searchBuildingTypes(buildingTypeID){
  for(let i in buildings.possible){
    if (buildings.possible[i].id == buildingTypeID){
      return buildings.possible[i]
    }
  }
  return null
}
