function refreshUI(){
  //console.log('refresh')
  //console.log(labor.availableSkillPoints, labor.startingSkillPoints)

  /*
  if (!skipForcedSkillSCreen && labor.availableSkillPoints == labor.startingSkillPoints){

    $("#mainScreen").addClass('d-none')
    $(".forcedSkillScreen").removeClass('d-none')
    displaySkills("forcedSkillListing")
    return
  }
  */

  hideForcedSkillScreen()
  if(!doTheyOwnLand()){
    $(".payAllBribes").addClass('d-none')
  }
  if (currentskillPoints < labor.availableSkillPoints){
    playSkill()
  }
  $("#numOfPoints").html(labor.availableSkillPoints + labor.allocatedSkillPoints)
  $("#numOfParcels").html(numOfParcels)
  $("#autoActions").html(autoActions)
  $("#autoBribe").val(autoBribe)
  $("#autoWorkStart").addClass('d-none')
  $("#avgBribe").html(avgBribe)
  $("#clacks").html(clacks.toLocaleString())
  $("#availableSkillPoints").html(labor.availableSkillPoints)

  if (labor.availableSkillPoints > 0){
    $("#skillUnlocked").removeClass('d-none')
  }
  $("#laborItems").html(numOfItems.toLocaleString())
  $("#headerAvailableSkillPoints").html(labor.availableSkillPoints.toLocaleString())

  $("#pendingMaxSkillPoints").html(labor.pendingMaxSkillPoints)
  $("#pendingMaxSkillPointsSection").addClass('d-none')
  if (labor.pendingMaxSkillPoints > 0){
    $("#pendingMaxSkillPointsSection").removeClass('d-none')
  }
  $("#allocatedSkillPoints").html(labor.allocatedSkillPoints)
  $("#maxSkillPoints").html(labor.maxSkillPoints)
  $("#workHours").html(labor.workHours.toLocaleString())
  $("#numOfContracts").html(numOfContracts)
  $("#workHoursStop").html(stopWorkHours)
  if (hostileTakeover){
    $("#hostileTakeover").removeClass('d-none')
  }
  if (labor.equipped != null){
    let equippedItem = fetchEquipped()
    $("#equipped").html(equippedItem.name + " " + "(" + equippedItem.material
      + " / " + equippedItem.durability + ") "
      + (equippedItem.uses / equippedItem.totalUses * 100).toFixed(2) + "%")
  } else {
    $("#equipped").html('nothing')
  }

  if (labor.alsoEquipped != null){
    let specialEquippedItem = fetchSpecialEquipped()
    $("#specialEquipped").html(specialEquippedItem.name + " " +
      + (specialEquippedItem.uses / specialEquippedItem.totalUses * 100).toFixed(2) + "%")
  } else {
    $("#specialEquipped").html('nothing')
  }

  $("#numOfBuildingSlots").html(buildingSlots)
  if (numOfItems < 1000){
    $("#numOfItems").html(numOfItems.toLocaleString())
  } else if (numOfItems < 1000000){
    $("#numOfItems").html((numOfItems / 1000).toFixed(1).toLocaleString() + "k")

  } else if (numOfItems < 1000000000){
    $("#numOfItems").html((numOfItems / 1000000).toFixed(1).toLocaleString() + "m")

  }


  checkNewBuildings()
  let percentCaption = ""
  displayRobots()

  checkNewBuildings()
  displayPossibleBuildings()
  displayBuiltBuildings()
  displayLand()
  displaySkills('skillListing')
  displayContracts()
  displayChat()
  displayItems()
  displayActions()
  displayHireableActions()
  displayFreelanceActions()
  displayBuyOrders()
  displayEquipment()
  displayHistory()
  $("#skillPointProgress").attr('aria-valuemax', labor.actionsUntilSkill)
  $("#skillPointProgress").attr('aria-valuenow', labor.actions)
  let progressCent = ($("#skillPointProgress").attr('aria-valuenow') / $("#skillPointProgress").attr('aria-valuemax')).toFixed(2) * 100
  $("#skillPointProgress").css('width', progressCent + "%")
  $("#eatFood").prop('checked', settings.eatFood)
  $("#useHerbMeds").prop('checked', settings.useHerbMeds)
  $("#useBioMeds").prop('checked', settings.useBioMeds)
  $("#useNanoMeds").prop('checked', settings.useNanoMeds)

  doTheyHaveItemsQuant(3, 1)
    ? $("#eatFood").removeAttr('disabled')
    : $("#eatFood").attr('disabled', true)

let meds = ['BioMeds', 'HerbMeds', 'NanoMeds']
for (let i in meds){
  doTheyHaveItemsQuant(fetchItemTypeIDByName(meds[i]), 1)
    ? $("#use" + meds[i]).removeAttr('disabled')
    : $("#use" + meds[i]).attr('disabled', true)


}
  if (automation == null && fetchFood().quantity < 1 ){
    $("#startAutomation").prop('disabled', true)
  }
  $("#username").html(username)
  if (doTheyHaveThisActionUnlocked('program-robot') && doTheyHaveItemsQuant(fetchItemTypeIDByName('Robots'), 1)){
    displayRobotSkill()
  }
}
