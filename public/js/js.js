
function autoBribe(){
  $.post( "/autobribe", {amount: $("#autoBribe").val(), _token: fetchCSRF() }).done(function(data){
    land = JSON.parse(data).land
    autoBribe = JSON.parse(data).autoBribe
    status("From now on, you will now automatically submit a bribe of " + autoBribe + " clack(s) for each parcel of land  by the end of the day. ")

  })
}

function bid(landID, amount){
  $.post( "/bids", {landID: landID, amount: amount, _token: fetchCSRF() }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    clacks = JSON.parse(data).clacks
    location.reload()
  })

}

function decrementSkill(skillID){
  let csrf = fetchCSRF()
  $.post( "/skills/" + skillID, { _token: csrf, _method: 'PUT' }).done(function(data){
    labor = JSON.parse(data).labor
    currentskillPoints = labor.availableSkillPoints
    formatSkillsObjectFromDB(JSON.parse(data).skills)
    statusHistory = JSON.parse(data).history
    actions = JSON.parse(data).actions
    refreshUI()

  })
}

function dedashify(arr){
  actionArr = arr.split('-')
  let txt = ""
  for (n in actionArr){
    txt += actionArr[n] + " "
  }
  return txt.substring(0, txt.length-1)
}

function doTheyOwnLand(){
  for (i in land){
    if (land[i].userID == userID){
      return true
    }
  }
  return false
}

function doTheyOwnThisTypeOfLand(landType){
  for (i in land){
    if (land[i].userID == userID && land[i].type == landType){
      return true
    }
  }
  return false
}

function fetchBuyOrders(){
  let stateURL = '/buyOrders'
  if ($("#stateSort").val()){
    stateURL += "?sort=" + $("#stateSort:checked").val()
  }
  $.get(stateURL, function(data){
    buyOrders = JSON.parse(data)
    displayBuyOrders()
  })
}

function fetchCSRF(){
  if (csrfToken == null){
    return $("#csrf").children('input').val()
  } else {
    return csrfToken
  }
}

function fetchNumOfLand(){
  let n = 0
  for (let i in land){
    if (land[i].userID == userID){
      n++
    }
  }
  return n
}

function fetchTypeOfLand(landID){
  for (let i in land){
    if (land[i].id == landID){
      return land[i].type
    }
  }
  return null
}



function formatSkillsObjectFromDB(skillsDB){
  skills = {}
  for(i in skillsDB){
    skills[skillsDB[i].identifier] = {
      caption: skillsDB[i].name,
      description: skillsDB[i].description,
      rank: skillsDB[i].rank,
      id: skillsDB[i].id
    }
  }
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function haveTheyAlreadyLeasedABuilding(contractID){
  for (let i in buildingLeases){
    if (buildingLeases[i].contractID == contractID){
      return true
    }
  }
  return false
}
function hideForcedSkillScreen(){
  $(".forcedSkillScreen").html('')
  $(".forcedSkillScreen").addClass('d-none')
  $("#mainScreen").removeClass('d-none')
  skipForcedSkillSCreen = true
  //scrollTop: $("#lastAction").offset().top
}
function incrementSkill(skillIdentifier){
  let csrf = fetchCSRF()
  $.post( "/skills", {identifier: skillIdentifier, _token: csrf }).done(function(data){
    labor = JSON.parse(data).labor
    currentskillPoints = labor.availableSkillPoints
    formatSkillsObjectFromDB(JSON.parse(data).skills)
    statusHistory = JSON.parse(data).history
    actions = JSON.parse(data).actions
    refreshUI()
  })
}

function isThereABuyOrderForThis(itemTypeID, quantity){
  for (let i in buyOrders){
    if (buyOrders[i].itemTypeID == itemTypeID
      && quantity >= buyOrders[i].quantity){
        return true
    }
  }
  return false
}



function resetMetric(){
  for (let i in tabMetrics){
    tabMetrics[i] = 0
  }
}
function uploadMetric(){
  $.post( "/metric", {tabMetrics: tabMetrics, _token: fetchCSRF() }).done(function(data){
  })
  //resetMetric()
}

function payAllBribes(amount){
  $.post( "/bribe", {amount: amount, _token: fetchCSRF() }).done(function(data){
    avgBribe = JSON.parse(data).avgBribe
    clacks = JSON.parse(data).clacks
    land = JSON.parse(data).land
    status(JSON.parse(data).status)
    refreshUI()
  })
}

function payBribe(landID, amount){
  $.post( "/land/" + landID, {amount: amount, _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    avgBribe = JSON.parse(data).avgBribe
    clacks = JSON.parse(data).clacks
    land = JSON.parse(data).land
    status(JSON.parse(data).status)
    refreshUI()
  })
}
function playSkill(){
  if (!settings.sound){
    return
  }
  let audio = new Audio('js/skill.wav');
  audio.play();
  currentskillPoints = labor.availableSkillPoints
}

function processAndSendRobotActions(){
  let robotData = []
  for (let i in robots){
    $("#robotStatus" + i).html()
    $("#robotError" + i).html()
    if ($("#robotAction" + robots[i].id).val() != 'nothing'){
      robotData.push( {id: robots[i].id, defaultAction: $("#robotAction" + robots[i].id).val()})
    }
  }
  $.post( "/robotActions", {robotData: JSON.stringify(robotData), _token: fetchCSRF() }).done(function(data){
    actions = JSON.parse(data).actions
    buildSlots = JSON.parse(data).buildingSlots
    buildings = JSON.parse(data).buildings
    clacks        = JSON.parse(data).clacks
    equipment = JSON.parse(data).equipment
    statusHistory = JSON.parse(data).history
    items = JSON.parse(data).items
    itemCapacity = JSON.parse(data).itemCapacity
    labor = JSON.parse(data).labor
    land = JSON.parse(data).land
    numOfItems = JSON.parse(data).numOfItems
    robots      = JSON.parse(data).robots
    csrfToken = JSON.parse(data).csrf
    statusArr = JSON.parse(data).statusArr

    refreshUI()
    let robotStopped = true
    for (let i in statusArr){
      if ('error' in statusArr[i]){
        $("#robotError" + i).html(statusArr[i].error)
      } else {
        if (robotStopped){
          robotStopped = false
        }
        $("#robotStatus" + i).html(statusArr[i].status)
      }
    }
    if (robotStopped){
      stopRobot()
    }
  })
}

function programRobot(actionName){
  if (actionName == 'null'){
    return
  }
  $.post( "/robots", {actionName: actionName, _token: fetchCSRF() }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    robots = JSON.parse(data).robots
    status(JSON.parse(data).status)
    refreshUI()
  })
}

function reprogramRobot(actionName, id){
  if (actionName == 'null'){
    return
  }
  $.post( "/robots/" + id, {actionName: actionName, _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    actions = JSON.parse(data).actions
    robots = JSON.parse(data).robots
    status(JSON.parse(data).status)
    refreshUI()
  })
}

function reset(){
  $.post( "/reset/", { _token: fetchCSRF() }).done(function(data){
    location.reload()
  })
}

function sellToState(buyOrderID){
  $.post( "/buyOrders", {buyOrderID: buyOrderID, _token: fetchCSRF() }).done(function(data){
    status(JSON.parse(data).status)
    actions = JSON.parse(data).actions
    buyOrders = JSON.parse(data).buyOrders
    clacks = JSON.parse(data).clacks
    history = JSON.parse(data).history
    items = JSON.parse(data).items
    refreshUI()
  })
}

function sendChat(msg){
  $.post( "/chat", {msg: msg, _token: fetchCSRF()}).done(function(data){
    refreshUI()
  })
}

function adjustSettings(soundSetting, eatFoodSetting, useHerbMedsSetting, useBioMedsSetting, useNanoMedsSetting){
  $.post( "/settings", {soundSetting: soundSetting,
    eatFoodSetting: eatFoodSetting, useHerbMedsSetting: useHerbMedsSetting,
    useBioMedsSetting: useBioMedsSetting, useNanoMedsSetting:useNanoMedsSetting,
    _token: fetchCSRF()}).done(function(data){
      console.log(data)
    refreshUI()
  })
}

function readBook(){
  $.get('/read', function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    items = JSON.parse(data).items
    labor = JSON.parse(data).labor
    status(JSON.parse(data).status)
    refreshUI()
  })
}

function startRobot(){
  $("#robotStart").addClass('d-none')
  $("#robotStop").removeClass('d-none')
  processAndSendRobotActions()
  $("#robotAnimation").removeClass('d-none')
  robotAnimating = setInterval(robotAnimation, 100)
  robotAutomation = setInterval(function(){
    processAndSendRobotActions()

  }, 4000)
}

function stopRobot(){
  $("#robotStart").removeClass('d-none')
  $("#robotStop").addClass('d-none')
  $("#robotAnimation").addClass('d-none')
  clearInterval(robotAutomation)
  robotAutomation = null
  clearInterval(robotAnimating)
  robotAnimating = null

}

function takeover(landID, amount){
  $.post( "/bids", {landID: landID, amount: amount, _token: fetchCSRF() }).done(function(data){
    if (JSON.parse(data).error != undefined){
      displayError(JSON.parse(data).error)
      return
    }
    clacks = JSON.parse(data).clacks
    land = JSON.parse(data).land
    status(JSON.parse(data).status)
    refreshUI()

  })
}
