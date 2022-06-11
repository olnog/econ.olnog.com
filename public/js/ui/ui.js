function fetchLand(){
  $.get("/land?landType=" + $("#landTypeFilter").val() + "&sort="
    + $("#landSortByFilter").val() , function(data){
    $("#land").html(data)

  })
}

function displayAutomation(){
  if (lastAction != null || lastContractAction != null){
    $("#lastAction").prop('disabled', false)
    if (food > 0){
      $("#startAutomation").prop('disabled', false)
    }
  }
  if (lastAction == null && lastContractAction == null ){
    $("#lastAction").prop('disabled', true)
    $("#startAutomation").prop('disabled', true)
    if (automation != null){
      stopAutomation()
    }
  }
  if (automation != null){
    $(".action").prop('disabled', true)
    $("#lastAction").prop('disabled', true)
  }
}


function displayHeaders(info){
  food = info.food
  $("#laborFood").html(info.food.toLocaleString())
  $("#buildingSlots").html(info.buildingSlots.toLocaleString())
  $("#clacks").html(info.clacks.toLocaleString())
  $("#numOfParcels").html(info.numOfParcels.toLocaleString())
  $("#numOfUnlocked").html(info.numOfUnlocked.toLocaleString())
  $("#numOfPoints").html(info.numOfActions.toLocaleString())
  $(".builtBuildings").html(info.numOfBuildings.toLocaleString())
  $("#numOfContracts").html(info.numOfContracts.toLocaleString())
  let numOfItems = 0
  if (info.numOfItems < 1000 ){
    numOfItems = (info.numOfItems).toLocaleString()
  } else if (info.numOfItems < 1000000 ){
    numOfItems = (info.numOfItems / 1000).toFixed(1).toLocaleString() + "k"
  } else if (info.numOfItems < 1000000000 ){
    numOfItems = (info.numOfItems / 1000000).toFixed(1).toLocaleString() + 'm'
  }
  $("#numOfItems").html(numOfItems)
  $("#username").html(info.username)
  settings = info.settings
  console.log(settings.sound)
}

function displayError(errorMsg){ //4
  $("#error").html(errorMsg)
  $("#status").html('&nbsp;')
  $("#error").addClass('fw-bold')
  setTimeout(function(){
    $("#error").removeClass('fw-bold')
  }, 1000)
}


function formatBuildings(){
  $(".fields").removeClass('d-none')
  if ($("#filterFields").is(':checked')){
    $(".fields").addClass('d-none')
  }
}

function formatBuyOrders(){
  $(".stateBuyOrders").removeClass('d-none')
  if ($("#noShowEmptyBuyOrders").is(":checked")){
    $(".unfillableBuyOrders").addClass('d-none')
  }
  if ($("#stateItemFilter").val() != ""){
      $(".stateBuyOrders").not(".stateItemType" + $("#stateItemFilter").val()).addClass("d-none")
  }

}



function formatItems(){
  $(".dump").addClass('d-none')
  if ($("#showDump").is(':checked')){
    $(".dump").removeClass('d-none')
  }
}

function formatLand(){
  $('.parcel').removeClass('d-none')
  if ($("[name='landFilter']:checked").val() == 'yours'){
    $(".parcel:not('.ownedLand')").addClass('d-none')
  } else if ($("[name='landFilter']:checked").val() == 'takeovers'){
    $(".parcel:not('.takeovers')").addClass('d-none')
  }
  if ($("#landOwnerFilter").val() != ''){
    $(".parcel:not(.ownedBy" + $("#landOwnerFilter").val() + ")").addClass('d-none')
  }
  if ($("[name='landTypeFilter']").val() == 'all') {
    return
  }
  $(".parcel:not(." + $("[name='landTypeFilter']").val() + ")").addClass('d-none')
}

function formatSkills(){
  $(".emptySkill").removeClass('d-none')
  if ($("#hideEmptySkills").is(":checked")){
    $(".emptySkill").addClass('d-none')
  }
}



function robotAnimation(){
  $("#robotAnimation").html($("#robotAnimation").html() + ".")
  if ($("#robotAnimation").html().length > 3){
    $("#robotAnimation").html('')
  }
}

function status(text){
  $("#status").addClass('fw-bold')
  $('.fn').addClass('text-danger')
  setTimeout(function(){
    $("#status").removeClass('fw-bold')


  }, 1000)
  $("#status").html(text)
  $("#error").html('&nbsp;')
}

function updateWorkHoursStop(){
  if ( isNaN($("#workHoursCent").val()) ){
      $("#workHoursStop").html(0)
      stopWorkHours = 0
      autoActions = null
    return
  }
  stopWorkHours = $("#workHoursCent").val()
  if (stopWorkHours != 0){
    autoActions = stopWorkHours
  }
}
