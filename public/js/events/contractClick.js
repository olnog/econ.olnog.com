$(document).on('click', 'button.buildContract', function(e) {
  if ($("#buildContractBuilding-" + this.id.split('-')[1]).val() == ""){
    displayError("You still need to select which building you want to build.")
    return
  }
  construction(this.id.split('-')[1], $("#buildContractBuilding-" + this.id.split('-')[1]).val())
})

$(document).on('click', 'button.buyFromSellContract', function(e) {
  buyFromSellOrder(this.id.split('-')[1], this.id.split('-')[2])
})

$(document).on('click', 'button.buyFromSellOrder', function(e) {
  buyFromSellOrder(this.id.split('-')[1], this.id.split('-')[2])
})

$(document).on('click', 'button.buyLand', function(e) {
  console.log(this.id, this)
  buyLand(this.id.split('-')[1])
})



$(document).on('click', 'button.cancelContract', function(e) {
  if (confirm('Are you sure you want to cancel this contract?')){
    cancelContract(this.id.split('-')[1])
  }
})

$(document).on('click', 'button.cancelLease', function(e) {
  cancelLease(this.id.split('-')[1])
})

$(document).on('click', 'button.cancelBuildingLease', function(e) {
  cancelBuildingLease(this.id.split('-')[1])
})


$(document).on('click', 'button.freelance', function(e) {
  $("#lastAction").html($("#" + this.id).html())

  lastContractAction = {freelance: this.id.split('-')[1]}
  lastAction = null
  freelance(this.id.split('-')[1])
})

$(document).on('click', 'button.hire', function(e) {
  $("#lastAction").html($("#" + this.id).html())
  lastContractAction = {hire: this.id.split('-')[1]}
  lastAction = null
  hire(this.id.split('-')[1])
})

$(document).on('click', 'button.lease', function(e) {
  lease(this.id.split('-')[1])
})

$(document).on('click', 'button.leaseBuilding', function(e) {
  leaseBuilding(this.id.split('-')[1])
})


$(document).on('click', 'button.repairContract', function(e) {
  if ($("#repairContractBuilding-" + this.id.split('-')[1]).val() == ""){
    displayError("You still need to select which building you want to repair.")
    return
  }
  repairContract(this.id.split('-')[1], $("#repairContractBuilding-" + this.id.split('-')[1]).val())
})

$(document).on('click', '.reproduction', function(e) {
  reproduction(this.id.split('-')[1])
})

$(document).on('click', 'button.sellLand', function(e) {
  sellLand(this.id.split('-')[1])
})

$(document).on('click', 'button.sellToBuyOrder', function(e) {
  sellToBuyOrder(this.id.split('-')[1], this.id.split('-')[2])
})

$(document).on('click', 'button.sellToBuyContract', function(e) {
  sellToBuyOrder(this.id.split('-')[1], this.id.split('-')[2])
})

$(document).on('click', '.createContract', function(e) {
  let category = this.id.split('-')[1]
  window.location.href = '/contracts/create?category=freelance'
})
