function doTheyAlreadyHaveThisAsEquipment(itemID){
  for (let i in items){
    if (items[i].id == itemID){
      for(let n in equipment){
        if (equipment[n].itemTypeID == items[i].itemTypeID){
          return true
        }
      }
    }
  }
  return false
}

function equipEquipment(equipmentID){
  $.post( "/equipment", {equipmentID: equipmentID, _token: fetchCSRF() }).done(function(data){
    loadPage('actions')
  })
}

function fetchEquipped(){
  for (i in equipment){
    if (equipment[i].id == labor.equipped){
      return equipment[i]
    }
  }
}

function fetchSpecialEquipped(){
  for (i in equipment){
    if (equipment[i].id == labor.alsoEquipped){
      return equipment[i]
    }
  }
}
