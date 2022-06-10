function buyFromSellOrder(contractID, quantity, page){
  $.post( "/contracts/" + contractID, {type: 'buyFromSellOrder', quantity: quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    if (page == 'items'){
      loadPage('items')
      return
    }
    fetchContracts('items')
    //is this item page or market page?
  })
}

function buyLand(contractID, page){
  $.post( "/contracts/" + contractID, {type: 'buyLand',
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    if (page=='land'){
      fetchLand()
      return
    }
    fetchContracts('land')
  })
}

function cancelBuildingLease(contractID){
  $.post( "/buildingLease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    fetchContracts('mine')
    displayHeaders(JSON.parse(data).info)

  })
}

function cancelLease(contractID){
  $.post( "/lease/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    fetchContracts('mine')
    displayHeaders(JSON.parse(data).info)

  })
}

function cancelContract(contractID){
  $.post( "/contracts/" + contractID, {
    _token: fetchCSRF(), _method: 'DELETE' }).done(function(data){
    status(JSON.parse(data).status)
    fetchContracts('mine')
    displayHeaders(JSON.parse(data).info)

  })
}

function contractBuild(contractID, buildingName, page){
  $.post( "/contracts/" + contractID, {type: 'build', buildingName: buildingName, _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    if (page == 'actions'){
      loadPage('actions')
      return
    }
    fetchContracts('labor')
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
      displayHeaders(JSON.parse(data).info)

      if (page == 'actions'){
        loadPage('actions')
        return
      }
      fetchContracts('actions')
  })
}

function hire(contractID, page){
  $.post( "/contracts/" + contractID, {type: 'hire', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        return
      }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    if (page == 'actions'){
      loadPage('actions')
      return
    }
    fetchContracts('actions')
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
    fetchContracts('land')
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
    status(JSON.parse(data).status)
    fetchContracts('buildings')
  })

}

function repairContract(contractID, buildingID, page){
  $.post( "/contracts/" + contractID, {type: 'repair', buildingID: buildingID, _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
      console.log(data)
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)
    if (page == 'actions'){
      loadPage('actions')
      return
    }
    fetchContracts('labor')
  })
}

function reproduction(contractID){
  $.post( "/contracts/" + contractID, {type: 'reproduction', _token: fetchCSRF(),
    _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)

      return
    }
    displayHeaders(JSON.parse(data).info)

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

function sellToBuyOrder(contractID, quantity, page){
  $.post( "/contracts/" + contractID, {type: 'sellToBuyOrder', quantity:quantity,
    _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)

        return
      }
    status(JSON.parse(data).status)
    displayHeaders(JSON.parse(data).info)

    if (page == 'items'){
      loadPage('items')
      return
    }
    fetchContracts('items')

  })
}
