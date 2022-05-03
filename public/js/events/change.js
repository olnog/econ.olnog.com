$(document).on('change', '#buildingsThatCanBeBuilt', function(e) {
/*
$("#build").attr('disabled', true)

  $("#buildingCosts").html('')
*/
  if ($("#buildingsThatCanBeBuilt").val() != ""){
    /*
    html = "<span class='fw-bold'>Building Cost: </span> "
    for (let i in buildings.costs[$("#buildingsThatCanBeBuilt").val()]){
      html += " " + i + ":" + buildings.costs[$("#buildingsThatCanBeBuilt").val()][i] + " "
    }
    $("#buildingCosts").html(html)
    */
    $("#build").removeAttr('disabled')
  }

})

$(document).on('change', '.buyUntil', function(e) {
  $("#buyCondition").addClass('d-none')
  if ($('.buyUntil:checked').val() != 'gone'){
    $("#buyCondition").removeClass('d-none')
  }
})

$(document).on('change', '.contractCategory', function(e) {
  $(".contractSection").addClass('d-none')
  if ($("#contractCategory:checked").val() == 'sellLand' && !doTheyOwnLand()){
    $("#contractError").html("You don't own any land to sell. (Explore.)")
    return

  } else   if ($("#contractCategory:checked").val() == 'repair' && buildings.built.length == 0){
      $("#contractError").html("You don't have any buildings to repair.")
      return;
  } else if (clacks == 0 && ($("#contractCategory:checked").val() == 'buyOrder'
    || $("#contractCategory:checked").val() == 'hire'
    || $("#contractCategory:checked").val() == 'construction'
    || $("#contractCategory:checked").val() == 'buyLand' ) ){
    $("#contractError").html("You don't have any money for this contract.")
    return;
  } else {
    $("#contractError").html('')
  }
  console.log("#" + $(".contractCategory:checked").val() + "Section")
  $("#" + $(".contractCategory:checked").val() + "Section").removeClass('d-none')
})

$(document).on('change', '.contractFilter', function(e) {

formatContracts()

})

$(document).on('change', '.contractFilterByCategory', function(e) {
  filterContractsByType()
})

$(document).on('change', '#contractItemFilter', function(e) {
  console.log($("#contractItemFilter").val())
  $(".contracts").removeClass('d-none')
  if ($("#contractItemFilter").val() != ''){
    $(".contracts").addClass('d-none')
    $(".itemClass" + $("#contractItemFilter").val()).removeClass('d-none')
    return
  }
})

$(document).on('change', '#contractLandFilter', function(e) {
  console.log($("#contractLandFilter").val())
  $(".contracts").removeClass('d-none')
  if ($("#contractLandFilter").val() != ''){
    $(".contracts").addClass('d-none')
    $("." + $("#contractLandFilter").val()).removeClass('d-none')
    return
  }
})

$(document).on('change', '.formatActions', function(e) {
  formatActions()

})

$(document).on('change', '.filterActionTypes', function(e) {
  $(".noDo").removeClass('d-none')
  if ($("#showOnlyActionsYouCanDo").is(':checked')){
    $(".noDo").addClass('d-none')
  }

})

$(document).on('change', '.filterBuildings', function(e) {
  formatBuildings()

})

$(document).on('change', '#filterChat', function(e) {
  formatChat()
})

$(document).on('change', '#hideEmptySkills', function(e) {
  formatSkills()
})

$(document).on('change', '.historyFilter', function(e) {
  $(".history").addClass('d-none')

  $('.historyFilter').each(function (e) {
    if ($(this).is(':checked')){
      $("." + $(this).val()).removeClass('d-none')
    }
  })

})

$(document).on('change', '.stateFilter', function(e) {
  formatBuyOrders()
})
$(document).on('change', '.landFetch', function(e) {
  fetchLand()
})

$(document).on('change', '.landFilter', function(e) {
  formatLand()
})

$(document).on('keydown', '#landOwnerFilter', function(e) {
  if (e.which == 13 || $("#landOwnerFilter").val() == ''){
    formatLand()
  }

})

$(document).on('change', '.sellUntil', function(e) {
  $("#sellCondition").addClass('d-none')
  if ($('.sellUntil:checked').val() != 'gone'){
    $("#sellCondition").removeClass('d-none')
  }
})

$(document).on('change', '.settings', function(e) {
  let soundSetting = $("#soundSetting").is(':checked')
  let eatFoodSetting = $("#eatFoodSetting").is(':checked')
  let useHerbMedsSetting = $("#useHerbMedsSetting").is(':checked')
  let useBioMedsSetting = $("#useBioMedsSetting").is(':checked')
  let useNanoMedsSetting = $("#useNanoMedsSetting").is(':checked')
  adjustSettings(soundSetting, eatFoodSetting, useHerbMedsSetting, useBioMedsSetting, useNanoMedsSetting)

})
$(document).on('change', '#showDump', function(e) {
  formatItems()

})

$(document).on('change', '#showOnlyInventory', function(e) {
  $(".noQuantity").removeClass('d-none')
  if (this.checked){
    $(".noQuantity").addClass('d-none')
  }
})
$(document).on('change', '#stateSort', function(e) {
  fetchBuyOrders()
})

$("#workHoursCent").keyup(function() {
  updateWorkHoursStop()
})
