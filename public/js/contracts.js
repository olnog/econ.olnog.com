function buyFromSellOrder(contractID, quantity){
  $.post( "/contracts/" + contractID, {type: 'buyFromSellOrder', quantity: quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    loadPage('items')
    displayHeaders(JSON.parse(data).info)
    //is this item page or market page?
  })
}

function buyLand(contractID){
  $.post( "/contracts/" + contractID, {type: 'buyLand',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    fetchLand('land')
  })
}

function cancelBuildingLease(contractID){
  $.post( "/buildingLease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)

  })
}

function cancelLease(contractID){
  $.post( "/lease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)

  })
}

function cancelContract(contractID){
  $.post( "/contracts/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)

  })
}

function construction(contractID, buildingName){

  $.post( "/contracts/" + contractID, {type: 'construction', buildingName: buildingName, _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

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


function freelance(contractID){
  $.post( "/contracts/" + contractID, {type: 'freelance', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      console.log(data)
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)

        return
      }
      status(JSON.parse(data).status)
      loadPage('items')
  })
}

function hire(contractID){
  $.post( "/contracts/" + contractID, {type: 'hire', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        return
      }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
  })
}

function lease(contractID){
  $.post( "/contracts/" + contractID, {type: 'lease', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      window.location.hash = '#error'
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    fetchLand('land')
  })
}

function leaseBuilding(contractID){
  $.post( "/buildingLease/", {contractID: contractID, _token: fetchCSRF()}).done(function(data){
    if (JSON.parse(data).error != undefined){
      window.location.hash = '#error'
      displayError(JSON.parse(data).error)

      return
    }
    displayHeaders(JSON.parse(data).info)
    fetchLand('land')
  })

}


function reproduction(contractID){
  $.post( "/contracts/" + contractID, {type: 'reproduction', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    window.location.reload()
  })
}

function sellLand(contractID){
  $.post( "/contracts/" + contractID, {type: 'sellLand',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    fetchLand()
  })
}

function sellToBuyOrder(contractID, quantity){
  $.post( "/contracts/" + contractID, {type: 'sellToBuyOrder', quantity:quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)

        return
      }
    status(JSON.parse(data).status)
    loadPage('items')

  })
}
