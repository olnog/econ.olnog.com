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
  $("#autoActions").html(autoActions)
  $("#autoBribe").val(autoBribe)
  $("#autoWorkStart").addClass('d-none')
  if (stopWorkHours > 0){
    //$("#autoWorkStart").removeClass('d-none')
  }
  $("#avgBribe").html(avgBribe)
  $("#clacks").html(clacks.toLocaleString())
  $("#availableSkillPoints").html(labor.availableSkillPoints)
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
  $("#numOfContracts").html(contracts.length)
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
  $("#numOfItems").html(numOfItems.toLocaleString())
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
  $("#skillPointProgress").attr('aria-valuenow', 0)
  $("#skillPointProgress").attr('aria-valuemax', labor.actionsUntilSkill)
  if (labor.allocatedSkillPoints + labor.availableSkillPoints < labor.maxSkillPoints){
    $("#skillPointProgress").attr('aria-valuenow', labor.actions)
    $("#skillPointProgress").attr('aria-valuemax', labor.actionsUntilSkill)
  }
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
  if (skills.robotics.rank > 0 && doTheyHaveItemsQuant(fetchItemTypeIDByName('Robots'), 1)){
    displayRobotSkill()
  }
}
