$(document).on('click', 'button.buildContract', function(e) {
  if ($("#buildContractBuilding-" + e.target.id.split('-')[1]).val() == ""){
    displayError("You still need to select which building you want to build.")
    return
  }
  construction(e.target.id.split('-')[1], $("#buildContractBuilding-" + e.target.id.split('-')[1]).val())
})

$(document).on('click', 'button.buyFromSellContract', function(e) {
  buyFromSellOrder(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', 'button.buyFromSellOrder', function(e) {
  buyFromSellOrder(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', 'button.buyLand', function(e) {
  buyLand(e.target.id.split('-')[1])
})



$(document).on('click', 'button.cancelContract', function(e) {
  if (confirm('Are you sure you want to cancel this contract?')){
    cancelContract(e.target.id.split('-')[1])
  }
})

$(document).on('click', 'button.cancelLease', function(e) {
  cancelLease(e.target.id.split('-')[1])
})

$(document).on('click', 'button.cancelBuildingLease', function(e) {
  cancelBuildingLease(e.target.id.split('-')[1])
})


$(document).on('click', 'button.freelance', function(e) {
  $("#lastAction").html($("#" + e.target.id).html())

  lastContractAction = {freelance: e.target.id.split('-')[1]}
  lastAction = null
  freelance(e.target.id.split('-')[1])
})

$(document).on('click', 'button.hire', function(e) {
  $("#lastAction").html($("#" + e.target.id).html())
  lastContractAction = {hire: e.target.id.split('-')[1]}
  lastAction = null
  hire(e.target.id.split('-')[1])
})

$(document).on('click', 'button.lease', function(e) {
  lease(e.target.id.split('-')[1])
})

$(document).on('click', 'button.leaseBuilding', function(e) {
  leaseBuilding(e.target.id.split('-')[1])
})


$(document).on('click', 'button.repairContract', function(e) {
  if ($("#repairContractBuilding-" + e.target.id.split('-')[1]).val() == ""){
    displayError("You still need to select which building you want to repair.")
    return
  }
  repairContract(e.target.id.split('-')[1], $("#repairContractBuilding-" + e.target.id.split('-')[1]).val())
})

$(document).on('click', '.reproduction', function(e) {
  reproduction(e.target.id.split('-')[1])
})

$(document).on('click', 'button.sellLand', function(e) {
  sellLand(e.target.id.split('-')[1])
})

$(document).on('click', 'button.sellToBuyOrder', function(e) {
  sellToBuyOrder(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', 'button.sellToBuyContract', function(e) {
  sellToBuyOrder(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', '.createContract', function(e) {
  let category = this.id.split('-')[1]
  window.location.href = '/contracts/create?category=freelance'
})
