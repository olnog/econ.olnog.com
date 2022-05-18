$(document).on('click', 'button', function(e) {
  buttonMetric.push(e.target.id)
})
$(document).on('keyup', '#chatContent', function(e) {
  if(e.key == 'Enter'){
    sendChat($("#chatContent").val())
    $("#chatContent").val('')
  }
})

$(document).on('click', '.bid', function(e) {
  bid(e.target.id.split('-')[1], $("#bidAmount").val())
})


$(document).on('click', '#cancelFeedback', function(e) {
  $(".main").removeClass('d-none')
  $("#feedbackScreen").addClass('d-none')
})

$(document).on('click', '#chatSend', function(e) {
  sendChat($("#chatContent").val())
  $("#chatContent").val('')
})

$(document).on('click', '#clearLandOwnerFilter', function(e) {
  $("#landOwnerFilter").val('')
  formatLand()
})

$(document).on('click', '.destroyBuilding', function(e) {
  destroyBuilding(e.target.id.split('-')[1])
})

$(document).on('click', '.dump', function(e) {
  dump(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', '.equipItem', function(e) {
  let sameEquipment = doTheyAlreadyHaveThisAsEquipment(e.target.id.split('-')[1])
  if ( (sameEquipment && confirm('You already have this type of item as equipment. Are you sure?'))
    || !sameEquipment){
    equipItem(e.target.id.split('-')[1])
  }
})

$(document).on('click', '.equipEquipment', function(e) {
  equipEquipment(e.target.id.split('-')[1])
})

$(document).on('click', '.equipLabor', function(e) {
  equipEquipment(e.target.id.split('-')[1])
})

$(document).on('click', '.feedback', function(e) {
  $(".main").addClass('d-none')
  $("#feedbackScreen").removeClass('d-none')
})

$(document).on('click', '.filterByOwner', function(e) {
  $("#landOwnerFilter").val($(this).html())
  formatLand()
})


$(document).on('click', '.goToLand', function(e) {
  $(".menu").removeClass('active')
  $("#landNav").addClass('active')
  $(".otherPages").addClass('d-none')
  $("#land").removeClass('d-none')
  window.location.hash = '#parcel' + e.target.id.split('-')[1];

})


$(document).on('click', '.market', function(e) {

  $(".market").removeClass('active')
  $(this).addClass('active')
  fetchContracts(this.innerHTML)
  /*
  if ($(".nav-link.market.active").html() != 'items' && $("#contractItemFilter").val() != ""){
    //this is a very hacky solution to the issue I was having with
    //itemTypeFilter select list basically screwing up all the other tabs

    return
  }
  */
  //formatContracts()
})


$(document).on('click', '.hide', function(e) {
  $("#show-" + e.target.id.split('-')[1]).removeClass('d-none')
  $("#hide-" + e.target.id.split('-')[1]).addClass('d-none')
  $("#" + e.target.id.split('-')[1]).addClass('d-none')
})

$(document).on('click', '.decrementSkill', function(e) {
    decrementSkill(e.target.id.split('-')[1])
})

$(document).on('click', '.incrementSkill', function(e) {
  if (labor.availableSkillPoints > skills[e.target.id.split('-')[1]].rank && skills[e.target.id.split('-')[1]].rank != 5){
    incrementSkill(e.target.id.split('-')[1])
  }
})

$(document).on('click', ".menu", function(e) {
  buttonMetric.push(e.target.innerHTML)
  uploadMetric()
  $(".menu").removeClass('active')
  $(this).addClass('active')
  $(".otherPages").addClass('d-none')
  $("#" + e.target.innerHTML).removeClass('d-none')
  loadPage(e.target.innerHTML)
})

$(document).on('click', '.payBribe', function(e) {
  payBribe(e.target.id.split('-')[1], e.target.id.split('-')[2])
})

$(document).on('click', '.payAllBribes', function(e) {
  payAllBribes(e.target.id.split('-')[1])
})

$(document).on('click', '#programRobot', function(e) {
  robot.program($("#robotActionList").val())
})

$(document).on('click', '#quitForcedSkillScreen', function(e) {
  hideForcedSkillScreen()
  refreshUI()
})

$(document).on('click', '#readBook', function(e) {
  readBook()
})

$(document).on('click', '.reprogram', function(e) {
  robot.reprogram($("#reprogramList-" +  e.target.id.split('-')[1]).val(), e.target.id.split('-')[1])
})

$(document).on('click', '.rebuild', function(e) {
  rebuild(e.target.id.split('-')[1])
})


$(document).on('click', '.repair', function(e) {
  repair(e.target.id.split('-')[1])
})



$(document).on('click', '#reset', function(e) {
  if (confirm('You will reset your skills but you will no longer have anything. Are you sure you want to do this?')){
    reset()
  }
})

$(document).on('click', '#robotStart', function(e) {
  robot.start()
})

$(document).on('click', '#robotStop', function(e) {
  robot.stop()
})

$(document).on('click', '.sellToState', function(e) {
  let buyOrderID = e.target.id.split('-')[1]
  sellToState(buyOrderID)
})

$(document).on('click', '.sellToStateFromItems', function(e) {
  let buyOrderID = e.target.id.split('-')[1]
  sellToState(buyOrderID)
})
$(document).on('click', '#setAutoBribe', autoBribe)


$(document).on('click', '.show', function(e) {
  $("#hide-" + e.target.id.split('-')[1]).removeClass('d-none')
  $("#show-" + e.target.id.split('-')[1]).addClass('d-none')
  $("#" + e.target.id.split('-')[1]).removeClass('d-none')
})

$(document).on('click', '.takeover', function(e) {
  let landID = e.target.id.split('-')[1]
  let minBid = Math.round(Number(e.target.id.split('-')[2]) * 2)
  if (minBid < 1){
    minBid = 1
  }
  let amount = prompt("How much do you wish to spend to initiate this takeover? (You will not get this back.)", minBid);
  if (amount < minBid){
    displayError(amount + " clacks is too low. The minimum bid is " + minBid )
    return
  }
  takeover(landID, amount)
})

$(document).on('click', '.updateActionType', function(e) {
  $("#updateActionTypeForm")
    .html($("#updateActionTypeForm").html()
    + "<input type='hidden' name='_method' value='PUT'>")

  $("#actionName").val($("#actionTypeName" + e.target.id.split('-')[1]).html())
  $("#actionDescription").val($("#actionTypeDescription" + e.target.id.split('-')[1]).html())

  $("#createAction").html('update')
  $("#updateActionTypeForm").attr('action', '/actionTypes/' + e.target.id.split('-')[1] )
  window.location.hash = '#updateActionTypeForm';
})

$(document).on('click', '.updateBuildingType', function(e) {
  let buildingTypeID = e.target.id.split('-')[1]
  let buildingTypeName = $("#buildingName-" + buildingTypeID).html().trim()
  let buildingTypeSkill = $("#buildingSkill-" + buildingTypeID).html().trim()
  let buildingTypeActions = $("#buildingActions-" + buildingTypeID).html().trim()
  let buildingTypeCost = $("#buildingCost-" + buildingTypeID).html().trim()

  let buildingTypeDescription = $("#buildingDescription-" + buildingTypeID).html().trim()
  $("#updateBuildingTypeForm").html($("#updateBuildingTypeForm").html() + "<input type='hidden' name='_method' value='PUT'>")
  $("#buildingName").val(buildingTypeName)
  $("#buildingDescription").val(buildingTypeDescription)
  $("#buildingSkill").val(buildingTypeSkill)
  $("#buildingActions").val(buildingTypeActions)
  $("#buildingCost").val(buildingTypeCost)

  $("#buildingTypeSubmit").html('update')
  $("#updateBuildingTypeForm").attr('action', '/buildingTypes/' + buildingTypeID )
  window.location.hash = '#updateBuildingTypeForm';
})

$(document).on('click', '.updateItemType', function(e) {
  let id = e.target.id.split('-')[1]
  $("#updateItemTypeForm").html($("#updateItemTypeForm").html() + "<input type='hidden' name='_method' value='PUT'>")

  $("#itemTypeNameInput").val($("#itemName" + id).html().trim())
  $("#itemTypeDescriptionInput").val($("#itemDescription" + id).html().trim())
  $("#itemTypeIDInput").val(id)
  $("#itemTypeSubmit").html('update')
  $("#updateItemTypeForm").attr('action', '/itemTypes/' + id )
  window.location.hash = '#updateItemTypeForm';


})

$(document).on('click', '.updateSkillType', function(e) {
  let skillTypeID = e.target.id.split('-')[1]
  let skillTypeName = $("#skillTypeName-" + skillTypeID).html().trim()
  let skillTypeDescription = $("#skillTypeDescription-" + skillTypeID).html().trim()
  $("#skillTypeForm").html($("#skillTypeForm").html() + "<input type='hidden' name='_method' value='PUT'>")
  $("#skillTypeName").val(skillTypeName)
  $("#identifier").val($("#skillTypeIdentifier-" + skillTypeID).html().trim())
  $("#skillTypeDescription").val(skillTypeDescription)
  $("#skillTypeSubmit").html('update')
  $("#skillTypeForm").attr('action', '/skillTypes/' + skillTypeID )
  window.location.hash = '#skillTypeForm';
})

$(document).on('click', '.useMeds', function(e) {
  useMeds(e.target.id.split("-")[1])
})

$(document).on('click', '#workHoursCent', function(e) {
  updateWorkHoursStop()

})
