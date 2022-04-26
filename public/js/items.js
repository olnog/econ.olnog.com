function doTheyHaveItemsQuant(itemTypeID, quantity){
  for (let i in items){
    if (items[i].itemTypeID == itemTypeID && items[i].quantity >= quantity){
      return true
    }
  }
  return false
}

function dump(itemID, quantity){
  $.post( "/items/" + itemID, {itemID: itemID, _token: fetchCSRF(), quantity: quantity, _method:'DELETE'}).done(function(data){
    items = JSON.parse(data).items
    numOfItems = JSON.parse(data).numOfItems
    refreshUI()
  })
}

function equipItem(itemID){
  $.post( "/labor", {itemID: itemID, _token: fetchCSRF() }).done(function(data){
    actions = JSON.parse(data).actions
    items = JSON.parse(data).items
    equipment = JSON.parse(data).equipment
    labor = JSON.parse(data).labor
    status(JSON.parse(data).status)

    refreshUI()
  })
}

function fetchBuyOrderForItemType(itemTypeID){
  for (let i in buyOrders){
    if (buyOrders[i].itemTypeID == itemTypeID){
      return buyOrders[i].id
    }
  }
  return null
}

function fetchFood(){
  for (let i in items){
    if (items[i].name == 'Food'){
      return items[i]
    }
  }
  return null
}

function fetchItemName(itemTypeID){
  for (i in itemTypes){
    if (itemTypes[i].id == itemTypeID){
      materialCaption = '';
      if (itemTypes[i].material !=  null){
        materialCaption = "(" + itemTypes[i].material + "/" + itemTypes[i].durability + ")"
      }
      return itemTypes[i].name + materialCaption
    }
  }
  return null
}


function fetchItemTypeIDByName(itemName){
  for (let i in itemTypes){
    if (itemTypes[i].name == itemName){
      return itemTypes[i].id
    }
  }
  return null
}

function howManyItems(itemTypeID){
  for (let i in items){
    if(items[i].itemTypeID == itemTypeID){
      return items[i].quantity
    }
  }
  return 0
}
