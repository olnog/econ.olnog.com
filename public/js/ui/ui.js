function displayBuyOrders(){ //23
  html = ""
  let buyOrderItems = {}
  for (let i in buyOrders){
    buyOrderItems[buyOrders[i].name] = buyOrders[i].itemTypeID
    let t = buyOrders[i].updated_at.split(/[- :]/);

    let d = new Date(Date.UTC(t[0], t[1]-1, t[2].substring(0, 2), t[2].substring(3), t[3], t[4].substring(0, 2)));

    let hoursOld = Math.round((Date.now()-d) / 1000/ 60 / 60 * 10) / 10;
    sellAs = "sell " + buyOrders[i].quantity.toLocaleString() + " "
      + buyOrders[i].name + " to The State (+" + buyOrders[i].cost.toLocaleString() + " clacks)"
    sellCaption = "<button disabled id='sellToState-" + buyOrders[i].id
      + "' class='sellToState btn btn-link me-3'>[ sell ]</button>"
    toolCaption = ""
    hideDiv = 'unfillableBuyOrders d-none'
    if (doTheyHaveItemsQuant(buyOrders[i].itemTypeID, buyOrders[i].quantity)){
      hideDiv = ''
      sellCaption = "<button id='sellToState-" + buyOrders[i].id
        + "' class='sellToState btn btn-link me-3'>[ sell ]</button>"
    }
    if (buyOrders[i].material != null){
      toolCaption = " (" + buyOrders[i].material + " / " + buyOrders[i].durability + ")"
    }
    html += "<div class='stateBuyOrders stateItemType" + buyOrders[i].itemTypeID + " " + hideDiv + "'>" + sellCaption +  "The State wants to buy "
      + buyOrders[i].quantity.toLocaleString()  + " <span class='fw-bold'>"
      + buyOrders[i].name + toolCaption + "</span> for " + buyOrders[i].cost.toLocaleString()
      + " clacks. (" + (buyOrders[i].cost / buyOrders[i].quantity).toFixed().toLocaleString() + " each - " + hoursOld + "h)</div>"
  }
  $("#stateBuyOrders").html(html)
  generateBuyOrders(buyOrderItems)
  formatBuyOrders()
}

function displayChat(){ //5
  $.get('/chat', function (data){
    $("#chatMsgs").html(data)
    formatChat()

  })
}

function displayRobots (){
  let html = "<div>Electricity: <span id='robotsElectricity'></span> "
  for (let i in robots){
    let selectedOpt = ''
    html += "<div>Robot #" + robots[i].num  + " (" + (robots[i].uses / 100 * 10)
      + "%) - <span class='fw-bold'>" + robots[i].name + "</span> "
      + "<select id='robotAction" + robots[i].id + "'> <option value='nothing'></option>"

    for (let n in actions.robots[robots[i].skillTypeID]){
      if (actions.robots[robots[i].skillTypeID][n] == robots[i].defaultAction){
        selectedOpt = ' selected '
      }
      html += "<option value='" + actions.robots[robots[i].skillTypeID][n]
        + "' " + selectedOpt + ">"
        + dedashify(actions.robots[robots[i].skillTypeID][n]) + '</option>'
    }

    html += "</select> <select id='reprogramList-" + robots[i].id
      + "' class='reprogramList'></select>"
      + " <button id='reprogram-" + robots[i].id
      + "' class='reprogram btn btn-link'>reprogram</button></div>"
    html += "<div id='robotStatus" + robots[i].id + "'></div><div id='robotError" + robots[i].id + "' class='text-danger'></div>"
  }
  $("#robotListing").html(html)
  if (robots.length > 0){
    $("#robots").removeClass('d-none')
  }
  displayRobotSkill()
}

function displayRobotSkill(){
  let html = "<option value='null'></option>"
  let actionList = []
  let badActionList = ['build', 'repair', 'make-book']
  for (let i in actions.unlocked){
    if (actions.unlocked[i].rank > 0){
      actionList.push(actions.unlocked[i].name)
    }
  }
  for (let i in actionList){
    if (badActionList.includes(actionList[i])){
      continue
    }
    html += "<option value='" + actionList[i] + "'>" + dedashify(actionList[i]) + "</option>"
  }
  $(".reprogramList").html(html)
  $("#robotSkillList").html(html)
}

function displayEquipment(){ //15
  let equipmentListings = ""
  for (i in equipment){
    let equippedClass = ''
    let durabilityCaption = " "
    if (equipment[i].id == labor.equipped){
      equippedClass = ' text-decoration-underline '
    }
    if (equipment[i].durability != null){
      durabilityCaption = " (" + equipment[i].material + " / "
        + equipment[i].durability + ") "
    }

    let equipmentDIV = "<div class='" + equippedClass + "'>" + equipment[i].name
      + durabilityCaption + (equipment[i].uses / equipment[i].totalUses * 100).toFixed(2) + "%"
    if (equipment[i].id == labor.equipped || equipment[i].id == labor.alsoEquipped){

      equipmentListings += equipmentDIV + "<span class='fw-bold ms-3'>[ equipped ]</span></div>"
    } else {
      equipmentListings += equipmentDIV
        + "<button id='equipEquipment-" + equipment[i].id
        + "' class='equipEquipment btn btn-link'>equip</button></div>"
    }
  }
  $("#equipmentListings").html(equipmentListings)
}

function displayError(errorMsg){ //4
  $("#error").html(errorMsg)
  $("#status").html('&nbsp;')
  $("#error").addClass('fw-bold')
  setTimeout(function(){
    $("#error").removeClass('fw-bold')
  }, 1000)
}

function displayHistory(){ //7
  let html=''
  for (let i in statusHistory){
    let t = statusHistory[i].created_at.split(/[- :]/);
    let d = new Date(Date.UTC(t[0], t[1]-1, t[2].substring(0, 2), t[2].substring(3), t[3], t[4].substring(0, 2)));
    let hoursOld = Math.round((Date.now()-d) / 1000/ 60 / 60 * 10) / 10;
    time = hoursOld + " hours ago"
    if (hoursOld >= 24 ){
      time = (hoursOld / 24).toFixed(1) + " days ago"
    }
    html += "<div style='color:grey;' class='text-center'>" + time + "</div>"
      + "<div class='text-center m-3'>" + statusHistory[i].status + "</div>"
  }
  $("#history").html(html)
}

function displayLand(){ //12
  $.get('/land', function(data){
    land = JSON.parse(data).land
  })
  let landURL = '/land/create'
  if ($("#landSortByFilter").val() != 'null'){
    landURL += "?sort=" + $("#landSortByFilter").val()
  }
  $.get(landURL, function(data){

    $("#landTable").html(data)
    formatLand()
  })
}

function displaySkills(id){
  let babySkills = ['contracting', 'cooking', 'exploring', 'hunting', 'mining',
    'papermaking', 'toolmaking']
  let buildingReqSkills = ['aerospaceEngineering', 'biologicalEngineering',
    'chemicalEngineering', 'cooking', 'electricalEngineering',
    'geneticEngineering', 'machining', 'nanotechnology', 'nuclearEngineering',
    'petroleumEngineering', 'smeltingIron', 'smeltingSteel',
  'smeltingCopper'  ]
  let landReqSkills = ['construction', 'farmingHerbalGreens', 'farmingPlantX', 'farmingRubber', 'farmingWheat', 'lumberjacking', 'miningStone',
    'miningIron', 'miningCoal', 'miningCopper', 'miningSand', 'miningUranium' ]
  let html = ""

  if (automation != null){
    $("#stopAutomation").removeClass('d-none')
    $("#startAutomation").addClass('d-none')
  }
  //skill in building
  if (automation == null){
    if (labor.availableSkillPoints > 0){
      $("#show-skillsSection").addClass('d-none');
      $("#skillsSection").removeClass('d-none')
      $("#hide-skillsSection").removeClass('d-none')
    } else {
      $("#hide-skillsSection").addClass('d-none');
      $("#skillsSection").addClass('d-none')
      $("#show-skillsSection").removeClass('d-none')
    }
  }

  for (i in skills){
    let buttonCaption = ""
    let divClass = ''
    let babySkillIcon = ''
    let buildingSkillIcon = ""
    let landSkillIcon = ""
    if(buildingReqSkills.includes(i)){
      buildingSkillIcon = "<img src='https://img.icons8.com/ios-glyphs/30/000000/building--v1.png'  class='ms-3'/>"
    }
    //skill in land
    if(landReqSkills.includes(i)){
      landSkillIcon = "<img src='https://img.icons8.com/ios-glyphs/30/000000/landscape.png' class='ms-3'/>"
    }
    if (babySkills.includes(i)){
      babySkillIcon = "<img src='/img/icons8-pacifier-24.png' class='ms-3'/>"
    }
    decrementButton = "<button id='decrementSkill-" + skills[i].id
      + "' class='decrementSkill invisible btn btn-warning ms-3 me-5'>&darr;</button>"
    incrementButton = "<button id='incrementSkill-" + i
      + "' class='incrementSkill invisible btn btn-info '>&uarr;</button>"
    skillButtonDivClass = 'd-none'
    if (labor.availableSkillPoints > skills[i].rank && skills[i].rank != 5){
      incrementButton = "<button id='incrementSkill-" + i
        + "' class='incrementSkill  btn btn-info '>&uarr;</button>"
      skillButtonDivClass = ''
    }
    if (skills[i].rank > 0){
      decrementButton = "<button id='decrementSkill-" + skills[i].id
      + "' class='decrementSkill  btn btn-warning ms-3 me-5'>&darr;</button>"
      skillButtonDivClass = ''
    }

    if (skills[i].rank == 0 ){
      divClass = ' emptySkill '
    }
    html += "<div class='row " + divClass + " mb-3'>"
      + "<div class='fw-bold mt-3 col-lg-2 col-6'>"
      //+ "<button id='show-skillDescription" + skills[i].id + "' class='show btn btn-link'>+</button>"
      //+ "<button id='hide-skillDescription" + skills[i].id + "' class='hide btn btn-link d-none'>-</button>"
      + skills[i].caption + ": " + skills[i].rank + babySkillIcon + landSkillIcon + buildingSkillIcon
      + "</div><div class='col-lg-10 col-6'>"
      + decrementButton + incrementButton + "</div><div>"
      + "</div><div id='skillDescription" + skills[i].id + "' class='ms-3 pb-3'>"
      + skills[i].description + "</div></div>"


  }

  $("#" + id).html(html)

  formatSkills()
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

function formatChat(){
  $('.allChat').removeClass('d-none')
  if ($("#filterChat").is(":checked")){
    $(".allChat:not('.chitChat')").addClass('d-none')
  }
}

function formatItems(){
  $(".dump").addClass('d-none')
  if ($("#showDump").is(':checked')){

    $(".dump").removeClass('d-none')
  }
}

function formatLand(){
  //$("#landOwnerFilter").val()
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

function generateBuyOrders(buyOrderItems){
  let itemNames = Object.keys(buyOrderItems)
  itemNames.sort()
  let html = "<option></option>"
  for (let i in itemNames){
    html += "<option value='" + buyOrderItems[itemNames[i]] + "'>"
      + itemNames[i] + "</option>"
  }
  $("#stateItemFilter").html(html)
}


function robotAnimation(){
  $("#robotAnimation").html($("#robotAnimation").html() + ".")
  if ($("#robotAnimation").html().length > 3){
    $("#robotAnimation").html('')
  }
}

function status(text){
  $("#status").addClass('fw-bold')
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
  refreshUI()
}
