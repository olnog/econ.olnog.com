function buyFromSellOrder(contractID, quantity){
  $.post( "/contracts/" + contractID, {type: 'buyFromSellOrder', quantity: quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}

function buyLand(contractID){
  $.post( "/contracts/" + contractID, {type: 'buyLand',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}

function cancelBuildingLease(contractID){
  $.post( "/buildingLease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    history = JSON.parse(data).history
    contracts = JSON.parse(data).contracts
    buildingLeases = JSON.parse(data).buildingLeases
    refreshUI()
  })
}

function cancelLease(contractID){
  $.post( "/lease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    history = JSON.parse(data).history
    contracts = JSON.parse(data).contracts
    leases = JSON.parse(data).leases
    refreshUI()
  })
}

function cancelContract(contractID){
  $.post( "/contracts/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    history = JSON.parse(data).history
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}

function construction(contractID, buildingName){

  $.post( "/contracts/" + contractID, {type: 'construction', buildingName: buildingName, _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return;
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })

}


function haveTheyAlreadyLeased(contractID){
  for (let i in leases){
    if (leases[i].contractID == contractID){
      return true
    }
  }
  return false
}

function fetchContracts(){
  $.get('/contracts', function(data){
    contracts = JSON.parse(data)
    displayContracts()
  })
}

function freelance(contractID){
  $.post( "/contracts/" + contractID, {type: 'freelance', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        fetchContracts()
        return
      }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    labor = JSON.parse(data).labor
    refreshUI()
  })
}

function hire(contractID){
  $.post( "/contracts/" + contractID, {type: 'hire', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        fetchContracts()
        return
      }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    labor = JSON.parse(data).labor
    refreshUI()
  })
}

function isThereABuyContract(itemTypeID){
  let buyContract = {cost: null, id: null}
  for (let i in contracts){
    if (contracts[i].userID != userID && contracts[i].category == 'buyOrder'
      && contracts[i].itemTypeID == itemTypeID
      && (buyContract.cost == null || buyContract.cost < contracts[i].price)){
      buyContract.cost = contracts[i].price
      buyContract.id = contracts[i].id
    }
  }
  return buyContract
}

function isThereASellContract(itemTypeID){
  let sellContract = {cost: null, id: null}
  for (let i in contracts){
    if (contracts[i].userID != userID && contracts[i].category == 'sellOrder'
      && contracts[i].itemTypeID == itemTypeID
      && (sellContract.cost == null || sellContract.cost > contracts[i].price)){
      sellContract.cost = contracts[i].price
      sellContract.id = contracts[i].id
    }
  }
  return sellContract
}

function lease(contractID){
  $.post( "/contracts/" + contractID, {type: 'lease', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      window.location.hash = '#error'
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    leases = JSON.parse(data).leases
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}

function leaseBuilding(contractID){
  $.post( "/buildingLease/", {contractID: contractID, _token: fetchCSRF()}).done(function(data){
    if (JSON.parse(data).error != undefined){
      window.location.hash = '#error'
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }

    buildingLeases = JSON.parse(data).buildingLeases
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()

  })

}

function repairContract(contractID, buildingID){
  console.log(contractID, buildingID)

  $.post( "/contracts/" + contractID, {type: 'repair', buildingID: buildingID,  _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })

}

function reproduction(contractID){
  $.post( "/contracts/" + contractID, {type: 'reproduction', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    labor = JSON.parse(data).labor
    refreshUI()
    window.location.reload()
  })
}

function sellLand(contractID){
  $.post( "/contracts/" + contractID, {type: 'sellLand',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      fetchContracts()
      return
    }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}

function sellToBuyOrder(contractID, quantity){
  $.post( "/contracts/" + contractID, {type: 'sellToBuyOrder', quantity:quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        fetchContracts()
        return
      }
    status(JSON.parse(data).status)
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    actions = JSON.parse(data).actions
    contracts = JSON.parse(data).contracts
    refreshUI()
  })
}
